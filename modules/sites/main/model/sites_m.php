<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();

class Msites {

    private $_data;                      //data receive from form
    var $table = 'sites';
    var $last_id = null;        //return last ID after insert command
    var $log = null;        //Log of all opération.
    var $error = true;        //Error bol changed when an error is occured
    var $id_sites = null;        // sites ID append when request
    var $token = null;        //user for recovery function
    var $sites_info = array();     //Array stock all sites info

    public function __construct($properties = array()) {
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value) {
        return $this->_data[$property] = $value;
    }

    public function __get($property) {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null
        ;
    }

    //Get all info user fro database for edit form

    public function get_sites() {
        global $db;
       
        $table = $this->table;

        $sql = "SELECT $table.* , clients.denomination as client FROM 
		$table, clients WHERE $table.id_client=clients.id AND $table.id = " . $this->id_sites;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->sites_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array user_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Save new row to main table
     * @return [bol] [bol value send to controller]
     */
    public function save_new_sites() {
        //$this->check_exist($column, $value, $message, $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)
        // If we have an error
        
        
        
        
        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            if(!$reference = $db->Generate_reference($this->table, 'SITE', false))
        {
                $this->log .= '</br>Problème Réference';
                return false;
        }
                        
            $values["type_site"] = MySQL::SQLValue($this->_data['type_site']);
            $values["reference"] = MySQL::SQLValue($reference);
            $values["id_client"] = MySQL::SQLValue($this->_data['id_client']);
            $values["date_mes"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_mes'])));
            $values["basestation"] = MySQL::SQLValue($this->_data['basestation']);
            $values["secteur"] = MySQL::SQLValue($this->_data['secteur']);
            $values["antenne"] = MySQL::SQLValue($this->_data['antenne']);
            $values["modem"] = MySQL::SQLValue($this->_data['modem']);
            $values["sn_modem"] = MySQL::SQLValue($this->_data['sn_modem']);
            $values["bande"] = MySQL::SQLValue($this->_data['bande']);
            $values["satellite"] = MySQL::SQLValue($this->_data['satellite']);
            $values["lnb"] = MySQL::SQLValue($this->_data['lnb']);
            $values["buc"] = MySQL::SQLValue($this->_data['buc']);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            
            //var_dump( $values["date_mes"]);
            //var_dump(MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_mes']))));
            
            if (!$result = $db->InsertRow($this->table, $values)) {
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                $this->save_file('photo', 'Photo du site '.$reference, 'image');
                $this->log .= '</br>Enregistrement  réussie ' . $reference . ' - ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Création sites', 'Insert')) {
                    $this->log .= '</br>Un problème de log ';
                }
            }
        } else {

            $this->log .= '</br>Enregistrement non réussie';
        }

        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Edit selected Row
     * @return Bol [send to controller]
     */
    public function edit_sites() {
        //$this->check_exist($column, $value, $message, $edit = 1);
        //$this->check_non_exist($table, $column, $value, $message)
        //Get existing data for row
        $this->get_sites();

        $this->last_id = $this->id_sites;
        // If we have an error
        if ($this->error == true) {
            global $db;
            //ADD field row here

            $values["type_site"] = MySQL::SQLValue($this->_data['type_site']);
            $values["id_client"] = MySQL::SQLValue($this->_data['id_client']);
            $values["date_mes"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_mes'])));
            $values["basestation"] = MySQL::SQLValue($this->_data['basestation']);
            $values["secteur"] = MySQL::SQLValue($this->_data['secteur']);
            $values["antenne"] = MySQL::SQLValue($this->_data['antenne']);
            $values["modem"] = MySQL::SQLValue($this->_data['modem']);
            $values["sn_modem"] = MySQL::SQLValue($this->_data['sn_modem']);
            $values["bande"] = MySQL::SQLValue($this->_data['bande']);
            $values["satellite"] = MySQL::SQLValue($this->_data['satellite']);
            $values["lnb"] = MySQL::SQLValue($this->_data['lnb']);
            $values["buc"] = MySQL::SQLValue($this->_data['buc']);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_sites;

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                //$this->last_id = $result;
                $this->save_file('photo', 'Photo du site ', 'image');
                $this->log .= '</br>Enregistrement  réussie ' . ' ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Modification sites', 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if (!$db->After_update($this->table, $this->id_sites, $values, $this->sites_info)) {
                    $this->log .= '</br>Problème Espionnage';
                    $this->error = false;
                }
            }
        } else {

            $this->log .= '</br>Enregistrement non réussie';
        }

        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Valide sites
     * @return bol send to controller
     */
    public function valid_sites($etat) {
        //Get existing data for row
        $this->get_sites();

        $this->last_id = $this->id_sites;
        global $db;

        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        $etat = $etat == 0 ? 1 : 0;

        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        $wheres['id'] = $this->id_sites;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->log .= '</br>Statut changé! ';
            $this->error = true;
            if (!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  sites', 'Update')) {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
            //Esspionage
            if (!$db->After_update($this->table, $this->id_sites, $values, $this->sites_info)) {
                $this->log .= '</br>Problème Espionnage';
                $this->error = false;
            }
        }
        if ($this->error == false) {
            return false;
        } else {
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
    private function check_non_exist($table, $column, $value, $message) {
        global $db;
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
			WHERE $table.$column = " . MySQL::SQLValue($value));
        if ($result == "0") {
            $this->error = false;
            $this->log .= '</br>' . $message . ' n\'exist pas';
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
    private function check_exist($column, $value, $message, $edit = null) {
        global $db;
        $table = $this->table;
        $sql_edit = $edit == null ? null : " AND  <> $edit";
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
    		WHERE $table.$column = " . MySQL::SQLValue($value) . " $sql_edit ");

        if ($result != "0") {
            $this->error = false;
            $this->log .= '</br>' . $message . ' existe déjà';
        }
    }

    /**
     * Delet selectd Row
     * @return bol [Send to controller]
     */
    public function delete_sites() {
        global $db;
        $id_sites = $this->id_sites;
        $this->get_sites();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_sites);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows($this->table, $where)) {

            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Suppression réussie ';
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * [s Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function s($key) {
        if ($this->sites_info[$key] != null) {
            echo $this->sites_info[$key];
        } else {
            echo "";
        }
    }

    /**
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key) {
        if ($this->sites_info[$key] != null) {
            return $this->sites_info[$key];
        } else {
            return null;
        }
    }
    
    
     public function Generate_reference($table, $abr, $year = true) 
    {
        //SET Ranking value
    	$this->QuerySingleValue0('SET @i = 1 ;');
    	$sql_req = "SELECT MAX(IF(@i = id, @i := id + 1, @i)) AS next_ref FROM  (SELECT ( SUBSTRING_INDEX( SUBSTRING_INDEX(a.reference, '-', - 1), '/', 1 ) * 1 ) AS id  FROM $table a WHERE   SUBSTRING_INDEX(a.reference, '/', - 1) = YEAR(CURDATE())  ORDER BY reference LIMIT 0,99999999999) AS refs;";

        if(!$year){
        	$sql_req = "SELECT MAX(IF(@i = id, @i := id + 1, @i)) AS next_ref FROM
                    (SELECT  ( SUBSTRING_INDEX(a.reference, '-', - 1) * 1 ) AS id 
                    FROM  $table a ORDER BY reference LIMIT 0,99999999999) AS refs;";
        }
    	$max_id = $this->QuerySingleValue0($sql_req);
    	$max_id = $max_id == 0 ? 1 : $max_id;
        //$lent
    	if($max_id != '0')
    	{  
            
            
    		$lettre_ste = Msetting::get_set('abr_ste');
    		$lettre_ste = $lettre_ste == null ? null : $lettre_ste.'_';
        	$num_padded = sprintf("%04d", $max_id); //Format Number to 4 char with 0
        	if(!$year){
        		$reference = $lettre_ste.$abr.'-' . $num_padded;
        		return $reference;
        	}
        	$reference = $lettre_ste.$abr.'-' . $num_padded . '/' . date('Y');
        }else{
        	return false;
        }
        
        return $reference;
        
    }
    
    
    private function save_file($item, $titre, $type)
    {    
    	//Format all parameteres
    	$temp_file     = $this->_data[$item.'_id'];
            
        //If nofile uploaded return kill function
      if($temp_file == Null){
        return true;
      }

      $new_name_file = $item.'_'.$this->last_id;
      $folder        = MPATH_UPLOAD.'sites'.SLASH.$this->last_id;
      $id_line       = $this->last_id;
      $title         = $titre;
      $table         = $this->table;
      $column        = $item;
      $type          = $type;


    	//Call save_file_upload from initial class
      if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'sites', $table, $column, $type, $edit = null))
      {
        $this->error = false;
        $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
      }
    }

}
