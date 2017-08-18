<?php

/**
* Class Gestion Permissionnaire V1.0
*/


class Mgsm {

	private $_data; //data receive from form

	var $table = 'gsm_stations'; //Main table of module
	var $last_id; //return last ID after insert command
	var $error = true; //Error bol changed when an error is occured
	var $exige_pj; // set when pk is required must be default true.
    var $log = ''; //Log of all opération.
    var $id_gsm;//ID used for get gsm
    var $gsm_info; //Array stock all prminfo 
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
	public function get_gsm()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.*,v.ville as villes FROM 
        $table,ref_ville v WHERE $table.ville=v.id and  $table.id = ".$this->id_gsm;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->gsm_info = $db->RowArray();
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
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->gsm_info[$key] != null)
      {
        echo $this->gsm_info[$key];
      }else{
        echo "";
      }
      
    }

	/**
	 * [Shw description]
	 * @param [type] $key     [Key of info_array]
	 * @param string $no_echo [Used for echo the value on View ]
	 */
	public function Shw($key,$no_echo = null)
	{
		if($this->gsm_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->gsm_info[$key];
			}

			echo $this->gsm_info[$key];
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
    	$folder        = MPATH_UPLOAD.'station_gsm'.SLASH.$this->last_id;
    	$id_line       = $this->last_id;
    	$title         = $titre;
    	$table         = $this->table;
    	$column        = $item;
    	$type          = $type;

    	//Call save_file_upload from initial class
    	if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'gsm', $table, $column, $type, $edit = null))
    	{
    		$this->error = false;
    		$this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
    	}
    }

    /**
	 * [save_new_gsm Save new VSAT Station after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
    public function save_new_gsm(){


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

            $user_info = new Musers();
            $user_info->id_user = session::get('userid');
            $user_info->get_user();
            $service=$user_info->Shw('service_user',1);

    		$values["nom_station"]       = MySQL::SQLValue($this->_data['nom_station']);
    		//$values["id_perm"]           = MySQL::SQLValue($this->_data['id_perm']);
            $values["operateur"]         = MySQL::SQLValue($service);
    		$values["adresse"]           = MySQL::SQLValue($this->_data['adresse']);
    		$values["ville"]             = MySQL::SQLValue($this->_data['ville']);
    		$values["longi"]             = MySQL::SQLValue($this->_data['longi']);
    		$values["latit"]             = MySQL::SQLValue($this->_data['latit']);
    		$values["type_support"]      = MySQL::SQLValue($this->_data['type_support']);
    		$values["shared_site"]       = MySQL::SQLValue($this->_data['shared_site']);
    		$values["oper_share"]        = MySQL::SQLValue($this->_data['oper_share']);
    		$values["power_generator"]   = MySQL::SQLValue($this->_data['power_generator']);
    		$values["power_company"]     = MySQL::SQLValue($this->_data['power_company']);
    		$values["power_solar"]       = MySQL::SQLValue($this->_data['power_solar']);
    		$values["bh_vsat"]           = MySQL::SQLValue($this->_data['bh_vsat']);
    		$values["bh_fh"]             = MySQL::SQLValue($this->_data['bh_fh']);
    		$values["bh_fibre"]          = MySQL::SQLValue($this->_data['bh_fibre']);
    		$values["tech_2g"]           = MySQL::SQLValue($this->_data['tech_2g']);
    		$values["tech_3g"]           = MySQL::SQLValue($this->_data['tech_3g']);
    		$values["tech_4g"]           = MySQL::SQLValue($this->_data['tech_4g']);
    		$values["tech_cdma"]         = MySQL::SQLValue($this->_data['tech_cdma']);
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
    				$this->save_file('pj', 'Formulaire d\'enregistrement Station GSM '.$this->_data['nom_station'], 'Document');
    			}
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'station_gsm'.SLASH.$this->last_id;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'gsm', $this->table, 'pj_images', $edit = null))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}			
				//Check $this->error = true return Green message and Bol true
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
	 * [edit_gsm Edit GSM Station after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
    public function edit_gsm(){
        //Get data for selected station
    	$this->get_gsm();
        $this->last_id = $this->id_gsm; 
		//Befor execute do the multiple check
		// check nom de station
    	$this->Check_exist('nom_station', $this->_data['nom_station'], 'Nom de Station edited', $this->id_gsm);
		//Check Referentiel managed on controller
    	if($this->exige_pj)
    	{
    		$this->check_file('pj', 'Le formulaire d\' Enregistrement.', $this->_data['pj_id']);
    	}
		//Check $this->error (true / false)
    	if($this->error == true){
    		global $db;
            
            $user_info = new Musers();
            $user_info->id_user = session::get('userid');
            $user_info->get_user();
            $service=$user_info->Shw('service_user',1);
            
    		$values["nom_station"]       = MySQL::SQLValue($this->_data['nom_station']);
            //$values["id_perm"]           = MySQL::SQLValue($this->_data['id_perm']);
            $values["operateur"]         = MySQL::SQLValue($service);
    		$values["adresse"]           = MySQL::SQLValue($this->_data['adresse']);
    		$values["ville"]             = MySQL::SQLValue($this->_data['ville']);
    		$values["longi"]             = MySQL::SQLValue($this->_data['longi']);
    		$values["latit"]             = MySQL::SQLValue($this->_data['latit']);
    		$values["type_support"]      = MySQL::SQLValue($this->_data['type_support']);
    		$values["shared_site"]       = MySQL::SQLValue($this->_data['shared_site']);
    		$values["oper_share"]        = MySQL::SQLValue($this->_data['oper_share']);
    		$values["power_generator"]   = MySQL::SQLValue($this->_data['power_generator']);
    		$values["power_company"]     = MySQL::SQLValue($this->_data['power_company']);
    		$values["power_solar"]       = MySQL::SQLValue($this->_data['power_solar']);
    		$values["bh_vsat"]           = MySQL::SQLValue($this->_data['bh_vsat']);
    		$values["bh_fh"]             = MySQL::SQLValue($this->_data['bh_fh']);
    		$values["bh_fibre"]          = MySQL::SQLValue($this->_data['bh_fibre']);
    		$values["tech_2g"]           = MySQL::SQLValue($this->_data['tech_2g']);
    		$values["tech_3g"]           = MySQL::SQLValue($this->_data['tech_3g']);
    		$values["tech_4g"]           = MySQL::SQLValue($this->_data['tech_4g']);
    		$values["tech_cdma"]         = MySQL::SQLValue($this->_data['tech_cdma']);
    		$values["updusr"]            = MySQL::SQLValue(session::get('userid'));
    		$values["upddat"]            = ' CURRENT_TIMESTAMP ';
    		$wheres["id"]                = MySQL::SQLValue($this->id_gsm);

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
    				$this->save_file('pj', 'Formulaire d\'enregistrement Station GSM '.$this->_data['nom_station'], 'Document');
    			}
				//Esspionage
    			if(!$db->After_update($this->table, $this->id_gsm, $values, $this->gsm_info)){
    				$this->log .= '</br>Problème Esspionage';
    				$this->error = false;	
    			}
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'station_gsm'.SLASH.$this->id_gsm;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_gsm, $this->_data['photo_titl'], 'gsm', $this->table, 'pj_images', $this->gsm_info['pj_images']))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}			
				//Check $this->error = true return Green message and Bol true
    			if($this->error == true)
    			{
    				$this->log = '</br>Modification réussie: <b>'.$this->_data['nom_station'].' ID: '.$this->id_gsm;
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
     * [valid_gsm_station Validation gsm ]
     * @param  integer $etat [Etat line]
     * @return [bol]        [Send bol to controller]
     */
    public function valid_station_gsm($etat = 0)
    {
        global $db;
        $etat = $etat == 0 ? 1 : 0;
        $gsm_id = $this->id_gsm;
            //Format value for requet
        $value["etat"] = MySQL::SQLValue($etat);
        $where["id"] =  $gsm_id;
        // Execute the update and show error case error
        if( !$result = $db->UpdateRows("gsm_stations", $value , $where))
        {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>'.$db->Error();
            $this->error = false;
        }else{
            $this->log .= '</br>Statut changé! ';
            $this->error = true;

        } 
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }




    /**
     * [delete_gsm Delete GSM Station]
     * @return [bol] [Send bol value to controller]
     */
    public function delete_gsm()
    {
    	//Get info gsm by id_gsm else return false
    	if(!$this->get_gsm())
    	{
    		$this->app_action .= 'Fatal Error';
    		return false;
    	}
    	global $db;
    	$id   = $this->gsm_info['id'];
    	$sql = "DELETE FROM ".$this->table." WHERE id =".$id ;
    	if(!$db->Query($sql))
    	{
    		$this->log .= $db->Error();
    		$this->error = false;
    		$this->log .='</br>Suppression  non réussie'; 
    	}else{
    		$folder = MPATH_UPLOAD.'station_gsm'.SLASH.$this->id_vsat;
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
}



?>