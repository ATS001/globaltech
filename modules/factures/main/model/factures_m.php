<?php

//SYS GLOBAL TECH
// Modul: factures => Model


class Mfacture {

    private $_data; //data receive from form
    var $table = 'factures'; //Main table of module
    var $table_complement = 'complement_facture'; // Complement facture table
    var $last_id; //return last ID after insert command
    var $log = NULL; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $id_facture; // Facture ID append when request
    var $id_complement; // Complement ID
    var $id_encaissement; //Encaissement ID
    var $token; //user for recovery function
    var $facture_info; //Array Facture all info
    var $complement_info; // Array Complement info
    var $encaissement_info; // Array encaissement info
    var $reference = null; // Reference 

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

    //Get all info categorie_contrats_frn from database for edit form
    public function get_facture() {
        global $db;
        $table = $this->table;

        $sql = "SELECT  c.* FROM $table c WHERE  c.id = " . $this->id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->facture_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array contrats_frn_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //Get all info complement from database for edit form
    public function get_complement() {
        global $db;

        $table_complement = $this->table_complement;

        $sql = "SELECT $table_complement.* FROM 
    		$table_complement WHERE  $table_complement.id = " . $this->id_complement;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->complement_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array produit_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //Get all info encaissement from database for edit form
    public function get_encaissement() {
        global $db;

        $table_encaissement = $this->table_encaissement;

        $sql = "SELECT $table_encaissement.* FROM 
    		$table_encaissement WHERE  $table_encaissement.id = " . $this->id_encaissement;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->encaissement_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array produit_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
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
        $sql_edit = $edit == null ? null : " AND id <> $edit";
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
		 		WHERE $table.$column = " . MySQL::SQLValue($value) . " $sql_edit ");

        if ($result != "0") {
            $this->error = false;
            $this->log .= '</br>' . $message . ' existe déjà';
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
    private function check_non_exist($table, $column, $value, $message) {
        global $db;
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
    		WHERE $table.$column = " . MySQL::SQLValue($value));
        if ($result == "0") {
            $this->error = false;
            $this->log .= '</br>' . $message . ' n\'exist pas';
            //exit('0#'.$this->log);
        }
    }

    //Generate contrat reference
    private function Generate_encaissement_reference() {
        if ($this->error == false) {
            return false;
        }
        global $db;
        $max_id = $db->QuerySingleValue0('SELECT IFNULL(( MAX(SUBSTR(ref, 9, LENGTH(SUBSTR(ref,9))-5))),0)+1  AS ref  FROM encaissements'
                . ' WHERE SUBSTR(ref,LENGTH(ref)-3,4)= (SELECT  YEAR(SYSDATE()))');
        $this->reference = 'GT-ENC-' . $max_id . '/' . date('Y');
    }

    //Save new complement after all check
    public function save_new_complement() {


        global $db;
        $values["designation"] = MySQL::SQLValue($this->_data['designation']);
        $values["idfacture"] = MySQL::SQLValue($this->_data['idfacture']);
        $values["montant"] = MySQL::SQLValue($this->_data['montant']);
        $values["type"] = MySQL::SQLValue($this->_data['type']);
        $values["date_complement"] = MySQL::SQLValue(date("Y-m-d"));
        $values["creusr"] = MySQL::SQLValue(session::get('userid'));
        $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
        if ($this->error == true) {

            if (!$result = ($db->InsertRow("complement_facture", $values))) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                $this->log .= '</br>Enregistrement  réussie ' . ' - ' . $this->last_id . ' -';
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

    //Save new encaissement after all check
    public function save_new_encaissement() {

        $this->Generate_encaissement_reference();
        //Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justifications du contrat.');
        }

        if ($this->error == true) {

            global $db;
            $values["ref"] = MySQL::SQLValue($this->reference);
            $values["designation"] = MySQL::SQLValue($this->_data['designation']);
            $values["idfacture"] = MySQL::SQLValue($this->_data['idfacture']);
            $values["montant"] = MySQL::SQLValue($this->_data['montant']);
            $values["date_encaissement"] = MySQL::SQLValue(date("Y-m-d"));
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            if (!$result = ($db->InsertRow("encaissements", $values))) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                $this->log .= '</br>Enregistrement  réussie ' . ' - ' . $this->last_id . ' -';
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

    //activer ou desactiver un contrats_frn
    public function valid_contrats_frn($etat = 0) {

        global $db;
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        $etat = $etat == 0 ? 1 : 0;
        //Format value for requet
        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        $where["id"] = $this->id_contrats_frn;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $where)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->log .= '</br>Statut changé! ';
            $this->error = true;
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    // afficher les infos d'un contrats_frn
    public function s($key) {
        if ($this->contrats_frn_info[$key] != null) {
            echo $this->contrats_frn_info[$key];
        } else {
            echo "";
        }
    }

    // afficher les infos d'un contrats_frn
    public function Shw($key, $no_echo = "") {
        if ($this->contrats_frn_info[$key] != null) {
            if ($no_echo != null) {
                return $this->contrats_frn_info[$key];
            }

            echo $this->contrats_frn_info[$key];
        } else {
            echo "";
        }
    }

    /**
     * [save_file For save anattached file for entrie ]
     * @param  [string] $item  [input_name of attached file we add _id]
     * @param  [string] $titre [Title stored for file on Archive DB]
     * @param  [string] $type  [Type of file (Document, PDF, Image)]
     * @return [Setting]       [Set $this->error and $this->log]
     */
    private function save_file($item, $titre, $type) {
        //Format all parameteres
        $temp_file = $this->_data[$item . '_id'];
        //If nofile uploaded return kill function
        if ($temp_file == Null) {
            return true;
        }

        $new_name_file = $item . '_' . $this->last_id;
        $folder = MPATH_UPLOAD . 'contrats_fournisseurs' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;



        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'contrats_fournisseurs', $table, $column, $type, $edit = null)) {
            $this->error = false;
            $this->log .= '</br>Enregistrement ' . $item . ' dans BD non réussie';
        }
    }

    /**
     * [check_file Check attached if required stop Insert this must be placed befor Insert commande]
     * @param  [string] $item [input_name of attached file we add _id]
     * @param  [string] $msg  [description]
     * @param  [int] $edit    [Used if is edit action must be the ID of row edited]
     * @return [Setting]      [Set $this->error and $this->log]
     */
    Private function check_file($item, $msg = null, $edit = null) {
        //Format temporary file
        $temp_file = $this->_data[$item . '_id'];
        //Check if is edit action (is numeric when called from archive DB else is retrned target upload)
        if ($edit != null && !is_numeric($temp_file)) {
            if (!file_exists($temp_file)) {
                $this->log .= '</br>Il faut choisir ' . $msg . ' pour la mise à jour ' . $edit;
                $this->error = false;
            }
            //When is not edit do check for existing file
        } else {
            if ($edit == null && $this->exige_ . $item == true && ($this->_data[$item . '_id'] == null || !file_exists($this->_data[$item . '_id']))) {
                $this->log .= '</br>Il faut choisir ' . $msg . '  ' . $edit;
                $this->error = false;
            }
        }
    }

}
