<?php 
/**
* Class Gestion Hubs
*/


class Mvsat_hub {
	private $_data; //data receive from form
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
     var $id_vsat_hub; // satellite ID append when request
	var $token; //Service for recovery function
	var $vsat_hub_info; //Array stock all vsat_hub_info
	

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
		if($this->vsat_hub_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->vsat_hub_info[$key];
			}

			echo $this->vsat_hub_info[$key];
		}else{
			echo "";
		}
		
	}
	
		//Get all info hub fro database for edit form

	public function get_vsat_hub()
	{
		global $db;

		$sql = "SELECT vsat_hub.* FROM 
		vsat_hub WHERE vsat_hub.id = ".$this->id_vsat_hub;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->vsat_hub_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array vsat_hub_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}
	 //Save new vsat_hub after all check
	public function save_new_vsat_hub(){

		

		global $db;
		$values["operateur"]        = MySQL::SQLValue($this->_data['operateur']);
		$values["pays_hub"]        = MySQL::SQLValue($this->_data['pays_hub']);
		$values["ville_hub"]        = MySQL::SQLValue($this->_data['ville_hub']);
		$values["email_hub"]        = MySQL::SQLValue($this->_data['email_hub']);
        $values["creusr"]    = MySQL::SQLValue(session::get('userid'));	
      
      if($this->error == true){
			if (!$result = $db->InsertRow("vsat_hub", $values)) {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['operateur'] . '  '.$this->_data['email_hub'] .' - '.$this->last_id.' -';
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

	public function update_vsat_hub()
	{
		$this->get_vsat_hub();
		
		$this->last_id = $this->id_vsat_hub;

		global $db;
		$values["operateur"]        = MySQL::SQLValue($this->_data['operateur']);
		$values["pays_hub"]        = MySQL::SQLValue($this->_data['pays_hub']);
		$values["ville_hub"]        = MySQL::SQLValue($this->_data['ville_hub']);
		$values["email_hub"]        = MySQL::SQLValue($this->_data['email_hub']);
        $values["updusr"]  = MySQL::SQLValue(session::get('userid'));              
        $values["upddat"]  = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]      = $this->id_vsat_hub;
		
		if($this->error == true){
			if (!$result = $db->UpdateRows("vsat_hub", $values, $wheres)) {
				
               	//$db->Kill();
				$this->log .= $db->Error()." ".$db->BuildSQLUpdate("vsat_hub", $values, $wheres);
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{
			$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['operateur'];

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

public function delete_vsat_hub()
    {
    	global $db;
    	$id_vsat_hub = $this->id_vsat_hub;
    	$where['id'] = MySQL::SQLValue($id_vsat_hub);
    	if(!$db->DeleteRows('vsat_hub',$where))
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


public function valid_vsat_hub()
	{
		global $db;
		$values['etat'] = ' ETAT + 1 ';
		$wheres['id']    = MySQL::SQLValue($this->id_vsat_hub);

		if(!$result = $db->UpdateRows('vsat_hub', $values, $wheres))
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
}