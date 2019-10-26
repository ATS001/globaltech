<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospect
//Created : 19-10-2019
//Model
/**
* M%modul% 
* Version 1.0
* 
*/

class Mprospects {
	
	private $_data;                      //data receive from form
	var $table            = 'prospects';   //Main table of module
	var $last_id          = null;        //return last ID after insert command
	var $log              = null;        //Log of all opération.
	var $error            = true;        //Error bol changed when an error is occured
    var $id_prospect       = null;        // prospect ID append when request
	var $token            = null;        //user for recovery function
	var $prospect_info     = array();     //Array stock all prospect info
	

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

	public function get_prospect()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.*, concat(c.prenom,' ',c.nom) as commercial, o.offre as lib_offre,
                DATE_FORMAT($table.date_entree,'%d-%m-%Y') AS date_e , DATE_FORMAT($table.date_cible,'%d-%m-%Y') AS date_c, DATE_FORMAT($table.credat,'%d-%m-%Y') AS date_prospect
                FROM $table, commerciaux c, ref_prospects_offre o 
                WHERE $table.id_commercial = c.id 
                and $table.offre=o.id
                and $table.id = ".$this->id_prospect;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->prospect_info = $db->RowArray();
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
	public function save_new_prospect(){
        //$this->check_exist($column, $value, $message, $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)
		
        // If we have an error
		if($this->error == true){
			global $db;

            if(!$reference = $db->Generate_reference('prospects', 'PRS'))
            {
                $this->log .= '</br>Problème Réference';
                return false;
            }

            $values["reference"]      = MySQL::SQLValue($reference);
            $values["id_commercial"]  = MySQL::SQLValue($this->_data["id_commercial"]);
            $values["raison_sociale"] = MySQL::SQLValue($this->_data["raison_sociale"]);
            $values["offre"]          = MySQL::SQLValue($this->_data["offre"]);
            $values["ca_previsionnel"]= MySQL::SQLValue($this->_data["ca_previsionnel"]);
            $values["ponderation"]    = MySQL::SQLValue($this->_data["ponderation"]);
            $values["ca_pondere"]     = MySQL::SQLValue($this->_data["ca_pondere"]);
            $values["date_entree"]    = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_entree'])));
            $values["date_cible"]     = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_cible'])));
            $values["statut_deal"]     = MySQL::SQLValue($this->_data["statut_deal"]);
            $values["commentaires"]   = MySQL::SQLValue($this->_data["commentaires"]);
		    $values["creusr"]         = MySQL::SQLValue(session::get('userid'));

			if (!$result = $db->InsertRow($this->table, $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['raison_sociale'] .' - '.$this->last_id.' -';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Création prospect', 'Insert'))
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
	public function edit_prospect(){
        //$this->check_exist($column, $value, $message, $edit = 1);
        //$this->check_non_exist($table, $column, $value, $message)
		//Get existing data for row
		$this->get_prospect();
		
		$this->last_id = $this->id_prospect;
        // If we have an error
		if($this->error == true){
			global $db;
		    //ADD field row here
            $values["id_commercial"]  = MySQL::SQLValue($this->_data["id_commercial"]);
            $values["raison_sociale"] = MySQL::SQLValue($this->_data["raison_sociale"]);
            $values["offre"]          = MySQL::SQLValue($this->_data["offre"]);
            $values["ca_previsionnel"]= MySQL::SQLValue($this->_data["ca_previsionnel"]);
            $values["ponderation"]    = MySQL::SQLValue($this->_data["ponderation"]);
            $values["ca_pondere"]     = MySQL::SQLValue($this->_data["ca_pondere"]);
            $values["date_entree"]    = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_entree'])));
            $values["date_cible"]     = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_cible'])));
            $values["statut_deal"]     = MySQL::SQLValue($this->_data["statut_deal"]);
            $values["commentaires"]   = MySQL::SQLValue($this->_data["commentaires"]);

		    $values["updusr"]         = MySQL::SQLValue(session::get('userid'));
		    $values["upddat"]         = MySQL::SQLValue(date("Y-m-d H:i:s"));
		    $wheres["id"]             = $this->id_prospect;

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement réussie '. $this->_data['raison_sociale'] .' - '.$this->last_id.' -';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Modification prospect', 'Update'))
                {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if(!$db->After_update($this->table, $this->id_prospect, $values, $this->prospect_info)){
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
     * Valide prospect
     * @return bol send to controller
     */
    public function valid_prospect($etat,$action)
    {
    	//Get existing data for row
		$this->get_prospect();
		
		$this->last_id = $this->id_prospect;
    	global $db;

		if($action == 'G'){
    	$etat = 1;
        }
        else if ($action == 'P'){
        $etat = 2;
        }

    	$values["etat"]        = MySQL::SQLValue($etat);
    	$values["updusr"]      = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));

    	$wheres['id']     = $this->id_prospect;

		// Execute the update and show error case error
    	if(!$result = $db->UpdateRows($this->table, $values, $wheres))
    	{
    		$this->log   .= '</br>Impossible de changer le statut!';
    		$this->log   .= '</br>'.$db->Error();
    		$this->error  = false;

    	}else{
    		$this->log   .= '</br>Statut changé! ';
    		$this->error  = true;
    		if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  prospect', 'Update'))
    		{
    			$this->log .= '</br>Un problème de log ';
    			$this->error = false;
    		}
               //Esspionage
    		if(!$db->After_update($this->table, $this->id_prospect, $values, $this->prospect_info)){
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


    //Archivage abonnement
    public function archiveprospects() {
        global $db;
        $id_prospect = $this->id_prospect;
        $table = $this->table;
        $this->get_prospect();
        //Format where clause
        $id_prospect = MySQL::SQLValue($id_prospect);
        $sql_req = "UPDATE $table SET etat = 100 WHERE id = $id_prospect";

        //check if id on where clause isset
        if ($id_prospect == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
            return false;
        }
        //execute Delete Query
        if (!$db->Query($sql_req)) {

            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '</br>Archivage non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Archivage réussie ';
            //log
            if (!Mlog::log_exec($table, $this->id_prospect, 'Archivage abonnement ' . $this->id_prospect, 'Update')) {
                $this->log .= '</br>Un problème de log ';
            }
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Delet selectd Row
     * @return bol [Send to controller]
     */
    public function delete_prospect()
    {
    	global $db;
    	$id_prospect = $this->id_prospect;
    	$this->get_prospect();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_prospect);
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
        if($this->prospect_info[$key] != null)
        {
            echo $this->prospect_info[$key];
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
        if($this->prospect_info[$key] != null)
        {
            return $this->prospect_info[$key];
        }else{
            return null;
        }

    }

}