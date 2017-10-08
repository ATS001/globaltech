<?php

//SYS GLOBAL TECH
// Modul: factures => Model


class Mfacture {

    private $_data; //data receive from form
    var $table = 'factures'; //Main table of module
    var $table_complement = 'complement_facture'; // Complement facture table
    var $table_encaissement = 'encaissements'; // Encaissement facture table
    var $table_details = 'd_devis'; //Tables détails devis
    var $last_id; //return last ID after insert command
    var $log = NULL; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $id_devis; // Devus ID
    var $id_facture; // Facture ID append when request
    var $id_complement; // Complement ID
    var $id_encaissement; //Encaissement ID
    var $token; //user for recovery function
    var $facture_info; //Array Facture all info
    var $complement_info; // Array Complement info
    var $encaissement_info; // Array encaissement info
    var $devis_info; // Array Devis info
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
        $max_id = $db->QuerySingleValue0('SELECT IFNULL(( MAX(SUBSTR(ref, 8, LENGTH(SUBSTR(ref,8))-5))),0)+1  AS reference  FROM encaissements WHERE SUBSTR(ref,LENGTH(ref)-3,4)= (SELECT  YEAR(SYSDATE()))');
        $this->reference = 'GT-ENC-' . $max_id . '/' . date('Y');
    }

    //Save new complement after all check
    public function save_new_complement() {

        if ($this->_data['type'] == "Réduction")
            $this->_data['montant'] = -$this->_data['montant'];

        global $db;
        $values["designation"] = MySQL::SQLValue($this->_data['designation']);
        $values["idfacture"] = MySQL::SQLValue($this->_data['idfacture']);
        $values["type"] = MySQL::SQLValue($this->_data['type']);
        $values["montant"] = MySQL::SQLValue($this->_data['montant']);
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
            $values["mode_payement"] = MySQL::SQLValue($this->_data['mode_payement']);
            $values["ref_payement"] = MySQL::SQLValue($this->_data['ref_payement']);
            $values["montant"] = MySQL::SQLValue($this->_data['montant']);
            $values["date_encaissement"] = MySQL::SQLValue(date("Y-m-d"));
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));


            //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow('encaissements', $values)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //If Attached required Save file to Archive

                $this->save_file('pj', 'Justifications de l\'encaissement' . $this->reference, 'Document');

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Enregistrement réussie: <b>' . $this->reference . ' ID: ' . $this->last_id;
                    $this->maj_reste($this->_data['idfacture'], $this->_data['montant']);
                    //Check $this->error = false return Red message and Bol false	
                } else {
                    $this->log .= '</br>Enregistrement réussie: <b>' . $this->reference;

                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }
            }
            //Else Error false	
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

    public function maj_reste($id_facture, $montant) {

        global $db;
        $req_sql = "UPDATE factures SET reste = reste - $montant , total_paye = total_paye + $montant WHERE id = '$id_facture'";
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème de mise à jour du reste ';
        }
    }

    public function update_reste_after_delete($id_facture, $mt) {
        $this->id_facture = $id_facture;

        global $db;
        $req_sql = "UPDATE factures SET reste = reste + $mt  , total_paye = total_paye - $mt WHERE id = '$id_facture'";
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème de mise à jour du reste ';
        }
    }

    public function update_reste($id_facture, $montant_init, $montant_modif) {
        $this->id_facture = $id_facture;
        $this->get_facture();
        $reste = ($this->facture_info['reste'] + $montant_init) - $montant_modif;
        $total_paye = ($this->facture_info['total_paye'] - $montant_init) + $montant_modif;

        global $db;
        $req_sql = "UPDATE factures SET reste = $reste , total_paye = $total_paye WHERE id = '$id_facture'";
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème de mise à jour du reste ';
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
        if ($this->encaissement_info[$key] != null) {
            if ($no_echo != null) {
                return $this->encaissement_info[$key];
            }

            echo $this->encaissement_info[$key];
        } else {
            echo "";
        }
    }

    // afficher les infos d'un contrats_frn
    public function Shw2($key, $no_echo = "") {
        if ($this->complement_info[$key] != null) {
            if ($no_echo != null) {
                return $this->complement_info[$key];
            }

            echo $this->complement_info[$key];
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
        $folder = MPATH_UPLOAD . 'encaissements' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table_encaissement;
        $column = $item;
        $type = $type;



        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'encaissements', $table, $column, $type, $edit = null)) {
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

    public function delete_complement() {
        global $db;
        $id_complement = $this->id_complement;
        $this->get_complement();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_complement);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows('complement_facture', $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('complement_facture', $where);
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

    public function delete_encaissement() {
        global $db;
        $id_encaissement = $this->id_encaissement;
        $this->get_encaissement();
        $mt = $this->encaissement_info['montant'];
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_encaissement);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows('encaissements', $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('encaissements', $where);
            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Suppression réussie ';
            $this->update_reste_after_delete($this->encaissement_info['idfacture'], $mt);
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function edit_complement() {

        //Get existing data for complement
        $this->get_complement();
        $this->last_id = $this->id_complement;

        if ($this->_data['type'] == "Réduction")
            $this->_data['montant'] = -$this->_data['montant'];


        global $db;
        $values["designation"] = MySQL::SQLValue($this->_data['designation']);
        $values["idfacture"] = MySQL::SQLValue($this->_data['idfacture']);
        $values["montant"] = MySQL::SQLValue($this->_data['montant']);
        $values["type"] = MySQL::SQLValue($this->_data['type']);
        $values["date_complement"] = MySQL::SQLValue(date("Y-m-d"));
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id"] = $this->id_complement;


        // If we have an error
        if ($this->error == true) {

            if (!$result = $db->UpdateRows("complement_facture", $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                //$this->last_id = $result;
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

    public function edit_encaissement() {

        //Get existing data for complement
        $this->get_encaissement();
        $mt_init = $this->encaissement_info['montant'];
        $this->last_id = $this->id_encaissement;

        //Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justifications du client.', $this->_data['pj_id']);
        }

        global $db;
        $values["designation"] = MySQL::SQLValue($this->_data['designation']);
        $values["idfacture"] = MySQL::SQLValue($this->_data['idfacture']);
        $values["mode_payement"] = MySQL::SQLValue($this->_data['mode_payement']);
        $values["ref_payement"] = MySQL::SQLValue($this->_data['ref_payement']);
        $values["montant"] = MySQL::SQLValue($this->_data['montant']);
        $values["date_encaissement"] = MySQL::SQLValue(date("Y-m-d"));
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id"] = $this->id_encaissement;


        // If we have an error
        if ($this->error == true) {

            if (!$result = $db->UpdateRows("encaissements", $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $this->id_encaissement;
                //If Attached required Save file to Archive
                $this->save_file('pj', 'Justifications encaissement' . $this->_data['id'], 'Document');


                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['id'] . ' ID: ' . $this->last_id;
                    $this->update_reste($this->_data['idfacture'], $mt_init, $this->_data['montant']);

                    //Check $this->error = false return Red message and Bol false	
                } else {
                    $this->log .= '</br>Modification non réussie: <b>' . $this->_data['id'];
                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }
            }
            //Else Error false	
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

    //validation ayoub
    /*     public function valid_facture($etat)
      {
      global $db;
      $table = $this->table;
      $id_facture = $this->id_facture;
      $this->get_id_devis();

      $req_sql = " UPDATE $table SET etat = $etat+1  WHERE id = $id_facture";

      if (!$db->Query($req_sql)) {
      $this->error = false;
      $this->log .= "Erreur Validation";
      return false;
      }

      if(!$this->Get_detail_facture_pdf())
      {
      $this->log .= $this->log;
      return false;

      }else{
      $this->log .= "Validation réussie";
      return true;
      }
      } */

    public function valid_facture() {
        global $db;
        $table = $this->table;
        $values['etat'] = ' ETAT + 1 ';
        $wheres['id'] = MySQL::SQLValue($this->id_facture);

        if (!$result = $db->UpdateRows($table, $values, $wheres)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= 'Validation non réussie DB';
        } else {
            $this->log .= 'Validation réussie';
            $this->error = true;
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function reject_facture() {
        global $db;
        $values['etat'] = ' ETAT - 1 ';
        $table = $this->table;
        $wheres['id'] = MySQL::SQLValue($this->id_facture);

        if (!$result = $db->UpdateRows($table, $values, $wheres)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= 'Rejet non réussie DB';
        } else {
            $this->log .= 'Rejet réussie';
            $this->error = true;
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    // afficher les infos d'un contrat
    public function printattribute($attibute) {
        if ($this->encaissement_info[$attibute] != null) {
            echo $this->encaissement_info[$attibute];
        } else {
            echo "";
        }
    }

    // afficher les infos d'un contrat
    public function printattribute_fact($attibute) {
        if ($this->facture_info[$attibute] != null) {
            echo $this->facture_info[$attibute];
        } else {
            echo "";
        }
    }

    //Get all info encaissement from database for edit form
    public function get_encaissement_info() {
        global $db;

        $table_encaissement = $this->table_encaissement;

        $sql = "SELECT $table_encaissement.* , factures.ref as facture FROM 
    		$table_encaissement,factures WHERE $table_encaissement.idfacture=factures.id AND $table_encaissement.id = " . $this->id_encaissement;

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

    //Get all info Facture from database for edit form
    public function get_facture_info() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* FROM 
    		$table WHERE  $table.id = " . $this->id_facture;

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
        //return Array produit_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Get_detail_facture_pdf() {
        global $db;
        $id_devis = $this->id_devis['id'];
        $id_facture = $this->id_facture;
        $table = $this->table_details;
        $this->Get_detail_facture_show();
        $devis_info = $this->devis_info;
        $this->get_facture();
        $info_facture = $this->facture_info;

        $colms = null;
        $colms .= " $table.id item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.prix_unitaire,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.remise_valeur,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_ttc,0),',', ' ') ";

        $req_sql = " SELECT $colms FROM $table WHERE id_devis = $id_devis ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
        }


        $headers = array(
            'Item' => '5[#]center',
            'Réf' => '10[#]center',
            'Description' => '45[#]',
            'Qte' => '5[#]center',
            'P.U' => '10[#]alignRight',
            'Re' => '5[#]center',
            'Total HT' => '15[#]alignRight',
        );

        $tableau_head = MySQL::make_table_head($headers);
        $tableau_body = $db->GetMTable_pdf($headers);

        $file_export = MPATH_TEMP . 'Facture' . '_' . date('d_m_Y_H_i_s') . '.pdf';

        //Load template 
        include_once MPATH_THEMES . 'pdf_template/facture_pdf.php';
        $new_file_target = MPATH_UPLOAD . 'Facture' . date('m_Y');

        if (file_exists($file_export)) {
            if (!Minit::save_file_upload($file_export, 'Facture_' . $id_facture, $new_file_target, $id_facture, 'Facture ' . $id_facture, 'factures', 'factures', 'facture_pdf', 'document', $edit = null)) {
                $this->error = false;
                $this->log .= "Erreur Archivage Devis";
            }
        } else {
            $this->error = false;
            $this->log .= "Erreur création template Facture";
        }

        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Get_detail_facture_show() {
        $id_devis = $this->id_devis['id'];
        global $db;
        $req_sql = "SELECT
        devis.reference
        , devis.date_devis
        , devis.valeur_remise
        ,  REPLACE(FORMAT(devis.totalht,0),',',' ') as totalht
        ,  REPLACE(FORMAT(devis.totaltva,0),',',' ') as totaltva
        ,  REPLACE(FORMAT(devis.totalttc,0),',',' ') as totalttc
        , devis.claus_comercial
        , clients.code
        , clients.denomination
        , clients.adresse
        , clients.bp
        , clients.tel
        , clients.nif
        , clients.email
        , ref_pays.pays
        , ref_ville.ville
        FROM
        devis
        INNER JOIN clients 
        ON (devis.id_client = clients.id)
        INNER JOIN ref_pays 
        ON (clients.id_pays = ref_pays.id)
        INNER JOIN ref_ville
        WHERE devis.id = " . $id_devis;
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->devis_info = $db->RowArray();
                $this->error = true;
            }
        }
    }

    public function get_id_devis() {
        global $db;

        $sql = "SELECT iddevis as id FROM 
    		contrats WHERE  contrats.id = " . $this->facture_info['idcontrat'];

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->id_devis = $db->RowArray();
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

}
