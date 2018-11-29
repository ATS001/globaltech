<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Model

/**
 * M%modul%
 * Version 1.0
 *
 */
class Mcommerciale
{

    private $_data;                      //data receive from form
    var $table = 'commerciaux';   //Main table of module
    var $last_id = null;        //return last ID after insert command
    var $log = null;        //Log of all opération.
    var $error = true;        //Error bol changed when an error is occured
    var $id_commerciale = null;        // commerciale ID append when request
    var $token = null;        //user for recovery function
    var $commerciale_info = array();     //Array stock all commerciale info
    var $id_service; // Id Service du commerciale interne
    var $exige_pj;
    var $exige_photo;

    public function __construct($properties = array())
    {
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value)
    {
        return $this->_data[$property] = $value;
    }

    public function __get($property)
    {
        return array_key_exists($property, $this->_data)
            ? $this->_data[$property]
            : null;
    }

    //Get all info user fro database for edit form

    public function get_commerciale()
    {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = " . $this->id_commerciale;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->commerciale_info = $db->RowArray();
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
    public function save_new_commerciale()
    {
        
        /*
        if($this->_data["is_glbt"] == "Oui"){
        if($this->_data["tel"] != NULL || $this->_data["email"] != NULL)
            $this->get_id_service ();
        }
         * 
         */


        // If we have an error
        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            $values["nom"] = MySQL::SQLValue($this->_data["nom"]);
            $values["prenom"] = MySQL::SQLValue($this->_data["prenom"]);
            $values["is_glbt"] = MySQL::SQLValue($this->_data["is_glbt"]);
            $values["cin"] = MySQL::SQLValue($this->_data["cin"]);
            $values["rib"] = MySQL::SQLValue($this->_data["rib"]);
            $values["tel"] = MySQL::SQLValue($this->_data["tel"]);
            $values["email"] = MySQL::SQLValue($this->_data["email"]);
            $values["id_service"] = MySQL::SQLValue($this->_data["service"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));

            if (!$result = $db->InsertRow($this->table, $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';

            } else {

                $this->last_id = $result;
                //If Attached required Save file to Archive

                $this->save_file('pj', 'Justifications' . $this->_data['cin'], 'Document');

                $this->save_file('photo', 'Photo du client' . $this->_data['cin'], 'Image');

                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['nom'] . ' ' . $this->_data['nom'] . ' - ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Création commerciale', 'Insert')) {
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
    public function edit_commerciale()
    {
        //$this->check_exist($column, $value, $message, $edit = 1);
        //$this->check_non_exist($table, $column, $value, $message)
        //Get existing data for row
        $this->get_commerciale();

        $this->last_id = $this->id_commerciale;
        // If we have an error
        if ($this->error == true) {
            global $db;
            //ADD field row here
            $values["nom"] = MySQL::SQLValue($this->_data["nom"]);
            $values["prenom"] = MySQL::SQLValue($this->_data["prenom"]);
            $values["is_glbt"] = MySQL::SQLValue($this->_data["is_glbt"]);
            $values["cin"] = MySQL::SQLValue($this->_data["cin"]);
            $values["rib"] = MySQL::SQLValue($this->_data["rib"]);
            $values["tel"] = MySQL::SQLValue($this->_data["tel"]);
            $values["email"] = MySQL::SQLValue($this->_data["email"]);
            $values["id_service"] = MySQL::SQLValue($this->_data["service"]);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_commerciale;

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';

            } else {

                $this->last_id = $result;
                //If Attached required Save file to Archive

                $this->save_file('pj', 'Justifications' . $this->_data['cin'], 'Document');

                $this->save_file('photo', 'Photo du client' . $this->_data['cin'], 'Image');

                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['nom'] . ' ' . $this->_data['nom'] . ' - ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Modification commerciale', 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if (!$db->After_update($this->table, $this->id_commerciale, $values, $this->commerciale_info)) {
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
     * Valide commerciale
     * @return bol send to controller
     */
    public function valid_commerciale($etat)
    {
        //Get existing data for row
        $this->get_commerciale();

        $this->last_id = $this->id_commerciale;
        global $db;

        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        $etat = $etat == 0 ? 1 : 0;

        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        $wheres['id'] = $this->id_commerciale;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;

        } else {
            $this->log .= '</br>Statut changé! ';
            $this->error = true;
            if (!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  commerciale', 'Update')) {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
            //Esspionage
            if (!$db->After_update($this->table, $this->id_commerciale, $values, $this->commerciale_info)) {
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
    private function check_non_exist($table, $column, $value, $message)
    {
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
    private function check_exist($column, $value, $message, $edit = null)
    {
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
    public function delete_commerciale()
    {
        global $db;
        $id_commerciale = $this->id_commerciale;
        $this->get_commerciale();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_commerciale);
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
    public function s($key)
    {
        if ($this->commerciale_info[$key] != null) {
            echo $this->commerciale_info[$key];
        } else {
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
        if ($this->commerciale_info[$key] != null) {
            return $this->commerciale_info[$key];
        } else {
            return null;
        }

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
        $folder        = MPATH_UPLOAD.'commerciale'.SLASH.$this->last_id;
        $id_line       = $this->last_id;
        $title         = $titre;
        $table         = $this->table;
        $column        = $item;
        $type          = $type;



        //Call save_file_upload from initial class
        if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'commerciale', $table, $column, $type, $edit = null))
        {
            $this->error = false;
            $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
        }
    }

public function get_id_service()
    {
   
        global $db;

        $table = "users_sys";
        $tel = $this->_data['tel'];
        $email=$this->_data['email'];
        
        $sql = "SELECT $table.service FROM 
		$table WHERE  $table.tel =   '$tel'  OR $table.mail = '$email' ";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->id_service = $db->RowArray();
                $this->error = true;
            }


        }
        var_dump($db);
        //return Array user_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }

    }
}