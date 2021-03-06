<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel_groupe
//Created : 21-05-2020
//Model
/**
* M%modul%
* Version 1.0
*
*/

class Mobjectif_mensuel_groupe {

	private $_data;                      //data receive from form
	var $table            = 'objectif_annuel';   //Main table of module
	var $last_id          = null;        //return last ID after insert command
	var $log              = null;        //Log of all opération.
	var $error            = true;        //Error bol changed when an error is occured
    var $id_objectif_mensuel_groupe       = null;        // objectif_mensuel_groupe ID append when request
	var $token            = null;        //user for recovery function
	var $objectif_mensuel_groupe_info     = array();     //Array stock all objectif_mensuel_groupe info


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

	public function get_objectif_mensuel_groupe()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.*, CONCAT(commerciaux.nom,' ',commerciaux.prenom) AS commercial FROM
		$table, commerciaux WHERE commerciaux.id = $table.id_commercial AND  $table.id = ".$this->id_objectif_mensuel_groupe;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->objectif_mensuel_groupe_info = $db->RowArray();
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
	/**
	 * Save new row to main table
	 * @return [bol] [bol value send to controller]
	 */
	public function save_new_objectif_mensuel_groupe(){
        //$this->check_exist($column, $value, $message, $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)



        // If we have an error
		if($this->error == true){
			global $db;
		    //Add all fields for the table
		    $values["description"]       = MySQL::SQLValue($this->_data["description"]);
				$values["id_commercial"]       = MySQL::SQLValue($this->_data["id_commercial"]);
				$values["annee"]       = MySQL::SQLValue($this->_data["annee"]);
		    $values["creusr"]       = MySQL::SQLValue(session::get('userid'));

			if (!$result = $db->InsertRow($this->table, $values)) {

				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie';

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie ';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Création objectif_mensuel_groupe', 'Insert'))
                {
                  $this->log .= '</br>Un problème de log ';
                }
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
	 * Edit selected Row
	 * @return Bol [send to controller]
	 */
	public function edit_objectif_mensuel_groupe(){
        //$this->check_exist($column, $value, $message, $edit = 1);
        //$this->check_non_exist($table, $column, $value, $message)
		//Get existing data for row
		//$this->get_objectif_mensuel_groupe();

		//$this->last_id = $this->id_objectif_mensuel_groupe;
        // If we have an error
		if($this->error == true){
			global $db;
		    //ADD field row here
				//Add all fields for the table
 			 $values["description"]       = MySQL::SQLValue($this->_data["description"]);
 			 $values["id_commercial"]       = MySQL::SQLValue($this->_data["id_commercial"]);
 			 $values["annee"]       = MySQL::SQLValue($this->_data["annee"]);
 			 $values["creusr"]       = MySQL::SQLValue(session::get('userid'));
		    $values["updusr"]         = MySQL::SQLValue(session::get('userid'));
		    $values["upddat"]         = MySQL::SQLValue(date("Y-m-d H:i:s"));
		    $wheres["id"]             =  MySQL::SQLValue($this->_data["id"]);

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie';

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie ';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Modification objectif_mensuel_groupe', 'Update'))
                {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if(!$db->After_update($this->table, $this->id_objectif_mensuel_groupe, $values, $this->objectif_mensuel_groupe_info)){
                    $this->log .= '</br>Problème Espionnage';
                    $this->error = false;
                }
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
     * Valide objectif_mensuel_groupe
     * @return bol send to controller
     */
    public function valid_objectif_mensuel_groupe($etat,$id)
    {
    	//Get existing data for row
			//$this->get_objectif_mensuel_groupe();

		//$this->last_id = $this->id_objectif_mensuel_groupe;
    	global $db;

		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
    	$etat = $etat == 0 ? 1 : 0;

    	$values["etat"]        = MySQL::SQLValue($etat);
    	$values["updusr"]      = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));

    	$wheres['id']     = $id;

		// Execute the update and show error case error
    	if(!$result = $db->UpdateRows($this->table, $values, $wheres))
    	{
    		$this->log   .= '</br>Impossible de changer le statut!';
    		$this->log   .= '</br>'.$db->Error();
    		$this->error  = false;

    	}else{
    		$this->log   .= '</br>Statut changé! ';
    		$this->error  = true;
    		if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  objectif_mensuel_groupe', 'Update'))
    		{
    			$this->log .= '</br>Un problème de log ';
    			$this->error = false;
    		}
               //Esspionage
    		if(!$db->After_update($this->table, $this->id_objectif_mensuel_groupe, $values, $this->objectif_mensuel_groupe_info)){
    			$this->log .= '</br>Problème Espionnage';
    			$this->error = false;
    		}

    	}
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}


    }

	/**
	 *  [check_non_exist Check if one entrie not exist on referential table]
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
    	$sql_edit = $edit == null ? null: " AND  <> $edit";
    	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table
    		WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

    	if ($result != "0") {
    		$this->error = false;
    		$this->log .='</br>'.$message.' existe déjà';
    	}
    }



    /**
     * Delet selectd Row
     * @return bol [Send to controller]
     */
    public function delete_objectif_mensuel_groupe()
    {
    	global $db;
    	$id_objectif_mensuel_groupe = $this->id_objectif_mensuel_groupe;
    	$this->get_objectif_mensuel_groupe();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_objectif_mensuel_groupe);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows($this->table, $where))
    	{

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

    /**
     * [s Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function s($key)
    {
        if($this->objectif_mensuel_groupe_info[$key] != null)
        {
            echo $this->objectif_mensuel_groupe_info[$key];
        }else{
            echo "";
        }

    }
    /**
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key)
    {
        if($this->objectif_mensuel_groupe_info[$key] != null)
        {
            return $this->objectif_mensuel_groupe_info[$key];
        }else{
            return null;
        }

    }

}
