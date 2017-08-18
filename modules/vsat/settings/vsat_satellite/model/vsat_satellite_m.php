<?php 
/**
* Class Gestion satellites
*/


class Mvsat_satellite {
	private $_data; //data receive from form
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
     var $id_vsat_satellite; // satellite ID append when request
	var $token; //Service for recovery function
	var $vsat_satellite_info; //Array stock all satelliteinfo
	

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
		if($this->vsat_satellite_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->vsat_satellite_info[$key];
			}

			echo $this->vsat_satellite_info[$key];
		}else{
			echo "";
		}
		
	}
	
		//Get all info satellite fro database for edit form

	public function get_vsat_satellite()
	{
		global $db;

		$sql = "SELECT vsat_satellite.* FROM 
		vsat_satellite WHERE vsat_satellite.id = ".$this->id_vsat_satellite;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->vsat_satellite_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array vsat_satellite_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}
	 //Save new satellite after all check
	public function save_new_vsat_satellite(){

		

		global $db;
		$values["satellite"]        = MySQL::SQLValue($this->_data['satellite']);
		$values["position_orbitale"]        = MySQL::SQLValue($this->_data['position_orbitale']);
		$values["pay_operator"]        = MySQL::SQLValue($this->_data['pay_operator']);
		$values["contractor"]        = MySQL::SQLValue($this->_data['contractor']);
        $values["creusr"]    = MySQL::SQLValue(session::get('userid'));	
      
      if($this->error == true){
			if (!$result = $db->InsertRow("vsat_satellite", $values)) {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['satellite'] . '  '.$this->_data['position_orbitale'] .' - '.$this->last_id.' -';
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

	public function update_vsat_satellite()
	{
		$this->get_vsat_satellite();
		
		$this->last_id = $this->id_vsat_satellite;

		global $db;
		$values["satellite"]        = MySQL::SQLValue($this->_data['satellite']);
		$values["position_orbitale"]        = MySQL::SQLValue($this->_data['position_orbitale']);
		$values["pay_operator"]        = MySQL::SQLValue($this->_data['pay_operator']);
		$values["contractor"]        = MySQL::SQLValue($this->_data['contractor']);
        $values["updusr"]  = MySQL::SQLValue(session::get('userid'));              
        $values["upddat"]  = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]      = $this->id_vsat_satellite;
		
		if($this->error == true){
			if (!$result = $db->UpdateRows("vsat_satellite", $values, $wheres)) {
				
               	//$db->Kill();
				$this->log .= $db->Error()." ".$db->BuildSQLUpdate("vsat_satellite", $values, $wheres);
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{
			$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['satellite'];

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

public function delete_vsat_satellite()
    {
    	global $db;
    	$id_vsat_satellite = $this->id_vsat_satellite;
    	$where['id'] = MySQL::SQLValue($id_vsat_satellite);
    	if(!$db->DeleteRows('vsat_satellite',$where))
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


public function valid_vsat_satellite()
	{
		global $db;
		$values['etat'] = ' ETAT + 1 ';
		$wheres['id']    = MySQL::SQLValue($this->id_vsat_satellite);

		if(!$result = $db->UpdateRows('vsat_satellite', $values, $wheres))
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