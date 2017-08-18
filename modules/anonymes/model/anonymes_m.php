<?php 
/**
* Class Gestion des anonymes
*/


class Manonyme {

	private $_data; //data receive from form
	var $table = 'prm_anonyme'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
        var $id_anonyme; // anonyme ID append when request
	var $token; //Service for recovery function
	var $anonyme_info; //Array stock all satelliteinfo
        var $test;


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

	public function Shw($key,$no_echo = "")
	{
		if($this->anonyme_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->anonyme_info[$key];
			}

			echo $this->anonyme_info[$key];
		}else{
			echo "";
		}
		
	}
	
        
        
        /**
	 * Get information for one Station VSAT
	 * @return [Array] [fill $this->vsat_info]
	 */
	public function get_anonyme()
	{
		global $db;
		$table = $this->table;
                
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_anonyme;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->anonyme_info = $db->RowArray();
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
        
	 //Save new satellite after all check
	public function save_new_anonyme(){

		

		global $db;
		$values["titre"]              = MySQL::SQLValue($this->_data['titre']);
		$values["longi"]              = MySQL::SQLValue($this->_data['longi']);
		$values["latit"]              = MySQL::SQLValue($this->_data['latit']);
		$values["technologie"]        = MySQL::SQLValue($this->_data['technologie']);
		$values["remarque"]           = MySQL::SQLValue($this->_data['remarque']);
		$values["date_visite"]        = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_visite'])));
        $values["creusr"]             = MySQL::SQLValue(session::get('userid'));	
      
      if($this->error == true){
			if (!$result = $db->InsertRow("prm_anonyme", $values)) {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'anonymes'.SLASH.$this->last_id;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'anonymes', $this->table, 'pj_images', $edit = null))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}

				$this->log .='</br>Enregistrement  réussie '. $this->_data['titre'] . '  '.$this->_data['technologie'] .' - '.$this->last_id.' -';
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
    	$folder        = MPATH_UPLOAD.'anonymes'.SLASH.$this->last_id;
    	$id_line       = $this->last_id;
    	$title         = $titre;
    	$table         = $this->table;
    	$column        = $item;
    	$type          = $type;

    	//Call save_file_upload from initial class
    	if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'anonymes', $table, $column, $type, $edit = null))
    	{
    		$this->error = false;
    		$this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
    	}
    }

	 public function edit_anonyme(){
        //Get data for selected station
    	$this->get_anonyme();
        $this->last_id = $this->id_anonyme; 
		
		global $db;
		$values["titre"]              = MySQL::SQLValue($this->_data['titre']);
		$values["longi"]   			  = MySQL::SQLValue($this->_data['longi']);
		$values["latit"]    	      = MySQL::SQLValue($this->_data['latit']);
		$values["technologie"]        = MySQL::SQLValue($this->_data['technologie']);
		$values["remarque"]           = MySQL::SQLValue($this->_data['remarque']);
		$values["date_visite"]        = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_visite'])));
        $values["updusr"]             = MySQL::SQLValue(session::get('userid'));              
        $values["upddat"]             = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]                 = MySQL::SQLValue($this->id_anonyme);
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
    			if(!$db->After_update($this->table, $this->id_anonyme, $values, $this->anonyme_info)){
    				$this->log .= '</br>Problème Esspionage';
    				$this->error = false;	
    			}
				//Add Gallerie Images
				//Set Folder
    			$folder        = MPATH_UPLOAD.'anonymes'.SLASH.$this->id_anonyme;
				//Call save_multiple files
				//save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
    			if(!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_anonyme, $this->_data['photo_titl'], 'anonymes', $this->table, 'pj_images', $this->anonyme_info['pj_images']))
    			{
    				$this->error = false;
    				$this->log .='</br>Problème archivage Images';
    			}			
				//Check $this->error = true return Green message and Bol true
    			if($this->error == true)
    			{
    				$this->log = '</br>Modification réussie: <b>'.$this->_data['titre'].' ID: '.$this->id_anonyme;
				//Check $this->error = false return Red message and Bol false	
    			}else{
    				$this->log .= '</br>Modification réussie: <b>'.$this->_data['titre'];
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

public function delete_anonyme()
    {
    	//Get info vsat by id_vsat else return false
    	if(!$this->get_anonyme())
    	{
    		$this->app_action .= 'Fatal Error';
    		return false;
    	}
    	global $db;
    	$id   = $this->anonyme_info['id'];
    	$sql = "DELETE FROM ".$this->table." WHERE id =".$id ;
    	if(!$db->Query($sql))
    	{
    		$this->log .= $db->Error();
    		$this->error = false;
    		$this->log .='</br>Suppression  non réussie'; 
    	}else{
    		$folder = MPATH_UPLOAD.'anonymes'.SLASH.$this->id_anonyme;
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


public function valid_anonyme()
	{
		global $db;
		$values['etat'] = ' ETAT + 1 ';
		$wheres['id']    = MySQL::SQLValue($this->id_anonyme);

		if(!$result = $db->UpdateRows('prm_anonyme', $values, $wheres))
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
        
        public function printattribute($attibute)
    {
      if($this->anonyme_info[$attibute] != null)
      {
        echo $this->anonyme_info[$attibute];
      }else{
        echo "";
      }
      
    }
}