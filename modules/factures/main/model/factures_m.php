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
    var $contrat_info; // Array contrat info
    var $devis_info; // Array Devis info
    var $client_info; // Array Client info
    var $facture_details_info; // Array details facture
    var $type_echeance_info; // Array details facture
    var $compte_commercial_info;
    var $reference = null; // Reference 
    var $sum_enc_fact; // Somme encaissements par facture
    var $solde; // Solde client

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
    public function get_commerciale_devis() {
        global $db;

        $sql = "SELECT  c.id AS commercial, IFNULL(d.commission,0) AS commission, f.reference AS ref_facture, 'Automatique' AS type_commission 
            FROM devis d,factures f,commerciaux c WHERE  
            d.id= IF(f.`base_fact`='C',(SELECT ctr.iddevis FROM contrats ctr WHERE ctr.id=f.`idcontrat`),f.`iddevis`)
            AND d.`id_commercial`=c.id AND f.id  = " . $this->id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->compte_commercial_info = $db->RowArray();

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

    //Get facture type echeance
    public function get_facture_type_echeance() {
        global $db;
        $table = $this->table;

        $sql = "SELECT ech.`type_echeance` FROM factures f, contrats ctr, ref_type_echeance ech   
                WHERE f.idcontrat=ctr.id AND  ctr.idtype_echeance=ech.id
                AND f.id=" . $this->id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->type_echeance_info = $db->RowArray();
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
    public function get_client() {

        //$this->get_facture();

        global $db;
        $client = $this->facture_info['client'];

        $sql = "SELECT * FROM clients WHERE  denomination = '$client'";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->client_info = $db->RowArray();
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

    public function get_complement_by_facture() {
        global $db;

        $table_complement = $this->table_complement;

        /* $sql = "SELECT id,designation,type,
          REPLACE(FORMAT(montant,0),',',' ') as montant
          FROM $table_complement WHERE  $table_complement.idfacture = " . $this->id_facture; */
        $sql = "SELECT designation,type,
                REPLACE(FORMAT(montant,0),',',' ') as montant
                FROM $table_complement WHERE  $table_complement.idfacture = " . $this->id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->complement_info = $db->RecordsSimplArray();

                $this->error = true;
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function get_complement_by_facture_modele() {
        global $db;

        $table_complement = $this->table_complement;

        /* $sql = "SELECT id,designation,type,
          REPLACE(FORMAT(montant,0),',',' ') as montant
          FROM $table_complement WHERE  $table_complement.idfacture = " . $this->id_facture; */
        $sql = "SELECT id,designation,type,
                REPLACE(FORMAT(montant,0),',',' ') as montant
                FROM $table_complement WHERE  $table_complement.idfacture = " . $this->id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->complement_info = $db->RecordsArray();

                $this->error = true;
            }
        }
        //return Array user_activities
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
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->encaissement_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function get_all_encaissements() {
        global $db;

        $table_encaissement = $this->table_encaissement;

        $sql = "SELECT id,reference,designation,
                REPLACE(FORMAT(montant,0),',',' ') as montant,
                DATE_FORMAT(date_encaissement,'%d-%m-%Y') as date_encaissement ,
                pj as pj                       
                FROM 
    		$table_encaissement WHERE  $table_encaissement.idfacture = " . $this->id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->encaissement_info = $db->RecordsArray();
                $this->error = true;
            }
        }
        //return Array user_activities
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
        $max_id = $db->QuerySingleValue0('SELECT IFNULL(( MAX(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5))),0)+1  AS reference  FROM encaissements WHERE SUBSTR(reference,LENGTH(YEAR(SYSDATE()))-3,4)= (SELECT  YEAR(SYSDATE()))');
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
                $this->maj_fact_after_complement($this->_data['idfacture'], $this->_data['montant']);
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

        //$this->sum_encaissement_by_facture($this->_data['idfacture']);
        $this->id_facture = $this->_data['idfacture'];
        $this->get_facture();
        //$total_encaissement= $this->sum_enc_fact + $this->_data['montant'];
        //if($total_encaissement > $this->facture_info['total_ttc'])
        if ($this->_data['montant'] > $this->facture_info['reste']) {
            $this->error = FALSE;
            $this->log .= '</br>Le montant doit être inférieur ou égal à ' . $this->facture_info['reste'] . ' FCFA';
            return FALSE;
        }
        //$this->Generate_encaissement_reference();
        global $db;
        //Generate reference
        if (!$reference = $db->Generate_reference($this->table_encaissement, 'ENC')) {
            $this->log .= '</br>Problème Réference';
            return false;
        }


        //Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justifications du contrat.');
        }

        if ($this->error == true) {

            global $db;
            $values["reference"] = MySQL::SQLValue($reference);
            $values["designation"] = MySQL::SQLValue($this->_data['designation']);
            $values["idfacture"] = MySQL::SQLValue($this->_data['idfacture']);
            $values["mode_payement"] = MySQL::SQLValue($this->_data['mode_payement']);
            $values["ref_payement"] = MySQL::SQLValue($this->_data['ref_payement']);
            $values["montant"] = MySQL::SQLValue($this->_data['montant']);
            $values["depositaire"] = MySQL::SQLValue($this->_data['depositaire']);
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
                    //$this->maj_reste($this->_data['idfacture'], $this->_data['montant']);
                    //$this->get_facture();
                    /*
                      if ($this->facture_info['reste'] > 0) {
                      $this->valid_etat_facture($etat = 2, $this->_data['idfacture']);
                      }
                      if ($this->facture_info['reste'] == 0) {
                      $this->valid_etat_facture($etat = 3, $this->_data['idfacture']);
                      }
                     * 
                     */
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

    //Credit commercial account
    public function credit_compte_commerciale() {

        //$this->sum_encaissement_by_facture($this->_data['idfacture']);
        //$this->id_facture = $this->_data['idfacture'];

        $this->get_facture();

        $this->get_commerciale_devis();

        global $db;
//var_dump($db);
        if ($this->error == true) {

            global $db;
            $objet = $this->compte_commercial_info["commission"] . "% de la facture: " . $this->compte_commercial_info["ref_facture"];
            $values["id_commerciale"] = $this->compte_commercial_info["commercial"];
            $values["objet"] = MySQL::SQLValue($objet);
            $values["id_facture"] = $this->id_facture;
            //$values["id_encaissement"]=$this->last_id;
            $values["id_encaissement"] = $this->encaissement_info["id"];
            //$values["credit"] = (($this->_data["montant"] * $this->compte_commercial_info["commission"]) / 100) ;
            $values["credit"] = (($this->encaissement_info["montant"] * $this->compte_commercial_info["commission"]) / 100);
            $values["Type"] = MySQL::SQLValue($this->compte_commercial_info["type_commission"]);
            $values["etat"] = 1;
            $values["creusr"] = MySQL::SQLValue(session::get("userid"));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow('compte_commerciale', $values)) {
                var_dump($db);
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Enregistrement réussie: <b>' . 'ID: ' . $this->last_id;
                    $this->get_facture();
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

    public function edit_compte_commercial() {
        //Get existing data for complement
        $this->get_encaissement();
        $this->last_id = $this->id_encaissement;

        $this->get_commerciale_devis();

        global $db;

        $values["id_commerciale"] = $this->compte_commercial_info["commercial"];
        $values["credit"] = (($this->_data["montant"] * $this->compte_commercial_info["commission"]) / 100);
        $values["etat"] = 1;
        $values["updusr"] = MySQL::SQLValue(session::get("userid"));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id_encaissement"] = $this->id_encaissement;


        // If we have an error
        if ($this->error == true) {

            if (!$result = $db->UpdateRows("compte_commerciale", $values, $wheres)) {
                //$db->Kill();
                //var_dump($db);
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $this->id_compte_commerciale;

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['id'] . ' ID: ' . $this->last_id;
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

    public function test_first_encaissement($id_facture) {
        global $db;

        $table_encaissement = $this->table_encaissement;

        $sql = "SELECT $table_encaissement.* FROM $table_encaissement WHERE $table_encaissement.idfacture = " . $id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
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

    public function maj_fact_after_complement($id_facture, $montant) {

        global $db;
        $req_sql = "UPDATE factures SET reste = reste + $montant ,"
                . "total_ttc = total_ttc + $montant WHERE id = '$id_facture'";
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

    public function update_after_delete_complement($id_facture, $mt) {
        $this->id_facture = $id_facture;

        global $db;
        $req_sql = "UPDATE factures SET reste = reste - $mt , total_ttc = total_ttc - $mt WHERE id = '$id_facture'";
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
        $total_ttc = ($this->facture_info['total_ttc'] + $montant_init) - $montant_modif;

        global $db;
        $req_sql = "UPDATE factures SET reste = $reste , total_paye = $total_paye WHERE id = '$id_facture'";
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème de mise à jour du reste ';
        }
    }

    public function update_reste_after_complement($id_facture, $montant_init, $montant_modif) {
        $this->id_facture = $id_facture;
        $this->get_facture();
        //if ($montant_init < 0) {
        $reste = ($this->facture_info['reste'] - $montant_init) + $montant_modif;
        //$total_paye = ($this->facture_info['total_paye'] + $montant_init) - $montant_modif;
        $total_ttc = ($this->facture_info['total_ttc'] - $montant_init) + $montant_modif;

        global $db;
        $req_sql = "UPDATE factures SET reste = $reste , total_ttc = $total_ttc WHERE id = '$id_facture'";
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
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key) {
        if ($this->encaissement_info[$key] != null) {
            return $this->encaissement_info[$key];
        } else {
            return null;
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
            $this->update_after_delete_complement($this->complement_info['idfacture'], $this->complement_info['montant']);
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function delete_encaissement() {
        $mt = 0;
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
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function delete_compte_commercial() {
        global $db;
        $id_encaissement = $this->id_encaissement;
        $this->get_encaissement();
        //Format where clause
        $where['id_encaissement'] = MySQL::SQLValue($id_encaissement);
        //check if id on where clause isset
        if ($where['id_encaissement'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows('compte_commerciale', $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('compte_commerciale', $where);
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

    public function edit_complement() {


        //Get existing data for complement
        $this->get_complement();
        $mt_init = $this->complement_info['montant'];
        $this->last_id = $this->id_complement;

        if ($this->_data['type'] == "Réduction" && $this->_data['montant'] > 0)
            $this->_data['montant'] = -$this->_data['montant'];
        if ($this->_data['type'] == "Pénalité" && $this->_data['montant'] < 0)
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
                $this->update_reste_after_complement($this->_data['idfacture'], $mt_init, $this->_data['montant']);
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
        $values["depositaire"] = MySQL::SQLValue($this->_data['depositaire']);
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
                    //$this->update_reste($this->_data['idfacture'], $mt_init, $this->_data['montant']);
//                    if($this->facture_info['reste'] > 0 and $this->facture_info['reste'] == 4)
//                    {
//                        $this->invalid_etat_facture($etat=3,$this->_data['idfacture']);
//                    }
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

    public function valid_etat_facture($etat, $idf) {
        global $db;
        $table = $this->table;
        $id_facture = $idf;
        $req_sql = " UPDATE $table SET etat = $etat+1 WHERE id = $id_facture ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= " Erreur changement statut";
            return false;
        } else {

            return true;
        }
    }

    /*
      public function invalid_etat_facture($etat,$idf) {
      global $db;
      $table = $this->table;
      $id_facture = $idf;
      $req_sql = " UPDATE $table SET etat = $etat-1 WHERE id = $id_facture ";
      if (!$db->Query($req_sql)) {
      $this->error = false;
      $this->log .= " Erreur changement statut";
      return false;
      } else {

      return true;
      }
      }

     */

    public function valid_send_facture() {
        global $db;
        $table = $this->table;
        $values['etat'] = ' ETAT + 1 ';
        $wheres['id'] = MySQL::SQLValue($this->id_facture);

        if (!$result = $db->UpdateRows($table, $values, $wheres)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= 'Validation non réussie DB';
        }

        if (!$this->Get_detail_facture_pdf()) {
            $this->log .= $this->log;
            return false;
        } else {
            $this->log .= "Facture envoyée";
            return true;
        }
    }

    public function valid_facture() {
        global $db;
        $table = $this->table;
        $values['etat'] = ' ETAT + 1 ';
        $wheres['id'] = MySQL::SQLValue($this->id_facture);

        if (!$result = $db->UpdateRows($table, $values, $wheres)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= 'Validation non réussie DB';
        }

        if (!$this->Get_detail_facture_pdf()) {
            $this->log .= $this->log;
            return false;
        } else {
            $this->debit_compte_client();
            $this->log .= "Validation réussie";
            $this->send_valid_facture_mail();
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

    // afficher les infos d'un contrat
    public function printattribute_clt($attibute) {
        if ($this->client_info[$attibute] != null) {
            echo $this->client_info[$attibute];
        } else {
            echo "";
        }
    }

    // afficher les infos d'un contrat
    public function printattribute_ctr($attibute) {
        if ($this->contrat_info[$attibute] != null) {
            echo $this->contrat_info[$attibute];
        } else {
            echo "";
        }
    }

    //Get all info encaissement from database for edit form
    public function get_encaissement_info() {
        global $db;

        $table_encaissement = $this->table_encaissement;

        $sql = "SELECT $table_encaissement.* ,DATE_FORMAT($table_encaissement.date_encaissement,'%d-%m-%Y') as date_encaissement, factures.reference as facture FROM 
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

        $sql = "SELECT id,reference,base_fact,
                REPLACE(FORMAT(total_ht,0),',',' ') as total_ht ,  
                REPLACE(FORMAT(total_tva,0),',',' ') as total_tva ,         
                REPLACE(FORMAT(total_ttc,0),',',' ') as total_ttc,           
                REPLACE(FORMAT(total_ttc_initial,0),',',' ') as total_ttc_initial,
                REPLACE(FORMAT(total_paye,0),',',' ') as total_paye,
                REPLACE(FORMAT(reste,0),',',' ') as reste,               
                client,tva,projet,ref_bc,idcontrat,
                DATE_FORMAT(du,'%d-%m-%Y') as du,
                DATE_FORMAT(au,'%d-%m-%Y') as au,
                CONCAT(DATE_FORMAT(du,'%d-%m-%Y'),' Au ',DATE_FORMAT(au,'%d-%m-%Y')) as periode,
                DATE_FORMAT(date_facture,'%d-%m-%Y') as date_facture
                FROM 
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

    public function get_d_facture($id_facture) {

        //$id_facture = $this->id_facture;
        global $db;
        $req_sql = "SELECT
        d_factures.ref_produit
        , d_factures.designation
        , d_factures.qte
       
        , d_factures.prix_unitaire
       
        ,  REPLACE(FORMAT(d_factures.total_ht,0),',',' ') as totalht
      
        FROM
        d_factures
        WHERE d_factures.id_facture = " . $id_facture;
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->facture_details_info = $db->RowArray();
                $this->error = true;
            }
        }
    }

    public function Get_detail_facture_pdf() {
        global $db;

        $id_facture = $this->id_facture;

        $this->get_id_devis();
        $id_devis = $this->id_devis['id'];
        $id_facture = $this->id_facture;


        //$this->get_d_facture($this->id_facture);
        $facture_details_info = $this->facture_details_info;

        $table = $this->table_details;

        $this->Get_detail_facture_show();
        $devis_info = $this->devis_info;


        $this->get_facture_info();
        $info_facture = $this->facture_info;

        if ($this->facture_info['base_fact'] == 'C') {
            $this->get_contrat($this->facture_info['idcontrat']);
            $info_contrat = $this->contrat_info;
        }


        $colms = null;
        $colms .= " d_factures.order item, ";
        $colms .= " d_factures.ref_produit, ";
        $colms .= " d_factures.designation, ";
        $colms .= " CONCAT(REPLACE(FORMAT(d_factures.qte,0),',',' '),' ',IFNULL(d_factures.qte_designation,' ')) as qte, ";
        $colms .= " REPLACE(FORMAT(d_factures.prix_ht,0),',',' '), ";
        $colms .= " REPLACE(FORMAT(d_factures.total_ht,0),',', ' ') ";

        $req_sql = " SELECT $colms FROM d_factures WHERE d_factures.id_facture = $id_facture order by item";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
        }

        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Get_detail_facture_autres_pdf() {
        global $db;

        $id_facture = $this->id_facture;
        $this->get_id_devis();
        $id_devis = $this->id_devis['id'];
        $id_facture = $this->id_facture;

        //$this->get_d_facture($this->id_facture);
        $facture_details_info = $this->facture_details_info;

        $table = $this->table_details;

        $this->Get_detail_facture_show();
        $devis_info = $this->devis_info;


        $this->get_facture_info();
        $info_facture = $this->facture_info;

        if ($this->facture_info['base_fact'] == 'C') {
            $this->get_contrat($this->facture_info['idcontrat']);
            $info_contrat = $this->contrat_info;
        }


        $colms = null;
        $colms .= " d_factures.order item, ";
        $colms .= " d_factures.ref_produit, ";
        $colms .= " d_factures.designation ";

        $req_sql = " SELECT $colms FROM d_factures WHERE d_factures.id_facture = $id_facture order by item";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
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
        devis.*
        , DATE_FORMAT(devis.date_devis,'%d-%m-%Y') as date_devis
        ,  REPLACE(FORMAT(devis.totalht,0),',',' ') as totalht
        ,  REPLACE(FORMAT(devis.totaltva,0),',',' ') as totaltva
        ,  REPLACE(FORMAT(devis.totalttc,0),',',' ') as totalttc
        ,  REPLACE(FORMAT(devis.total_remise,0),',',' ') as total_remise
        ,  REPLACE(FORMAT(devis.total_remise + devis.totalht ,0),',',' ') as total_no_remise
        , clients.reference as reference_client
        , clients.denomination
        , clients.adresse
        , CONCAT('BP', clients.bp) as bp
        , clients.tel
        , clients.nif
        , clients.email
        , ref_pays.pays
        , ref_ville.ville
        , ref_devise.abreviation as devise
        , services.service as comercial
        , CONCAT(commerciaux.nom,' ',commerciaux.prenom) as commercial
        FROM
        devis
        INNER JOIN clients 
        ON (devis.id_client = clients.id)
        LEFT JOIN ref_pays 
        ON (clients.id_pays = ref_pays.id)
        LEFT JOIN ref_ville
        ON (clients.id_ville = ref_ville.id)
        INNER JOIN ref_devise
        ON (clients.id_devise = ref_devise.id)
        INNER JOIN users_sys
        ON (devis.creusr = users_sys.id)
        INNER JOIN services
        ON (users_sys.service = services.id)
        INNER JOIN commerciaux
        ON (devis.id_commercial=commerciaux.id)
        WHERE devis.id = " . $id_devis;

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
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

        $contrat = $this->facture_info['idcontrat'] == NULL ? 'NULL' : $this->facture_info['idcontrat'];
        $devis = $this->facture_info['iddevis'] == NULL ? 'NULL' : $this->facture_info['iddevis'];
        $sql = "SELECT IF('" . $this->facture_info['base_fact'] . "'='C', (SELECT iddevis FROM contrats WHERE id=$contrat), id ) as id FROM 
    		devis WHERE  devis.id =if( '" . $this->facture_info['base_fact'] . "'='C', (SELECT iddevis FROM contrats WHERE id=$contrat), $devis)";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé !!';
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

    public function sum_encaissement_by_facture($id_facture) {
        global $db;

        $table_encaissement = $this->table_encaissement;

        $sql = "SELECT sum(montant) FROM 
    		$table_encaissement WHERE  $table_encaissement.idfacture = " . $id_facture;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                //$this->sum_enc_fact = $db->RecordsSimplArray();
                $this->sum_enc_fact = $db->QuerySingleValue0();
                $this->error = true;
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function get_contrat($idcontrat) {

        global $db;

        $sql = "SELECT contrats.id,contrats.reference,contrats.iddevis, DATE_FORMAT(contrats.date_effet,'%d-%m-%Y') AS date_effet,
            DATE_FORMAT(contrats.date_fin,'%d-%m-%Y') AS date_fin,
            DATE_FORMAT(contrats.date_contrat,'%d-%m-%Y') AS date_contrat,contrats.commentaire,
            devis.`reference` as ref_devis, DATE_FORMAT(devis.`date_devis`,'%d-%m-%Y') as date_devis
            FROM contrats ,devis 
            WHERE contrats.iddevis=devis.`id` AND contrats.id = " . $idcontrat;




        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->contrat_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function valid_encaissement() {
        global $db;

        $this->id_facture = $this->encaissement_info['idfacture'];
        $this->get_facture();
        
       
        
        if ($this->encaissement_info['montant'] > $this->facture_info['reste']) {
            $this->error = false;
            $this->log = 'Le montant doit être inférieur ou égale au reste <b>' . $this->facture_info['reste'] . '</b>';
        } else {



            $values["etat"] = MySQL::SQLValue($etat = 1);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres['id'] = $this->id_encaissement;

            // Execute the update and show error case error
            if (!$result = $db->UpdateRows($this->table_encaissement, $values, $wheres)) {
                $this->log .= '</br>Impossible de changer le statut!';
                $this->log .= '</br>' . $db->Error();
                $this->error = false;
            } else {
                if ($this->error == true) {
                    
                    $this->log = '</br>Enregistrement réussie: <b>' . $this->reference . ' ID: ' . $this->last_id;

                    $this->maj_reste($this->encaissement_info['idfacture'], $this->encaissement_info['montant']);
                    $this->id_facture = $this->encaissement_info['idfacture'];
                    $this->get_facture();



                    if ($this->facture_info['reste'] > 0) {
                        
                        $this->valid_etat_facture($etat = 2, $this->encaissement_info['idfacture']);
                    }

                    if ($this->facture_info['reste'] == 0) {
                        
                        $this->valid_etat_facture($etat = 3, $this->encaissement_info['idfacture']);
                    }
                } else {
                    
                    $this->log .= '</br>Enregistrement réussie: <b>' . $this->reference;

                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }
                $this->log .= '</br>Statut changé! ';
                
                
                $this->credit_compte_client();
                
                $this->error = true;
                
                if (!Mlog::log_exec($this->table_encaissement, $this->id_encaissement, 'Validation encaissement', 'Validate')) {
                    $this->log .= '</br>Un problème de log ';
                }
                
            }
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Get_detail_encaissement_show() {
        global $db;
        $req_sql = "SELECT
        encaissements.*,factures.reste,factures.client
        , DATE_FORMAT(encaissements.date_encaissement,'%d-%m-%Y') as date_encaissement
        , factures.reference as reference_facture
        ,encaissements.montant
		,encaissements.mode_payement AS mode_payement
		,encaissements.ref_payement       
        , CONCAT(users_sys.fnom,' ',users_sys.lnom) as commercial
        FROM
        encaissements
        INNER JOIN factures 
        ON (encaissements.idfacture = factures.id)
        LEFT JOIN users_sys 
        ON (encaissements.creusr = users_sys.id)
        WHERE encaissements.id = " . $this->id_encaissement;

        if (!$db->Query($req_sql)) {
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
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function archiver_facture() {
        global $db;
        $table = $this->table;
        $values['etat'] = "100";
        $wheres['id'] = MySQL::SQLValue($this->id_facture);

        if (!$result = $db->UpdateRows($table, $values, $wheres)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= 'Archivage non réussie DB';
        }

        if (!$this->Get_detail_facture_pdf()) {
            $this->log .= $this->log;
            return false;
        } else {
            $this->log .= "Archivage réussi";
            return true;
        }
    }

    public function archivefactures() {
        global $db;
        $id_facture = $this->id_facture;
        $table = $this->table;
        $this->get_facture();
        //Format where clause
        $id_facture = MySQL::SQLValue($id_facture);
        $sql_req = "UPDATE $table SET etat = 100 WHERE id = $id_facture";

        //check if id on where clause isset
        if ($id_facture == null) {
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
            if (!Mlog::log_exec($table, $this->id_facture, 'Archivage devis ' . $this->id_facture, 'Update')) {
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

    public function getSoldeClient($id_client) {
        global $db;

        $sql = "SELECT 0  AS solde FROM DUAL WHERE NOT EXISTS (SELECT * FROM compte_client cc WHERE  cc.id_client=$id_client )
UNION
(SELECT compte_client.solde AS solde FROM compte_client where compte_client.id_client =$id_client order by id desc limit 1 )";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->solde = $db->QuerySingleValue0($sql);
                $this->error = true;
            }
        }
    }

    public function debit_compte_client() {
        global $db;

        $mnt = str_replace(' ', '', $this->facture_info['total_ttc']);
        $clt = $this->devis_info['id_client'];
        
        $this->getSoldeClient($clt);
      
        $sld = $this->solde;

        $base=$this->facture_info['base_fact'];
        $reference=$this->facture_info['reference'];
        $du=$this->facture_info['du'];
        $au=$this->facture_info['au'];
        
        //var_dump($this->facture_info);
        
        $req_sql = "INSERT into compte_client(id_client,type_mouvement,montant,description,date_mouvement,solde,creusr) 
               values($clt,'D',$mnt,IF('$base'='C', CONCAT('Facture: ', '$reference',' du ','$du',' du ', '$au'),CONCAT('Facture: ','$reference')),NOW(), $sld+$mnt ,1)";
        
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème de mise à jour Etat de compte';
        }
        //var_dump($db);
    }

    function credit_compte_client() {
        global $db;

        $mnt = str_replace(' ', '', $this->encaissement_info['montant'] );
        $this->get_id_devis();
        $id_devis = $this->id_devis['id'];
        
        $this->Get_detail_facture_show();
        $devis_info = $this->devis_info;
        
        $clt = $this->devis_info['id_client'];
        
        $this->getSoldeClient($clt);
      
        $sld = $this->solde;

        $reference=$this->encaissement_info['reference'];
        $date=$this->encaissement_info['date_encaissement'];    
        $ref_payement=$this->encaissement_info['ref_payement'];
        
        //var_dump($this->facture_info);
        
        $req_sql = "INSERT into compte_client(id_client,type_mouvement,montant,description,date_mouvement,solde,creusr) 
               values($clt,'C',$mnt,CONCAT('Paiement: ', '$reference',' du ',DATE_FORMAT('$date','%d-%m-%Y'),"
                . "IF('$ref_payement'<> null,Concat(': Référence N°: ','$ref_payement'),' '))"
                . ",NOW(), $sld-$mnt ,1)";
        
        
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème de mise à jour Etat de compte';
        }
        ////var_dump($db);
        // Verifier Message Aucun Enregistrement apres validation
    }
  
    private function send_valid_facture_mail() {
        //Get info abonnement
        $this->get_facture();
        $facture_info = $this->facture_info;

        if ($this->verif_email($facture_info["creusr"]) == FALSE) {
            $this->log .= '<br/>Ce commerciale n\'a pas une adresse Mail';
            return false;
        }

        $commerciale = new Musers();
        $commerciale->id_user = $facture_info["creusr"];
        $commerciale->get_user();
        $agent_name = $commerciale->g('fnom') . ' ' . $commerciale->g('lnom');
        $agent_service = $commerciale->g('service_user');
        $agent_tel = $commerciale->g('tel');

        $mail = new PHPMailer();
        $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
        $mail->SMTPSecure = 'ssl'; // Accepter SSL
        $mail->setFrom($mail->Username, 'GlobalTech Direction'); // Personnaliser l'envoyeur
        $mail->addAddress('contact@globaltech.td', 'Globaltech DG');
        $mail->addCC('commercial@globaltech.td', 'Globaltech DCM');
        $mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

        $mail->Subject = "Facture Réf: #" . $facture_info['reference'];

        $mail->Body = "<b> Bonjour " . $commerciale->g('fnom') . " " . $commerciale->g('lnom') 
                . ",</br></br> La facture REF: " . $facture_info['reference'] . " a été validée. </br></br> Cordialement</b>";
        if (!$mail->send()) {
            $this->log .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $this->log .= "Mail validation facture envoyé  à " . $commerciale->g('mail');
        }
    }

    private function verif_email($id_commerciale) {
        global $db;
        $result = $db->QuerySingleValue0("SELECT mail FROM users_sys WHERE id=" . $id_commerciale);
        if ($result == "0") {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
