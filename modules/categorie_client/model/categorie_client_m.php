<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => Model


class Mcategorie_client {
	private $_data; //data receive from form
	var $table = 'categorie_client'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_categorie_client; // Ville ID append when request
	var $token; //user for recovery function
	var $categorie_client_info; //Array stock all ville info


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
		//Get all info categorie_client from database for edit form

	public function get_categorie_client()
	{
		global $db;

		$sql = "SELECT  c.*  FROM  categorie_client c WHERE  c.id = ".$this->id_categorie_client;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->categorie_client_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array categorie_client_info
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


	 //Save new categorie_client after all check
    public function save_new_categorie_client(){


        //Before execute do the multiple check
		// check categorie_client
    	$this->Check_exist('categorie_client', $this->_data['categorie_client'], 'Catégorie Client', null);

    	global $db;
    	$values["categorie_client"]  = MySQL::SQLValue($this->_data['categorie_client']);
    	$values["creusr"]      		 = MySQL::SQLValue(session::get('userid'));
    	$values["credat"]       	 = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("categorie_client", $values)) {

    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Enregistrement BD non réussie'; 

    		}else{

    			$this->last_id = $result;
    			$this->log .='</br>Enregistrement  réussie '. $this->_data['categorie_client'] .' - '.$this->last_id.' -';
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

    //activer ou desactiver un categorie_client
    public function valid_categorie_client($etat = 0)
    {
    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 		= MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_categorie_client;

        // Execute the update and show error case error
		if( !$result = $db->UpdateRows($this->table, $values , $where))
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



	// afficher les infos d'un categorie_client
    public function Shw($key,$no_echo = "")
    {
    	if($this->categorie_client_info[$key] != null)
    	{
    		if($no_echo != null)
    		{
    			return $this->categorie_client_info[$key];
    		}

    		echo $this->categorie_client_info[$key];
    	}else{
    		echo "";
    	}

    }
	//Edit categorie_client after all check
    public function edit_categorie_client(){

		//Get existing data for categorie_client
    	$this->get_categorie_client();

    	$this->last_id = $this->id_categorie_client;

		// check categorie_client
    	$this->Check_exist('categorie_client', $this->_data['categorie_client'], 'Catégorie Client', $this->id_categorie_client);

    	global $db;
    	$values["categorie_client"]  = MySQL::SQLValue($this->_data['categorie_client']);
    	$values["updusr"]            = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]            = MySQL::SQLValue(date("Y-m-d H:i:s"));
    	$wheres["id"]                = $this->id_categorie_client;


        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->UpdateRows("categorie_client", $values, $wheres)) {
				//$db->Kill();
    			$this->log .= $db->Error();
    			$this->error == false;
    			$this->log .='</br>Enregistrement BD non réussie'; 

    		}else{

				//$this->last_id = $result;
    			$this->log .='</br>Enregistrement  réussie '. $this->_data['categorie_client'] .' - '.$this->last_id.' -';
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

    public function delete_categorie_client()
    {
    	global $db;
    	$id_categorie_client = $this->id_categorie_client;
    	$this->get_categorie_client();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_categorie_client);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('categorie_client',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('categorie_client',$where);
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