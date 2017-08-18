<?php 
/**
* Class Gestion Régions 1.0
*/


class Mregion {
	private $_data; //data receive from form
	var $table = 'ref_region'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_region; // Region ID append when request
	var $token; //user for recovery function
	var $region_info; //Array stock all region info
	var $app_action; //Array action for each 

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
		//Get all info user fro database for edit form

	public function get_region()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_region;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->region_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array user_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
		}
		
	}
	 //Save new user after all check
	public function save_new_region(){

		
       //Befor execute do the multiple check
		/*$this->check_exist_userid();
		$this->check_exist_email();
		$this->check_exist_service();
		$this->check_password_comp();
		$this->check_Password_complex();*/
		

		global $db;
		$values["region"]     = MySQL::SQLValue($this->_data['region']);

		$values["id_pays"]    = MySQL::SQLValue($this->_data['id_pays']);
		$values["creusr"]     = MySQL::SQLValue(session::get('userid'));
	    $values["credat"]     = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow("ref_region", $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['region'] .' - '.$this->last_id.' -';
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

    //activer ou valider une region
	public function valid_region($etat = 0)
	{
		global $db;

		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;

		$values["etat"]    = MySQL::SQLValue($etat);
		$values["updusr"] = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$wheres['id']     = $this->id_region;

		// Execute the update and show error case error
		if(!$result = $db->UpdateRows($this->table, $values, $wheres))
		{
			$this->log   .= '</br>Impossible de changer le statut!';
			$this->log   .= '</br>'.$db->Error();
			$this->error  = false;

		}else{
			$this->log   .= '</br>Statut changé! ';
			//$this->log   .= $this->table.' '.$this->id_region.' '.$etat;
			$this->error  = true;

		}
		if($this->error == false){
			return false;
		}else{
			return true;
		}


	}



	// afficher les infos d'une region
	public function Shw($key,$no_echo = "")
	{
		if($this->region_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->region_info[$key];
			}

			echo $this->region_info[$key];
		}else{
			echo "";
		}
		
	}
	//Edit region after all check
	public function edit_region(){

		//Get existing data for user
		$this->get_region();
		
		$this->last_id = $this->id_region;



    	global $db;
		$values["id_pays"]       = MySQL::SQLValue($this->_data['id_pays']);
		$values["region"]        = MySQL::SQLValue($this->_data['region']);
        $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]            = $this->id_region;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("ref_region", $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['region'] .' - '.$this->last_id.' -';
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

	 public function delete_region()
    {
    	global $db;
    	$id_region = $this->id_region;
    	$this->get_region();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_region);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('ref_region',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_region',$where);
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';
    	}
    	//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
    }




}