<?php 
/**
* Class Gestion Unités de vente 1.0
*/


class Munite_vente {
	private $_data; //data receive from form
	var $table = 'ref_unites_vente'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
        var $id_unite_vente; // Region ID append when request
	var $token; //user for recovery function
	var $unite_vente_info; //Array stock all unite_vente info
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
		//Get all info unite_vente from database for edit form

	public function get_unite_vente()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_unite_vente;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->unite_vente_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array unite_vente_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
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
    		$this->log .='</br>'.$message.' existe déjà';
    	}
    }
   


	 //Save new unite_vente after all check
	public function save_new_unite_vente(){

		
       	//Before execute do the multiple check
	// check Region
	$this->Check_exist('unite_vente', $this->_data['unite_vente'], 'Unité de vente', null);

	
		global $db;
		$values["unite_vente"]     = MySQL::SQLValue($this->_data['unite_vente']);
		$values["creusr"]     = MySQL::SQLValue(session::get('userid'));
                 $values["credat"]     = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow("ref_unites_vente", $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['unite_vente'] .' - '.$this->last_id.' -';
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

    //activer ou valider une unite_vente
	public function valid_unite_vente($etat = 0)
	{
		global $db;

		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;

		$values["etat"]    = MySQL::SQLValue($etat);
		$values["updusr"] = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$wheres['id']     = $this->id_unite_vente;

		// Execute the update and show error case error
		if(!$result = $db->UpdateRows($this->table, $values, $wheres))
		{
			$this->log   .= '</br>Impossible de changer le statut!';
			$this->log   .= '</br>'.$db->Error();
			$this->error  = false;

		}else{
			$this->log   .= '</br>Statut changé! ';
			//$this->log   .= $this->table.' '.$this->id_unite_vente.' '.$etat;
			$this->error  = true;

		}
		if($this->error == false){
			return false;
		}else{
			return true;
		}


	}



	// afficher les infos d'une unite_vente
	public function Shw($key,$no_echo = "")
	{
		if($this->unite_vente_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->unite_vente_info[$key];
			}

			echo $this->unite_vente_info[$key];
		}else{
			echo "";
		}
		
	}
	//Edit unite_vente after all check
	public function edit_unite_vente(){

		

		//Get existing data for unite_vente
		$this->get_unite_vente();
		
		$this->last_id = $this->id_unite_vente;

		//Before execute do the multiple check
		// check Region
		$this->Check_exist('unite_vente', $this->_data['unite_vente'], 'Unite de vente', $this->id_unite_vente);

		
    	global $db;
		$values["unite_vente"]        = MySQL::SQLValue($this->_data['unite_vente']);
                $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
                $values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]            = $this->id_unite_vente;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("ref_unites_vente", $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				//$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['unite_vente'] .' - '.$this->last_id.' -';
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

	 public function delete_unite_vente()
    {
    	global $db;
    	$id_unite_vente = $this->id_unite_vente;
    	$this->get_unite_vente();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_unite_vente);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('ref_unites_vente',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_unites_vente',$where);
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