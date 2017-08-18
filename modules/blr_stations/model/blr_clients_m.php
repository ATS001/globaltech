<?php

/**
* Class Gestion BLR CLIENTS V1.0
*/


class Mblr_clients{

	private $_data; //data receive from form

	var $table = 'blr_clients'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = ''; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
	var $exige_pj; // set when pk is required must be default true.
	var $id_blr_clients; // User ID append when request
	var $token; //user for recovery function
	var $blr_clients_info; //Array stock all blrinfo 
	var $app_action; //Array action for each row

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
	 * Get information for one client
	 * @return [Array] [fill $this->blr_stations_info]
	 */
	public function get_blr_clients()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_blr_clients;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->blr_clients_info = $db->RowArray();
				$this->error = true;
			}	
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
	 * [Shw description]
	 * @param [type] $key     [Key of info_array]
	 * @param string $no_echo [Used for echo the value on View ]
	 */
	public function Shw($key,$no_echo = null)
	{
		if($this->blr_clients_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->blr_clients_info[$key];
			}

			echo $this->blr_clients_info[$key];
		}else{
			echo "";
		}
		
	}



	public function save_new_blr_clients(){

		$this->check_freq_correct($this->_data['station_base'], $this->_data['frequence']);	
		global $db;
		$values["station_base"] = MySQL::SQLValue($this->_data['station_base']);
		$values["site"]         = MySQL::SQLValue($this->_data['site']);
		$values["longi"]        = MySQL::SQLValue($this->_data['longi']);
		$values["latit"]        = MySQL::SQLValue($this->_data['latit']);
		$values["hauteur"]      = MySQL::SQLValue($this->_data['hauteur']);
		$values["frequence"]    = MySQL::SQLValue($this->_data['frequence']);
		$values["marque"]       = MySQL::SQLValue($this->_data['marque']);
		$values["modele"]       = MySQL::SQLValue($this->_data['modele']);
		$values["remarq"]       = MySQL::SQLValue($this->_data['remarq']);
        $values["creusr"]       = MySQL::SQLValue(session::get('userid'));	
      
      if($this->error == true){
			if (!$result = $db->InsertRow("blr_clients", $values)) {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'blr_clients'.SLASH.$this->last_id;
				//Call save_multiple files
				if($this->exige_pj)
    			{
    				$this->save_file('pj', 'Formulaire d\'enregistrement Client BLR '.$this->_data['site'], 'Document');
    			}
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'blr_clients', $this->table, 'pj_images', $edit = null))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}

    			//Insert Marker MAP
                if(!$this->add_marker_blr_clt($this->last_id, $this->_data['longi'], $this->_data['latit'], 'Station BLR CLient '.$this->_data['site'].' -'.$this->last_id.'-'))
                {
                	$this->error = false;
    				$this->log .='</br>Problème Ajout Marker';

                }	

				$this->log .='</br>Enregistrement  réussie '. $this->_data['station_base'] .' - '.$this->last_id.' -';
			}


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
    	$folder        = MPATH_UPLOAD.'blr_clients'.SLASH.$this->last_id;
    	$id_line       = $this->last_id;
    	$title         = $titre;
    	$table         = $this->table;
    	$column        = $item;
    	$type          = $type;

    	//Call save_file_upload from initial class
    	if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'blr_clients', $table, $column, $type, $edit = null))
    	{
    		$this->error = false;
    		$this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
    	}
    }

	 public function edit_blr_clients(){
        //Get data for selected station
    	$this->get_blr_clients();
        $this->last_id = $this->id_blr_clients; 
		$this->check_freq_correct($this->_data['station_base'], $this->_data['frequence']);
		global $db;
		$values["station_base"] = MySQL::SQLValue($this->_data['station_base']);
		$values["site"]         = MySQL::SQLValue($this->_data['site']);
		$values["longi"]        = MySQL::SQLValue($this->_data['longi']);
		$values["latit"]        = MySQL::SQLValue($this->_data['latit']);
		$values["hauteur"]      = MySQL::SQLValue($this->_data['hauteur']);
		$values["frequence"]    = MySQL::SQLValue($this->_data['frequence']);
		$values["marque"]       = MySQL::SQLValue($this->_data['marque']);
		$values["modele"]       = MySQL::SQLValue($this->_data['modele']);
		$values["remarq"]       = MySQL::SQLValue($this->_data['remarq']);
        $values["updusr"]       = MySQL::SQLValue(session::get('userid'));             
        $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]           = MySQL::SQLValue($this->id_blr_clients);
		//Check $this->error (true / false)
    	if($this->error == true){
    	    //Check if Update Query been executed (False / True)
    		if (!$result = $db->UpdateRows($this->table, $values, $wheres)){
    			//False => Set $this->log and $this->error = false
    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Modification BD non réussie'; 

    		}else{

				//Esspionage
    			if(!$db->After_update($this->table, $this->id_blr_clients, $values, $this->blr_clients_info)){
    				$this->log .= '</br>Problème Esspionage';
    				$this->error = false;	
    			}
    			if($this->exige_pj)
    			{
    				$this->save_file('pj', 'Formulaire d\'enregistrement  '.$this->_data['site'], 'Document');
    			}
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'blr_clients'.SLASH.$this->id_blr_clients;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_blr_clients, $this->_data['photo_titl'], 'blr_clients', $this->table, 'pj_images', $this->blr_clients_info['pj_images']))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}
    			//Insert Marker MAP
                if(!$this->add_marker_blr_clt($this->last_id, $this->_data['longi'], $this->_data['latit'], 'Station BLR CLient '.$this->_data['site'].' -'.$this->last_id.'-'))
                {
                	$this->error = false;
    				$this->log .='</br>Problème Ajout Marker';

                }			
				//Check $this->error = true return Green message and Bol true
    			if($this->error == true)
    			{
    				$this->log = '</br>Modification réussie: <b>'.$this->_data['station_base'].' ID: '.$this->id_blr_clients;
				//Check $this->error = false return Red message and Bol false	
    			}else{
    				$this->log .= '</br>Modification réussie: <b>'.$this->_data['station_base'];
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
    
    public function delete_blr_clients()
    {
    	global $db;
    	$id_blr_clients = $this->id_blr_clients;
    	$where['id'] = MySQL::SQLValue($id_blr_clients);
    	if(!$db->DeleteRows('blr_clients',$where))
    	{
    		$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		$this->error = true;
    		$this->log .='</br>Suppression réussie';
    	}
    	//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
    }
    
   

    public function valid_blr_clients()
	{
		global $db;
		$values['etat'] = ' ETAT + 1 ';
		$wheres['id']    = MySQL::SQLValue($this->id_blr_clients);

		if(!$result = $db->UpdateRows('blr_clients', $values, $wheres))
		{
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .= 'Activation non réussie DB';

		}else{
			$this->log .= 'Activation réussie';
			$this->error = true;

		}
		if($this->error == false){
			return false;
		}else{
			return true;
		}
	}

         public function printattribute($attibute) {
        if ($this->blr_clients_info[$attibute] != null) {
            echo $this->blr_clients_info[$attibute];
        } else {
            echo "";
        }
    }
    
    public function get_blr_client_info() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.*,blr_stations.site,permissionnaires.r_social
                FROM $table,permissionnaires,blr_stations"
                . " WHERE  blr_stations.prm  = permissionnaires.id "
                . "AND blr_stations.id  = blr_clients.station_base "
                . "AND $table.id = " . $this->id_blr_clients;
        
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->blr_clients_info = $db->RowArray();
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

        /**
     * [add_marker_gsm Add Marker to map]
     * @param [type] $id_blr_clt [description]
     * @param [type] $ville  [description]
     * @param [type] $log    [description]
     * @param [type] $lalt   [description]
     * @param [type] $text   [description]
     */
    public function add_marker_blr_clt($id_blr_clt, $log, $lalt, $text)
    {

        
        global $db;
        $station_base = new Mblr_stations();
        $station_base->id_blr_stations = $this->_data['station_base'];
        $station_base->get_blr_stations();

        $marker_line_lat  = $station_base->blr_stations_info['latit'];
        $marker_line_long = $station_base->blr_stations_info['longi'];
        $ville            = $station_base->blr_stations_info['ville'];


        //Delete if an marker have same value
        if(!$db->Query("DELETE FROM map_markers WHERE marker_categorie = 'blr_clt' AND marker_line_id = $id_blr_clt"))
        {
            $this->error = false;
            $this->log .='</br>Unable to delete existing Marker'; 
        }

        $values["marker_categorie"] = MySQL::SQLValue('blr_clt');
        $values["marker_line_id"]   = MySQL::SQLValue($id_blr_clt);
        $values["marker_ville"]     = MySQL::SQLValue($ville);
        $values["marker_longitude"] = MySQL::SQLValue($log);
        $values["marker_latitude"]  = MySQL::SQLValue($lalt);
        $values["marker_text"]      = MySQL::SQLValue($text);
        $values["marker_network"]   = MySQL::SQLValue(1);
        $values["marker_line_lat"]  = MySQL::SQLValue($marker_line_lat);
        $values["marker_line_long"] = MySQL::SQLValue($marker_line_long);
        $values["marker_actif"]     = MySQL::SQLValue(1);
        
        
        
        // If we have an error
        if($this->error == true){

            if (!$result = $db->InsertRow("map_markers", $values)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Enregistrement Marker BD non réussie'; 

            }else{

                if(!$db->UpdateSinglRows($this->table, 'marker_map', $result, $id_blr_clt))
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

    private function check_freq_correct($id_station, $frequence)
    {
    	global $db;
        $station_base = new Mblr_stations();
        $station_base->id_blr_stations = $id_station;
        $station_base->get_blr_stations();

        $frequences = $station_base->blr_stations_info['frequence'];

        if (strpos($frequences, $frequence) !== false) 
        {
            $this->log .= "";
        
        }else{
            exit("0#La fréquence doit être une des fréquence suivante: </br><b>".$frequences.'  '. $frequence);
        }
        

    }

}


?>