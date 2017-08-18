<?php

/**
* Class Gestion Permissionnaire V1.0
*/


class Mvsat {

	private $_data; //data receive from form

	var $table = 'vsat_stations'; //Main table of module
	var $last_id; //return last ID after insert command
	var $error = true; //Error bol changed when an error is occured
	var $exige_pj; // set when pk is required must be default true.
    var $log = ''; //Log of all opération.
    var $id_vsat;//ID used for get vsat
    var $vsat_info; //Array stock all prminfo 
    public function __construct($properties = array()){
    	$this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value){
    	return $this->_data[$property] = $value;
    }

    public function __get($property){
    	return array_key_exists($property, $this->_data)
    	? $this->_data[$property]
    	: null
    	;
    }

	/**
	 * Get information for one Station VSAT
	 * @return [Array] [fill $this->vsat_info]
	 */
	public function get_vsat()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_vsat;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->vsat_info = $db->RowArray();
				$this->error = true;
			}	
		}
		//return Array vsat_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}

	/**
	 * [Shw description]
	 * @param [type] $key     [Key of info_array]
	 * @param string $no_echo [Used for echo the value on View ]
	 */
	public function Shw($key,$no_echo = null)
	{
		if($this->vsat_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->vsat_info[$key];
			}

			echo $this->vsat_info[$key];
		}else{
			echo "";
		}
		
	}
	/**
     * [check_exist Check if one entrie already exist on table]
     * @param  [string] $column  [Column of field on main table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $message [Returned message if exist]
     * @param  [int] $edit       [Used if is edit action must be the ID of row edited]
     * @return [Setting]         [Set $this->error and $this->log]
     */
	private function check_exist($column, $value, $message, $edit = null)
	{
		global $db;
		$table = $this->table;
		$sql_edit = $edit == null ? null: " AND id <> $edit";
		$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
			WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

		if ($result != "0") {
			$this->error = false;
			$this->log .='</br>'.$message.' exist déjà';
		}
	}
    /**
     * [check_non_exist Check if one entrie not exist on referential table]
     * @param  [string] $table   [referential table]
     * @param  [string] $column  [Column bechecked on referential table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $message [Returned message if not  exist]
     * @return [Setting]         [Set $this->error and $this->log]
     */
    private function check_non_exist($table, $column, $value, $message)
    {
    	global $db;
    	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
    		WHERE $table.$column = ". MySQL::SQLValue($value));
    	if ($result == "0") {
    		$this->error = false;
    		$this->log .='</br>'.$message.' n\'exist pas';
    		//exit('0#'.$this->log);
    	}
    }
    /**
     * [check_file Check attached if required stop Insert this must be placed befor Insert commande]
     * @param  [string] $item [input_name of attached file we add _id]
     * @param  [string] $msg  [description]
     * @param  [int] $edit    [Used if is edit action must be the ID of row edited]
     * @return [Setting]      [Set $this->error and $this->log]
     */
    Private function check_file($item, $msg = null, $edit = null)
    {
        //Format temporary file
    	$temp_file     = $this->_data[$item.'_id'];
    	//Check if is edit action (is numeric when called from archive DB else is retrned target upload)
    	if($edit != null && !is_numeric($temp_file))
    	{
    		if(!file_exists($temp_file))
    		{
    			$this->log .= '</br>Il faut choisir '.$msg.' pour la mise à jour '.$edit;
    			$this->error = false;
    		}
    	//When is not edit do check for existing file
    	}else{
    		if($edit == null && $this->exige_.$item == true && ($this->_data[$item.'_id'] == null || !file_exists($this->_data[$item.'_id'])))
    		{
    			$this->log .= '</br>Il faut choisir '.$msg. '  '.$edit;
    			$this->error = false; 
    		}
    	}

    }

    /**
     * [save_file For save anattached file for entrie ]
     * @param  [string] $item  [input_name of attached file we add _id]
     * @param  [string] $titre [Title stored for file on Archive DB]
     * @param  [string] $type  [Type of file (Document, PDF, Image)]
     * @return [Setting]       [Set $this->error and $this->log]
     */
    private function save_file($item, $titre, $type)
    {
    	//Format all parameteres
    	$temp_file     = $this->_data[$item.'_id'];
    	$new_name_file = $item.'_'.$this->last_id;
    	$folder        = MPATH_UPLOAD.'station_vsat'.SLASH.$this->last_id;
    	$id_line       = $this->last_id;
    	$title         = $titre;
    	$table         = $this->table;
    	$column        = $item;
    	$type          = $type;

    	//Call save_file_upload from initial class
    	if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'vsat', $table, $column, $type, $edit = null))
    	{
    		$this->error = false;
    		$this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
    	}
    }

    /**
	 * [save_new_vsat Save new VSAT Station after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
    public function save_new_vsat(){


		//Befor execute do the multiple check
		// check nom de station
    	$this->Check_exist('nom_station', $this->_data['nom_station'], 'Nom de Station', null);
		//Check Referentiel managed on controller
    	if($this->exige_pj)
    	{
    		$this->check_file('pj', 'Le formulaire d\' Enregistrement.');
    	}
		//Check $this->error (true / false)
    	if($this->error == true){
    		global $db;
    		$values['id_perm']           = MySQL::SQLValue($this->_data['id_perm']);
    		$values['nom_station']       = MySQL::SQLValue($this->_data['nom_station']);
    		$values['ville']             = MySQL::SQLValue($this->_data['ville']);
    		$values['longi']             = MySQL::SQLValue($this->_data['longi']);
    		$values['latit']             = MySQL::SQLValue($this->_data['latit']);
    		$values['arch_reseau']       = MySQL::SQLValue($this->_data['arch_reseau']);
    		$values['bande_freq']        = MySQL::SQLValue($this->_data['bande_freq']);
    		$values['utilisation']       = MySQL::SQLValue($this->_data['utilisation']);
    		$values['pay_materiel']      = MySQL::SQLValue($this->_data['pay_materiel']);
    		$values['dat_entr_materiel'] = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['dat_entr_materiel'])));
    		$values['diametre_antenne']  = MySQL::SQLValue($this->_data['diametre_antenne']);
    		$values['marque_antenne']    = MySQL::SQLValue($this->_data['marque_antenne']);
    		$values['azimut']            = MySQL::SQLValue($this->_data['azimut']);
    		$values['tilt' ]             = MySQL::SQLValue($this->_data['tilt']);
    		$values['polarisation']      = MySQL::SQLValue($this->_data['polarisation']);
    		$values['marque_radio']      = MySQL::SQLValue($this->_data['marque_radio']);
    		$values['ns_radio']          = MySQL::SQLValue($this->_data['ns_radio']);
    		$values['tx_freq']           = MySQL::SQLValue($this->_data['tx_freq']);
    		$values['marque_lnb']        = MySQL::SQLValue($this->_data['marque_lnb']);
    		$values['ns_lnb']            = MySQL::SQLValue($this->_data['ns_lnb']);
    		$values['rx_freq']           = MySQL::SQLValue($this->_data['rx_freq']);
    		$values['marque_modem']      = MySQL::SQLValue($this->_data['marque_modem']);
    		$values['ns_modem']          = MySQL::SQLValue($this->_data['ns_modem']);
    		$values['ip']                = MySQL::SQLValue($this->_data['ip']);
    		$values['debit_download']    = MySQL::SQLValue($this->_data['debit_download']);
    		$values['debit_upload']      = MySQL::SQLValue($this->_data['debit_upload']);
    		$values['cout_mensuel']      = MySQL::SQLValue($this->_data['cout_mensuel']);
    		$values['satellite']         = MySQL::SQLValue($this->_data['satellite']);
    		$values['hub']               = MySQL::SQLValue($this->_data['hub']);
    		$values['revendeur']         = MySQL::SQLValue($this->_data['revendeur']);
    		$values['installateur']      = MySQL::SQLValue($this->_data['installateur']);
    		$values['last_visite']       = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['last_visite'])));
    		$values['remarq']            = MySQL::SQLValue($this->_data['remarq']);
    		$values["creusr"]            = MySQL::SQLValue(session::get('userid'));

    	//Check if Insert Query been executed (False / True)
    		if (!$result = $db->InsertRow($this->table, $values)){
				//False => Set $this->log and $this->error = false
    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Enregistrement BD non réussie'; 

    		}else{

    			$this->last_id = $result;
				//If Attached required Save file to Archive
    			if($this->exige_pj)
    			{
    				$this->save_file('pj', 'Formulaire d\'enregistrement Station VSAT '.$this->_data['nom_station'], 'Document');
    			}
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'station_vsat'.SLASH.$this->last_id;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'vsat', $this->table, 'pj_images', $edit = null))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}
                //Insert Marker MAP
                $this->add_marker_vsat($this->last_id, $this->_data['ville'], $this->_data['longi'], $this->_data['latit'], 'Station VSAT '.$this->_data['nom_station'].' -'.$this->last_id.'-');			
				//Check $this->error = true return Green message and Bol true-
    			if($this->error == true)
    			{
    				$this->log = '</br>Enregistrement réussie: <b>'.$this->_data['nom_station'].' ID: '.$this->last_id;
				//Check $this->error = false return Red message and Bol false	
    			}else{
    				$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['nom_station'];
    				$this->log .= '</br>Un problème d\'Enregistrement ';
    			}
    		}
		//Else Error false	
    	}else{
    		$this->log .='</br>Enregistrement non réussie';
    	}

        //check if last error is true then return true else rturn false.
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}

    }

    /**
	 * [edit_vsat Edit VSAT Station after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
    public function edit_vsat(){
        //Get data for selected station
    	$this->get_vsat();
        $this->last_id = $this->id_vsat; 
		//Befor execute do the multiple check
		// check nom de station
    	$this->Check_exist('nom_station', $this->_data['nom_station'], 'Nom de Station edited', $this->id_vsat);
		//Check Referentiel managed on controller
    	if($this->exige_pj)
    	{
    		$this->check_file('pj', 'Le formulaire d\' Enregistrement.', $this->_data['pj_id']);
    	}
		//Check $this->error (true / false)
    	if($this->error == true){
    		global $db;
    		$values['id_perm']           = MySQL::SQLValue($this->_data['id_perm']);
    		$values['nom_station']       = MySQL::SQLValue($this->_data['nom_station']);
    		$values['ville']             = MySQL::SQLValue($this->_data['ville']);
    		$values['longi']             = MySQL::SQLValue($this->_data['longi']);
    		$values['latit']             = MySQL::SQLValue($this->_data['latit']);
    		$values['arch_reseau']       = MySQL::SQLValue($this->_data['arch_reseau']);
    		$values['bande_freq']        = MySQL::SQLValue($this->_data['bande_freq']);
    		$values['utilisation']       = MySQL::SQLValue($this->_data['utilisation']);
    		$values['pay_materiel']      = MySQL::SQLValue($this->_data['pay_materiel']);
    		$values['dat_entr_materiel'] = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['dat_entr_materiel'])));
    		$values['diametre_antenne']  = MySQL::SQLValue($this->_data['diametre_antenne']);
    		$values['marque_antenne']    = MySQL::SQLValue($this->_data['marque_antenne']);
    		$values['azimut']            = MySQL::SQLValue($this->_data['azimut']);
    		$values['tilt' ]             = MySQL::SQLValue($this->_data['tilt']);
    		$values['polarisation']      = MySQL::SQLValue($this->_data['polarisation']);
    		$values['marque_radio']      = MySQL::SQLValue($this->_data['marque_radio']);
    		$values['ns_radio']          = MySQL::SQLValue($this->_data['ns_radio']);
    		$values['tx_freq']           = MySQL::SQLValue($this->_data['tx_freq']);
    		$values['marque_lnb']        = MySQL::SQLValue($this->_data['marque_lnb']);
    		$values['ns_lnb']            = MySQL::SQLValue($this->_data['ns_lnb']);
    		$values['rx_freq']           = MySQL::SQLValue($this->_data['rx_freq']);
    		$values['marque_modem']      = MySQL::SQLValue($this->_data['marque_modem']);
    		$values['ns_modem']          = MySQL::SQLValue($this->_data['ns_modem']);
    		$values['ip']                = MySQL::SQLValue($this->_data['ip']);
    		$values['debit_download']    = MySQL::SQLValue($this->_data['debit_download']);
    		$values['debit_upload']      = MySQL::SQLValue($this->_data['debit_upload']);
    		$values['cout_mensuel']      = MySQL::SQLValue($this->_data['cout_mensuel']);
    		$values['satellite']         = MySQL::SQLValue($this->_data['satellite']);
    		$values['hub']               = MySQL::SQLValue($this->_data['hub']);
    		$values['revendeur']         = MySQL::SQLValue($this->_data['revendeur']);
    		$values['installateur']      = MySQL::SQLValue($this->_data['installateur']);
    		$values['last_visite']       = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['last_visite'])));
            $values['remarq']            = MySQL::SQLValue($this->_data['remarq']);
    		$values["updusr"]            = MySQL::SQLValue(session::get('userid'));
    		$values["upddat"]            = ' CURRENT_TIMESTAMP ';
    		$wheres["id"]                = MySQL::SQLValue($this->id_vsat);

    	    //Check if Update Query been executed (False / True)
    		if (!$result = $db->UpdateRows($this->table, $values, $wheres)){
				//False => Set $this->log and $this->error = false
    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Modification BD non réussie'; 

    		}else{
				//If Attached required Save file to Archive
    			if($this->exige_pj)
    			{
    				$this->save_file('pj', 'Formulaire d\'enregistrement Station VSAT '.$this->_data['nom_station'], 'Document');
    			}
				//Esspionage
    			if(!$db->After_update($this->table, $this->id_vsat, $values, $this->vsat_info)){
    				$this->log .= '</br>Problème Esspionage';
    				$this->error = false;	
    			}
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'station_vsat'.SLASH.$this->id_vsat;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_vsat, $this->_data['photo_titl'], 'vsat', $this->table, 'pj_images', $this->vsat_info['pj_images']))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}
                //Insert Marker MAP
                $this->add_marker_vsat($this->id_vsat, $this->_data['ville'], $this->_data['longi'], $this->_data['latit'], 'Station VSAT '.$this->_data['nom_station'].' -'.$this->id_vsat.'-');			
				//Check $this->error = true return Green message and Bol true
    			if($this->error == true)
    			{
    				$this->log = '</br>Modification réussie: <b>'.$this->_data['nom_station'].' ID: '.$this->id_vsat;
				//Check $this->error = false return Red message and Bol false	
    			}else{
    				$this->log .= '</br>Modification réussie: <b>'.$this->_data['nom_station'];
    				$this->log .= '</br>Un problème d\'Enregistrement ';
    			}
    		}
		//Else Error false	
    	}else{
    		$this->log .='</br>Modification non réussie';
    	}

        //check if last error is true then return true else rturn false.
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}
    }

    /**
     * [delete_vsat Delete VSAT Station]
     * @return [bol] [Send bol value to controller]
     */
    public function delete_vsat()
    {
    	//Get info vsat by id_vsat else return false
    	if(!$this->get_vsat())
    	{
    		$this->app_action .= 'Fatal Error';
    		return false;
    	}
    	global $db;
    	$id   = $this->vsat_info['id'];
    	$sql = "DELETE FROM ".$this->table." WHERE id =".$id ;
    	if(!$db->Query($sql))
    	{
    		$this->log .= $db->Error();
    		$this->error = false;
    		$this->log .='</br>Suppression  non réussie'; 
    	}else{
    		$folder = MPATH_UPLOAD.'station_vsat'.SLASH.$this->id_vsat;
    		if(MInit::deleteDir($folder)){
    			$this->error = false;
    		    $this->log .='</br>Suppression dossier échouée';
    		}
    		$this->error = true;
    		$this->log = '</br>Suppression réussie';
    	}
		//return Array prm_info
    	if($this->error == false)
    	{
    		return false;
    	}else{
    		return true;
    	}
    }


    /**
     * [add_marker_gsm Add Marker to map]
     * @param [type] $id_gsm [description]
     * @param [type] $ville  [description]
     * @param [type] $log    [description]
     * @param [type] $lalt   [description]
     * @param [type] $text   [description]
     */
    public function add_marker_vsat($id_vsat, $ville, $log, $lalt, $text)
    {

        
        global $db;
        //Delete if an marker have same value
        if(!$db->Query("DELETE FROM map_markers WHERE marker_categorie = 'vsat' AND marker_line_id = $id_vsat"))
        {
            $this->error = false;
            $this->log .='</br>Unable to delete existing Marker'; 
        }

        $values["marker_categorie"] = MySQL::SQLValue('vsat');
        $values["marker_line_id"]   = MySQL::SQLValue($id_vsat);
        $values["marker_ville"]     = MySQL::SQLValue($ville);
        $values["marker_longitude"] = MySQL::SQLValue($log);
        $values["marker_latitude"]  = MySQL::SQLValue($lalt);
        $values["marker_text"]      = MySQL::SQLValue($text);
        $values["marker_actif"]     = MySQL::SQLValue(1);
        
        
        
        // If we have an error
        if($this->error == true){

            if (!$result = $db->InsertRow("map_markers", $values)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Enregistrement Marker BD non réussie'; 

            }else{

                if(!$db->UpdateSinglRows($this->table, 'marker_map', $result, $id_vsat))
                {
                    $this->error = false;
                    $this->log .='</br>Enregitrement Marker dans Table VSAT non réussie'; 
                }else{
                    $this->error = true;
                    $this->log = '</br>Enregistrement Marker réussie: <b>';
                }

            }


        }else{

            $this->log .='</br>Enregistrement Marker non réussie';

        }

        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }

    }



 public function printattribute($attibute)
    {
      if($this->vsat_info[$attibute] != null)
      {
        echo $this->vsat_info[$attibute];
      }else{
        echo "";
      }
      
    }
    
    
    public function get_vsat_info() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.*,permissionnaires.r_social AS permissionnaire,
                        installateurs.denomination AS installateur,
                        revendeurs.denomination AS revendeur,
                        ref_pays.pays AS pays,
                        ref_ville.ville AS ville,
                        vsat_hub.operateur AS hub,
                        vsat_satellite.satellite as satellite
                FROM $table,permissionnaires,installateurs,revendeurs,ref_pays,ref_ville,vsat_hub,vsat_satellite"
                . " WHERE  $table.id_perm  = permissionnaires.id 
                AND $table.ville=ref_ville.id
                AND $table.pay_materiel=ref_pays.id
                AND $table.revendeur=revendeurs.id
                AND $table.installateur=installateurs.id    
                AND $table.hub=vsat_hub.id 
                And $table.satellite=vsat_satellite.id
               AND $table.id = " . $this->id_vsat;
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->vsat_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array prm_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    
    
}



?>