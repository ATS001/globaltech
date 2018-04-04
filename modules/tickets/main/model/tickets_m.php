<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();

//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//Model
/**
 * M%modul% 
 * Version 1.0
 * 
 */
class Mtickets {

    private $_data;                      //data receive from form
    var $table = 'tickets';   //Main table of module
    var $last_id = null;        //return last ID after insert command
    var $log = null;        //Log of all opération.
    var $error = true;        //Error bol changed when an error is occured
    var $id_tickets = null;        // tickets ID append when request
    var $token = null;        //user for recovery function
    var $tickets_info = array();     //Array stock all tickets info

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

    public function get_tickets() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* ,
                DATE_FORMAT($table.date_affectation,'%d-%m-%Y') as date_affectation,
                DATE_FORMAT($table.date_previs,'%d-%m-%Y') as date_previs,
                    DATE_FORMAT($table.date_realis,'%d-%m-%Y') as date_realis,
                CONCAT(users_sys.fnom,' ',users_sys.lnom) as technicien ,
                clients.denomination as client ,
                ref_categories_produits.categorie_produit as categorie_produit ,
                ref_types_produits.type_produit as type_produit 
                FROM $table  LEFT JOIN ref_categories_produits  ON ref_categories_produits.id=$table.categorie_produit"
                . " LEFT JOIN ref_types_produits ON ref_types_produits.id=$table.type_produit"
                . " LEFT JOIN clients ON clients.id=$table.id_client"
                . " LEFT JOIN users_sys ON users_sys.id=$table.id_technicien"
                . " AND $table.id = " . $this->id_tickets;
        //var_dump($sql);
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->tickets_info = $db->RowArray();
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
    public function save_new_tickets() {

        $this->check_non_exist('clients', 'id', $this->_data['id_client'], 'Client');
        //$this->check_non_exist('users_sys', 'id', $this->_data['id_technicien'], 'Technicien');

        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            $values["id_client"] = MySQL::SQLValue($this->_data["id_client"]);
            $values["projet"] = MySQL::SQLValue($this->_data["projet"]);
            $values["message"] = MySQL::SQLValue($this->_data["message"]);
            $values["date_previs"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_previs'])));
            //$values["date_realis"] = MySQL::SQLValue($this->_data["date_realis"]);
            $values["type_produit"] = MySQL::SQLValue($this->_data["type_produit"]);
            $values["categorie_produit"] = MySQL::SQLValue($this->_data["categorie_produit"]);
            //$values["id_technicien"] = MySQL::SQLValue($this->_data["id_technicien"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            if (!$result = $db->InsertRow($this->table, $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                $this->log .= '</br>Enregistrement  réussie ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Création tickets', 'Insert')) {
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
    public function edit_tickets() {

        $this->check_non_exist('clients', 'id', $this->_data['id_client'], 'Client');
        $this->check_non_exist('users_sys', 'id', $this->_data['id_technicien'], 'Technicien');


        $this->get_tickets();

        $this->last_id = $this->id_tickets;
        // If we have an error
        if ($this->error == true) {
            global $db;
            //ADD field row here
            $values["id_client"] = MySQL::SQLValue($this->_data["id_client"]);
            $values["projet"] = MySQL::SQLValue($this->_data["projet"]);
            $values["message"] = MySQL::SQLValue($this->_data["message"]);
            $values["date_previs"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_previs'])));
            $values["type_produit"] = MySQL::SQLValue($this->_data["type_produit"]);
            $values["categorie_produit"] = MySQL::SQLValue($this->_data["categorie_produit"]);
            $values["id_technicien"] = MySQL::SQLValue($this->_data["id_technicien"]);


            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_tickets;

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                $this->log .= '</br>Enregistrement  réussie ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Modification tickets', 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if (!$db->After_update($this->table, $this->id_tickets, $values, $this->tickets_info)) {
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
     * Edit selected Row
     * @return Bol [send to controller]
     */
    public function affect_ticket() {


        $this->check_non_exist('users_sys', 'id', $this->_data['id_technicien'], 'Technicien');


        $this->get_tickets();

        $this->last_id = $this->id_tickets;
        // If we have an error
        if ($this->error == true) {
            global $db;
            //ADD field row here
            $values["id_technicien"] = MySQL::SQLValue($this->_data["id_technicien"]);
            $values["date_affectation"] = MySQL::SQLValue(date('Y-m-d', strtotime(date("Y-m-d"))));
            $wheres["id"] = $this->id_tickets;

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //$this->valid_tickets($etat=1);
                $this->log .= '</br>Enregistrement  réussie ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Affectation tickets', 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if (!$db->After_update($this->table, $this->id_tickets, $values, $this->tickets_info)) {
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
     * Valide tickets
     * @return bol send to controller
     */
    public function valid_tickets($etat) {
        //Get existing data for row
        $this->get_tickets();

        $this->last_id = $this->id_tickets;
        global $db;

        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        //$etat = $etat == 0 ? 1 : 0;

        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        $wheres['id'] = $this->id_tickets;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->log .= '</br>Statut changé! ';
            $this->error = true;
            if (!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  tickets', 'Update')) {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
            //Esspionage
            if (!$db->After_update($this->table, $this->id_tickets, $values, $this->tickets_info)) {
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
    public function delete_tickets() {
        global $db;
        $id_tickets = $this->id_tickets;
        $this->get_tickets();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_tickets);
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
        if ($this->tickets_info[$key] != null) {
            echo $this->tickets_info[$key];
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
        if ($this->tickets_info[$key] != null) {
            return $this->tickets_info[$key];
        } else {
            return null;
        }
    }

}