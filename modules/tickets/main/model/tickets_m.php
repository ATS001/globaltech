<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();

//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//Model

class Mtickets {

    private $_data;                      //data receive from form
    var $table = 'tickets';   //Main table of module
    var $table_action = 'action_ticket'; //Second table of module
    var $last_id = null;        //return last ID after insert command
    var $log = null;        //Log of all opération.
    var $error = true;        //Error bol changed when an error is occured
    var $id_action_ticket = null; //action ticket ID
    var $id_tickets = null;        // tickets ID append when request
    var $token = null;        //user for recovery function
    var $tickets_info = array();     //Array ticket all tickets info
    var $ticket_action_info = array();  //Array ticket action all tickets action info
    var $list_action = array(); // Arrauy of list action
    var $exige_pj;
    var $exige_photo;

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

    //Get all info ticket fro database for edit form

    public function get_tickets() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* ,
                IFNULL(DATEDIFF(DATE(NOW()),DATE(tickets.date_affectation)),0) as nbrj,
                DATE_FORMAT($table.date_affectation,'%d-%m-%Y') as date_affectation,
                DATE_FORMAT($table.date_previs,'%d-%m-%Y') as date_previs,
                DATE_FORMAT($table.date_realis,'%d-%m-%Y') as date_realis,
                CONCAT(users_sys.fnom,' ',users_sys.lnom) as technicien ,
                clients.denomination as client ,
                ref_categories_produits.categorie_produit as categorie_produit ,
                ref_types_produits.type_produit as typep , 
                produits.designation as prd,
                code_cloture.code_cloture as code_cloture,
                $table.serial_number as serial_number
                FROM $table LEFT JOIN produits ON produits.id=$table.id_produit "
                . "LEFT JOIN ref_categories_produits  ON ref_categories_produits.id=$table.categorie_produit"
                . " LEFT JOIN ref_types_produits ON ref_types_produits.id=$table.type_produit"
                . " LEFT JOIN clients ON clients.id=$table.id_client"
                . " LEFT JOIN users_sys ON users_sys.id=$table.id_technicien"
                . " LEFT JOIN code_cloture ON code_cloture.id=$table.code_cloture"
                . " WHERE $table.id = " . $this->id_tickets;

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

    public function get_ticket_action() {
        global $db;

        $table = $this->table_action;

        $sql = "SELECT $table.* , DATE_FORMAT($table.date_action,'%d-%m-%Y') as date_act FROM $table "
                . " WHERE $table.id = " . $this->id_action_ticket;


        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->ticket_action_info = $db->RowArray();

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
            $values["type_produit"] = MySQL::SQLValue($this->_data["type_produit"]);
            $values["categorie_produit"] = MySQL::SQLValue($this->_data["categorie_produit"]);
            $values["id_produit"] = MySQL::SQLValue($this->_data["id_produit"]);
              $values["serial_number"] = MySQL::SQLValue($this->_data["serial_number"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            if (!$result = $db->InsertRow($this->table, $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {
                $this->last_id = $result;
                $this->id_tickets = $db->GetLastInsertID();
                $this->init_action("ouverture", $old_technicien = NULL);
                $this->log .= '</br>Enregistrement  réussie ';
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
     * Save new row to main table
     * @return [bol] [bol value send to controller]
     */
    public function save_new_action() {

        $this->check_non_exist('tickets', 'id', $this->_data['id_ticket'], 'Ticket');

        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            $values["message"] = MySQL::SQLValue($this->_data["message"]);
            $values["date_action"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_action'])));
            $values["id_ticket"] = MySQL::SQLValue($this->_data["id_ticket"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            if (!$result = $db->InsertRow($this->table_action, $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;

                $this->save_file('pj', 'PJ' . $this->_data['id_ticket'], 'Document');

                $this->save_file('photo', 'Photo' . $this->_data['id_ticket'], 'Image');

                $this->log .= '</br>Enregistrement  réussie ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table_action, $this->last_id, 'Création action', 'Insert')) {
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
     * Edit Action
     * @return [bol] [bol value send to controller]
     */
    public function edit_tickets_action() {


        $this->get_ticket_action();
        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            $values["message"] = MySQL::SQLValue($this->_data["message"]);
            $values["date_action"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_action'])));
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = MySQL::SQLValue($this->id_action_ticket);

            if (!$result = $db->UpdateRows($this->table_action, $values, $wheres)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {


                $this->last_id = $this->id_action_ticket;

                $this->save_file('pj', 'PJ' . $this->id_action_ticket, 'Document');

                $this->save_file('photo', 'Photo' . $this->id_action_ticket, 'Image');

                //$this->last_id = $result;
                $this->log .= '</br>Enregistrement  réussie ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table_action, $this->last_id, 'Modification tickets action', 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if (!$db->After_update($this->table_action, $this->last_id, $values, $this->tickets_info)) {
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

    public function deleteactionticket() {
        global $db;
        $id_action_ticket = $this->id_action_ticket;

        //Format where clause
        $where['id'] = MySQL::SQLValue($id_action_ticket);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
            return false;
        }
        //execute Delete Query
        if (!$db->DeleteRows($this->table_action, $where)) {

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

    public function get_action_ticket() {
        global $db;
        $table_action = $this->table_action;
        $sql_req = "SELECT $table_action.*, users_sys.nom FROM $table_action, users_sys WHERE users_sys.id = $table_action.creusr AND id_ticket = " . $this->id_tickets;
        //exit($sql_req);
        if (!$db->Query($sql_req) OR ! $db->RowCount()) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '</br>Pas d\'enregistrement trouvé';
        } else {

            $this->error = true;
            $this->list_action = $db->RecordsArray();
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
        //$this->check_non_exist('users_sys', 'id', $this->_data['id_technicien'], 'Technicien');


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
             $values["serial_number"] = MySQL::SQLValue($this->_data["serial_number"]);
            $values["id_produit"] = MySQL::SQLValue($this->_data["id_produit"]);
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
     * Affectation
     * @return Bol [send to controller]
     */
    public function affect_ticket($is_reaffect) {

        $this->check_non_exist('users_sys', 'id', $this->_data['id_technicien'], 'Technicien');

        $this->get_tickets();
        $old_technicien = $this->tickets_info["technicien"];
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
                if ($is_reaffect == TRUE) {
                    $this->init_action("reaffectation", $old_technicien);
                } else {

                    $this->init_action("affectation", $old_technicien = NULL);
                }
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
     * Resolutions tickets
     * @return bol send to controller
     */
    public function resolution_ticket($etat) {
        //Get existing data for row
        $this->get_tickets();

        $this->last_id = $this->id_tickets;
        global $db;

        $values["code_cloture"] = MySQL::SQLValue($this->_data["code_cloture"]);
        $values["observation"] = MySQL::SQLValue($this->_data["observation"]);
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
     * Valide tickets
     * @return bol send to controller
     */
    public function valid_tickets($etat) {
        //Get existing data for row
        $this->get_tickets();

        $this->last_id = $this->id_tickets;
        global $db;

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
     * Clôture ticket
     * @return bol send to controller
     */
    public function cloture_ticket($etat) {
        //Get existing data for row
        $this->get_tickets();

        $this->last_id = $this->id_tickets;
        global $db;

        $values["etat"] = MySQL::SQLValue($etat);
        $values["date_realis"] = MySQL::SQLValue(date("Y-m-d"));
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
            $this->init_action("cloture", $old_technicien = NULL);
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

    /**
     * [sa Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function sa($key) {
        if ($this->ticket_action_info[$key] != null) {
            echo $this->ticket_action_info[$key];
        } else {
            echo "";
        }
    }

    /**
     * [ga Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function ga($key) {
        if ($this->ticket_action_info[$key] != null) {
            return $this->ticket_action_info[$key];
        } else {
            return null;
        }
    }

    private function save_file($item, $titre, $type) {
        //Format all parameteres
        $temp_file = $this->_data[$item . '_id'];
        //If nofile uploaded return kill function
        if ($temp_file == Null) {
            return true;
        }

        $new_name_file = $item . '_' . $this->last_id;
        $folder = MPATH_UPLOAD . 'tickets' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table_action;
        $column = $item;
        $type = $type;



        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'tickets', $table, $column, $type, $edit = null)) {
            $this->error = false;
            $this->log .= '</br>Enregistrement ' . $item . ' dans BD non réussie';
        }
    }

    /* Fonction qui ajoute une ligne dans la table action_ticket 
     * après la création,l'affectation , la réaffectation et la cloture 
     * afin de garder l'historique des technciens et spécifier 
     * tous les actions liées au ticket
     * 
     */

    public function init_action($action, $old_technicien) {
        $this->get_tickets();

        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            switch ($action) {

                case "ouverture":
                    $message = "Ouverture ticket";
                    $values["message"] = MySQL::SQLValue($message);
                    break;
                case "affectation":
                    $message = "Affectation ticket à <b>" . $this->tickets_info["technicien"] . "</b>";
                    $values["message"] = MySQL::SQLValue($message);
                    break;
                case "reaffectation":
                    $message = "Réaffectation ticket de <b>" . $old_technicien . "</b> à  <b>" . $this->tickets_info["technicien"] . "</b>";
                    $values["message"] = MySQL::SQLValue($message);
                    break;
                case "cloture":
                    $message = "Ticket clôturé";
                    $values["message"] = MySQL::SQLValue($message);
                    break;
            }

            $values["date_action"] = MySQL::SQLValue(date('Y-m-d'));
            $values["etat"] = MySQL::SQLValue(1);
            $values["id_ticket"] = MySQL::SQLValue($this->tickets_info["id"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            if (!$result = $db->InsertRow($this->table_action, $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                if (!Mlog::log_exec($this->table_action, $this->last_id, 'Création action', 'Insert')) {
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

}
