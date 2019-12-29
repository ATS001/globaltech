<?php

class Mcommission {

    private $_data;                      //data receive from form
    var $table = 'compte_commerciale';   //Main table of module
    var $last_id = null;        //return last ID after insert command
    var $log = null;        //Log of all opération.
    var $error = true;        //Error bol changed when an error is occured
    var $id_commission = null;        // commission ID append when request
    var $id_commerciale = null; // commerciale ID append when request
    var $id_paiement = null; // paiement ID append when request
    var $paiements_info = null;
    var $token = null;        //user for recovery function
    var $commission_info = array();     //Array stock all commerciale info
    var $exige_pj;
    var $exige_photo;
    var $details_commission_info = array();
    public static $reste;
    var $objectif_mensuel_list = array();

    public function __construct($properties = array()) {
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value) {
        return $this->_data[$property] = $value;
    }

    public function __get($property) {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null;
    }

    //Get all info user fro database for edit form
    public function get_commission() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = " . $this->id_commission;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->commission_info = $db->RowArray();
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
    public function save_new_commission() {
        //$this->check_exist($column, $value, $message, $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)
        // If we have an error
        if ($this->error == true) {
            global $db;
            //Add all fields for the table
            $values["objet"] = MySQL::SQLValue($this->_data["objet"]);
            $values["credit"] = MySQL::SQLValue($this->_data["credit"]);
            $values["type"] = MySQL::SQLValue($this->_data["type"]);
            $values["id_commerciale"] = MySQL::SQLValue($this->_data["id_commerciale"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));

            if (!$result = $db->InsertRow($this->table, $values)) {
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {
                $this->last_id = $result;

                //If Attached required Save file to Archive
                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['id_commerciale'] . ' ' . $this->last_id;
                if (!Mlog::log_exec($this->table, $this->last_id, 'Ajout Commission', 'Insert')) {
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
    public function edit_commission() {

        //Get existing data for row
        $this->get_commission();

        $this->last_id = $this->id_commerciale;
        // If we have an error
        if ($this->error == true) {
            global $db;
            //ADD field row here
            $values["objet"] = MySQL::SQLValue($this->_data["objet"]);
            $values["credit"] = MySQL::SQLValue($this->_data["credit"]);
            //$values["methode_payement"] = MySQL::SQLValue($this->_data["methode_payement"]);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_commission;

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //If Attached required Save file to Archive

                $this->log .= '</br>Modification  réussie ' . $this->last_id . ' -';
                if (!Mlog::log_exec($this->table, $this->last_id, 'Modification commission', 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if (!$db->After_update($this->table, $this->id_commission, $values, $this->commission_info)) {
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
    public function valid_commerciale($etat) {
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
    public function delete_commerciale() {
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
    public function s($key) {
        if ($this->commission_info[$key] != null) {
            echo $this->commission_info[$key];
        } else {
            echo "";
        }
    }

    /**
     * [s Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function m($key) {
        if ($this->paiements_info[$key] != null) {
            echo $this->paiements_info[$key];
        } else {
            echo "";
        }
    }

    public function f($key) {
        if ($this->details_commission_info[$key] != null) {
            echo $this->details_commission_info[$key];
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
        if ($this->commission_info[$key] != null) {
            return $this->commission_info[$key];
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
        $folder = MPATH_UPLOAD . 'commerciale' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;


        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'commerciale', $table, $column, $type, $edit = null)) {
            $this->error = false;
            $this->log .= '</br>Enregistrement ' . $item . ' dans BD non réussie';
        }
    }

    /**
     * [valid_compte Validate  commission]
     * @param  integer $etat [Etat de AEMPLOI]
     * @return [bool]        [Retrn bol value read by controller]
     */
    public function valid_commission($etat = 0, $validator = null) {
        global $db;
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 DÃ©sactivation)
        //$etat = $etat + 1 ;
        //Format value for requet
        //var_dump($etat);
        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $where["id"] = $this->id_commission;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $where)) {
            $this->log .= '</br>Impossible de changer le statut! ======== ';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->log .= '</br>Statut changÃ©! ';
            $this->error = true;
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    static public function reste_commission($id_commission) {
        global $db;
        $table = 'compte_commerciale';

        /* $sql = "SELECT  compte_commerciale.credit -(SELECT IFNULL(MAX(compte_commerciale.debit),0) FROM compte_commerciale,compte_commerciale crdt
          WHERE compte_commerciale.id_credit=crdt.id) AS reste
          FROM compte_commerciale
          WHERE compte_commerciale.id=".$id_commission;
         */
        $sql = "select compte_commerciale.`credit`- IF( (SELECT COUNT(*) FROM compte_commerciale cpt WHERE cpt.`id_commerciale`=compte_commerciale.`id_commerciale`
    AND cpt.`id_credit`=compte_commerciale.id AND cpt.`id_credit` IS NOT NULL)= 0, 0,  
  ( SELECT SUM(cc.debit) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
    AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL)) FROM compte_commerciale WHERE compte_commerciale.id=" . $id_commission;


        if (!$db->Query($sql)) {

            $reste = $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $reste = array();
            } else {
                $reste = $db->RowValue();
            }
        }


        return $reste;
    }

    public function payer_commission() {

        //Get existing data for aemploi
        $this->get_commission();

        $this->last_id = $this->id_commission;

        //Befor execute do the multiple check
        $table = 'compte_commerciale';
        $this->table = $table;

        //Check $this->error (true / false)
        if ($this->error == true) {
            //Format values for Insert query
            global $db;


            $values["id_commerciale"] = MySQL::SQLValue($this->commission_info["id_commerciale"]);
            $values["debit"] = MySQL::SQLValue($this->_data["debit"]);
            $values["objet"] = MySQL::SQLValue($this->_data["objet"]);
            $values["date_debit"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_debit'])));
            $values["methode_payement"] = MySQL::SQLValue($this->_data["methode_payement"]);
            $values["id_credit"] = MySQL::SQLValue($this->_data["id"]);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));

            //Check if Update Query been executed (False / True)
            if (!$result = $db->InsertRow($this->table, $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {
                $this->last_id = $result;
                $this->save_file('pj', 'Justificatif.' . $values["id_credit"], 'Document');
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    //$this->maj_reste($this->_data['id'], $this->_data["debit"]);
                    $this->log = '</br>Enregistrement réussie: <b>' . 'ID: ' . $this->last_id;
                    //Check $this->error = false return Red message and Bol false
                } else {
                    $this->log .= '</br>Enregistrement non réussie: <b>';
                    $this->log .= '</br>Un problème d\'Enregistrement ';
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

    public function maj_reste($id, $debit) {
        global $db;

        $this->get_commission();
        $reste = $this->commission_info['reste'] - $debit;
        $values["reste"] = MySQL::SQLValue($reste);
        $where["id"] = $this->id_commission;

        if (!$result = $db->UpdateRows($this->table, $values, $where)) {
            $this->log .= '</br>Impossible de changer le statut! ';
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

    public function get_paiement() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.*,CONCAT(commerciaux.nom,' ',commerciaux.prenom) as commerciale,DATE_FORMAT(date_debit,'%d-%m-%Y') as date_debit FROM 
		$table,commerciaux WHERE  compte_commerciale.id_commerciale=commerciaux.id AND $table.id = " . $this->id_paiement;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->paiements_info = null;
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->paiements_info = $db->RowArray();
            }
        }
        //return Array user_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //Get all info user fro database for edit form
    public function get_commission_info() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.*,CONCAT(commerciaux.nom,' ',commerciaux.prenom) as commerciale, 
	            (SELECT  IFNULL (SUM(cc.debit),0) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
               AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL) AS paye,
          compte_commerciale.`credit`- IF( (SELECT COUNT(*) FROM compte_commerciale cpt WHERE cpt.`id_commerciale`=compte_commerciale.`id_commerciale`
  AND cpt.`id_credit`=compte_commerciale.id AND cpt.`id_credit` IS NOT NULL)= 0, 0,  
  ( SELECT SUM(cc.debit) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
  AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL)) AS reste
  FROM compte_commerciale,commerciaux
  WHERE
  compte_commerciale.`id_credit` IS NULL AND commerciaux.id=compte_commerciale.id_commerciale AND $table.id = " . $this->id_commission;


        if (!$db->Query($sql)) {
            //var_dump($db);
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->details_commission_info = $db->RowArray();
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

    public function get_list_commission_by_commerciale() {
        global $db;

        $table = $this->table;

        $sql = "SELECT compte_commerciale.*,
	            (SELECT  IFNULL (SUM(cc.debit),0) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
               AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL) AS payé,
          compte_commerciale.`credit`- IF( (SELECT COUNT(*) FROM compte_commerciale cpt WHERE cpt.`id_commerciale`=compte_commerciale.`id_commerciale`
  AND cpt.`id_credit`=compte_commerciale.id AND cpt.`id_credit` IS NOT NULL)= 0, 0,  
  ( SELECT SUM(cc.debit) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
  AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL)) AS reste
  FROM compte_commerciale,commerciaux
  WHERE
  compte_commerciale.`id_credit` IS NULL AND commerciaux.id=compte_commerciale.id_commerciale AND compte_commerciale.id_commerciale = " . $this->id_commerciale;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->commission_info = null;
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {

                $this->commission_info = $db->RecordsArray();
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

    public function get_list_paiement_by_commerciale() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.*,DATE_FORMAT(date_debit,'%d-%m-%Y') as date_debit FROM 
		$table WHERE compte_commerciale.`id_credit` IS NOT NULL AND $table.id_commerciale = " . $this->id_commerciale;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                //$this->paiements_info = null;
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {

                $this->paiements_info = $db->RecordsArray();
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

    public function get_list_paiement_by_commission() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.*,DATE_FORMAT(date_debit,'%d-%m-%Y') as date_debit FROM 
		$table WHERE compte_commerciale.`id_credit` IS NOT NULL AND $table.id_credit = " . $this->id_commission;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                //$this->paiements_info = null;
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {

                $this->paiements_info = $db->RecordsArray();
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

    public function add_decharge() {

        //Get existing data for row
        $this->get_commission();

        $this->last_id = $this->id_commerciale;
        // If we have an error
        if ($this->error == true) {
            global $db;
            //ADD field row here
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_commission;

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $wheres["id"];
                //If Attached required Save file to Archive
                $this->save_file('decharge', 'Décharge.' . $wheres["id"], 'Document');

                if ($this->error == true) {

                    $this->maj_decharge();
                    $this->log = '</br>Enregistrement réussie: <b>' . 'ID: ' . $this->last_id;
                    //Check $this->error = false return Red message and Bol false
                } else {
                    $this->log .= '</br>Enregistrement non réussie: <b>';
                    $this->log .= '</br>Un problème d\'Enregistrement ';
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

    //Incrémente l'était du paiement après l'ajout de décharge
    public function maj_decharge() {
        global $db;

        $this->get_commission();
        $etat = 1;
        $values["etat"] = MySQL::SQLValue($etat);
        $where["id"] = $this->id_commission;

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

    public function Get_detail_paiement_show() {
        global $db;

        $table = $this->table;

        $req_sql = "SELECT $table.*,CONCAT(commerciaux.nom,' ',commerciaux.prenom) as commerciale,
                                    DATE_FORMAT(date_debit,'%d-%m-%Y') as date_debit,
                                    CONCAT(users_sys.fnom,' ',users_sys.lnom) as user
                                    FROM 
                                    commerciaux , compte_commerciale
                                    LEFT JOIN users_sys 
                                    ON (compte_commerciale.creusr = users_sys.id) 
                                    WHERE  compte_commerciale.id_commerciale=commerciaux.id AND $table.id = " . $this->id_paiement;

        if (!$db->Query($req_sql)) {

            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->paiements_info = $db->RowArray();
                $this->error = true;
            }
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function calculerCommissionCommerciale() {
       
        $mois = date('m')-1;
        $annee = date('Y');
        if($mois == 1){
            $annee = $annee - 1;
        }
        
        $this->getListCommerciaux($annee,$mois);
        var_dump($this->objectif_mensuel_list);
        
        foreach ($this->objectif_mensuel_list as $value) {
            
            $taux_resalisation = (intval($value["realise"]) / intval($value["objectif"])) * 100;
            $eligible = FALSE;
            
            if ($taux_resalisation >= intval($value["seuil"])) {
                $eligible = TRUE;
                $commission = intval($value["realise"]) * (150 / 100);
                $values["objet"] = MySQL::SQLValue("Commission mensuel");
                $values["credit"] = MySQL::SQLValue($commission);
                $values["type"] = MySQL::SQLValue("Automatique");
                $values["id_commerciale"] = MySQL::SQLValue($value["id_commercial"]);
                $values["creusr"] = MySQL::SQLValue(session::get('userid'));

                if (!$result = $db->InsertRow($this->table, $values)) {
                    $this->log .= $db->Error();
                    $this->log .= '</br>Enregistrement BD non réussie';
                } else {
                    
                    $this->last_id = $result;
                    //If Attached required Save file to Archive
                    $this->log .= '</br>Enregistrement  réussie ' . $this->_data['id_commerciale'] . ' ' . $this->last_id;
                    if (!Mlog::log_exec($this->table, $this->last_id, 'Ajout Commission', 'Insert')) {
                        $this->log .= '</br>Un problème de log ';
                    }
                }
            }
            $this->update_etat_objectif_mensuel($value["id"], $eligible, $commission);
        }
    }

    //List des objectifs du mois à traiter (N-1)
        function getListCommerciaux($annee,$mois) {
            
            global $db;

            //$sql = "SELECT $table.*, CONCAT(commerciaux.nom,' ',commerciaux.prenom) AS commercial FROM $table, commerciaux WHERE commerciaux.id = $table.id_commercial";
            $sql= "SELECT * FROM `objectif_mensuels` WHERE annee = .$annee. AND mois = .$mois .";
            if (!$db->Query($sql)) {
                $this->error = false;
                $this->log .= $db->Error();
            } else {
                if ($db->RowCount() == 0) {
                    $this->error = false;
                    $this->log .= 'Aucun enregistrement trouvé ';
                } else {
                    $this->objectif_mensuel_list = $db->RecordsArray();
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

        public function update_etat_objectif_mensuel($id_objectif_mensuel,$eligible,$montant_benif)
        {
        global $db; 
        $table = 'objectif_mensuels';
        $etat_ok  =  Msetting::get_set('etat_objectif_mensuel', 'objectif_atteint');
        $etat_notOk =  Msetting::get_set('etat_objectif_mensuel', 'objectif_non');
        
        if($eligible){
            $new_etat = $etat_ok;
        }else{
            $new_etat = $etat_notOk;
        }     
        $values["montant_benif"] = MySQL::SQLValue($montant_benif);
        $values["etat"]          = MySQL::SQLValue($new_etat);
        $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres['id']            = MySQL::SQLValue($id_objectif_mensuel) ;

        // Execute the update and show error case error
        if(!$result = $db->UpdateRows($table, $values, $wheres))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>'.$db->Error();
            $this->error  = false;

        }else{
            $this->log   .= '</br>Statut Objectif changé, Réalisé : '.$percentage_realise.' %';
            $this->error  = true;
            if(!Mlog::log_exec($this->table, $this->last_id, 'Calcul de bénifice Objectif', 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
               //Esspionage
            if(!$db->After_update($this->table, $this->id_objectif_mensuel, $values, $this->objectif_mensuel_info)){
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
    }
    