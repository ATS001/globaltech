<?php

//SYS GLOBAL TECH
// Modul: contrats => Model


class Mcontrat {

    private $_data; //data receive from form
    var $table = 'contrats'; //Main table of module
    var $table_echeance = 'echeances_contrat'; //Echeance table of module
    var $last_id; //return last ID after insert command
    var $log = NULL; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $id_contrat; // Contrat ID append when request
    var $id_devis; // Devis ID
    var $reference = null; // Reference contrat
    var $token; //user for recovery function
    var $contrat_info; //Array stock all contrat info
    var $devis_info; //Array stock devis info by id contrat
    var $echeance_contrat_info; //Array stock all echeance contrat info
    var $type_echeance_contrat_info; //Array stock all type echeance contrat info

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

    //Get all info contrat from database for edit form

    public function get_contrat() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* FROM 
    		$table WHERE  $table.id = " . $this->id_contrat;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->contrat_info = $db->RowArray();
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

    //Get all info echeance contrat from database for edit form
    public function get_echeance_contrat() {
        $table_echeance = $this->table_echeance;
        global $db;

        $sql = "SELECT $table_echeance.* FROM $table_echeance WHERE $table_echeance.id = " . $this->id_echeance_contrat;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->echeance_contrat_info = $db->RowArray();
                $this->error = true;
            }
        }

        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //Get id type_echance Autres 
    public function get_id_type_echeance($type) {
        $table_echeance = 'ref_type_echeance';
        global $db;

        $sql = "SELECT $table_echeance.id FROM $table_echeance WHERE $table_echeance.type_echance = '$type'";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->type_echeance_contrat_info = $db->RowArray();
                $this->error = true;
            }
        }

        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Gettable_echeance_contrat() {
        global $db;
        $id_contrat = $this->id_contrat;
        $table = $this->table_echeance;
        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.date_echeance, ";
        $colms .= " $table.commentaire ";

        $req_sql = " SELECT $colms FROM $table WHERE idcontrat = $id_contrat ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
        }

        //$style = array('5[#]center', '25[#]center', '70[#]alignLeft');
        $headers = array('Item'  => '5[#]center', 'Date Echéance'  => '15[#]center','Commentaire' => '30[#]',);

        //$tableau = $db->GetMTable($headers, null, $style);
        $tableau = $db->GetMTable($headers);


        return $tableau;
    }

    //Generate contrat reference
    private function Generate_contrat_reference() {
        if ($this->error == false) {
            return false;
        }
        global $db;
        $max_id = $db->QuerySingleValue0('SELECT IFNULL(( MAX(SUBSTR(ref, 5, LENGTH(SUBSTR(ref,5))-5))),0)+1  AS reference FROM contrats WHERE SUBSTR(ref,LENGTH(ref)-3,4)= (SELECT  YEAR(SYSDATE()))');
        $this->reference = 'CTR-' . $max_id . '/' . date('Y');
    }

    /////////////////////////////////////////////////////////////////////////////////
    private function Check_contrat_exist($tkn_frm, $edit = null) {
        global $db;
        $count_id = $db->QuerySingleValue0("SELECT COUNT(id) FROM contrats WHERE tkn_frm = '$tkn_frm'");
        //exit("0#".$count_id);
        if (($count_id != '0' && $edit == Null ) OR ( $count_id != '1' && $edit != null)) {
            $this->error = false;
            $this->log .= '</br>Ce contrat est déjà enregitré ' . $count_id . ' tkn= ' . $tkn_frm;
        }
    }

    //Save new contrat after all check
    public function save_new_contrat() {

        //Generate reference
        $this->Generate_contrat_reference();

        //Before execute do the multiple check
        $this->Check_exist('ref', $this->reference, 'Référence contrat', null);

        //Check if contrat exist
        $this->Check_contrat_exist($this->_data['tkn_frm'], null);


        $this->check_non_exist('devis', 'id', $this->_data['iddevis'], 'Devis');

        $this->check_non_exist('ref_type_echeance', 'id', $this->_data['idtype_echeance'], 'Type Echéance');

        //Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justifications du contrat.');
        }
        //Check if PJ attached required
        if ($this->exige_pj_photo) {
            $this->check_file('pj_photo', 'Photo .');
        }

        //Check $this->error (true / false)
        if ($this->error == true) {
            //Format values for Insert query 
            global $db;

            $values["ref"] = MySQL::SQLValue($this->reference);
            $values["tkn_frm"] = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["iddevis"] = MySQL::SQLValue($this->_data['iddevis']);
            $values["date_effet"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_effet'])));
            $values["date_fin"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_fin'])));
            $values["commentaire"] = MySQL::SQLValue($this->_data['commentaire']);
            $values["idtype_echeance"] = MySQL::SQLValue($this->_data['idtype_echeance']);
            $values["date_contrat"] = MySQL::SQLValue(date("Y-m-d"));
            $values["periode_fact"] = MySQL::SQLValue($this->_data['periode_fact']);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow($this->table, $values)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //If Attached required Save file to Archive

                $this->save_file('pj', 'Justifications du contrat' . $this->reference, 'Document');

                $this->save_file('pj_photo', 'Photo' . $this->reference, 'Image');

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Enregistrement réussie: <b>' . $this->reference . ' ID: ' . $this->last_id;
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

    //Edit contrat after all check
    public function edit_contrat() {
        $this->reference = $this->_data['ref'];
        //var_dump($this->_data['tkn_frm']);
        //Check if devis exist
        $this->Check_contrat_exist($this->_data['tkn_frm'], 1);
        //Check if devis have détails
        //$this->Check_contrat_have_details($this->_data['tkn_frm']);
        //Make reference
        //$this->Make_devis_reference();
        //Before execute do the multiple check
        $this->Check_exist('reference', $this->reference, 'Réference Devis', 1);

        $this->check_non_exist('devis', 'id', $this->_data['iddevis'], 'Client');

        //Check $this->error (true / false)
        if ($this->error == false) {
            $this->log .= '</br>Enregistrement non réussie';
            return false;
        }


        //Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justification du contrat.');
        }
        //Check if PJ attached required
        if ($this->exige_pj_photo) {
            $this->check_file('pj_photo', 'Photo .');
        }

        //Check $this->error (true / false)
        if ($this->error == true) {
            //Format values for Insert query 
            global $db;

            //$values["ref"] = MySQL::SQLValue($this->_data['ref']);
            //$values["tkn_frm"] = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["iddevis"] = MySQL::SQLValue($this->_data['iddevis']);
            $values["date_effet"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_effet'])));
            $values["date_fin"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_fin'])));
            $values["commentaire"] = MySQL::SQLValue($this->_data['commentaire']);
            $values["date_contrat"] = MySQL::SQLValue(date("Y-m-d"));
            $values["idtype_echeance"] = MySQL::SQLValue($this->_data['idtype_echeance']);
            $values["periode_fact"] = MySQL::SQLValue($this->_data['periode_fact']);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_contrat;

            //Check if Insert Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $this->id_contrat;
                //If Attached required Save file to Archive
                $this->save_file('pj', 'Justifications du contrat' . $this->_data['ref'], 'Document');


                $this->save_file('pj_photo', 'Photo' . $this->_data['ref'], 'image');

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['ref'] . ' ID: ' . $this->last_id;
                    $this->save_temp_detail($this->_data['tkn_frm'], $this->id_contrat);
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['ref'];
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

    private function save_temp_detail($tkn_frm, $id_devis) {

        $table_echeance = $this->table_echeance;
        global $db;
        $req_sql = "UPDATE $table_echeance SET idcontrat = $id_devis WHERE tkn_frm = '$tkn_frm'";
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème Enregistrement échéance dans le contrat';
        }
    }

    //Récuperer la valeur du champ Order echeance
    private function get_order_echeance($tkn_frm) {
        $table_echeance = $this->table_echeance;
        global $db;
        $req_sql = "SELECT IFNULL(MAX($table_echeance.order)+1,1) AS this_order FROM $table_echeance WHERE tkn_frm = '$tkn_frm'";
        $this->order_echeance = $db->QuerySingleValue0($req_sql);
    }

    //Verifier si la date d'échéance est déja insérée
    private function check_date_exist_in_echeance($tkn_frm, $date_echeance) {
        if ($this->error == false) {
            return false;
        }
        $table_echeance = $this->table_echeance;
        global $db;
        $req_sql = "SELECT COUNT($table_echeance.date_echeance) FROM $table_echeance WHERE tkn_frm='$tkn_frm' AND date_echeance = $date_echeance ";

        $count_id = $db->QuerySingleValue0($req_sql);
        if ($count_id != '0') {
            $this->error = false;
            $this->log .= '</br>Cette date échéance existe déjà dans la liste des échéances de ce contrat';
        }
    }

    //Vérifier si les dates d'échéance sont insérés (si le type d'echeance est Autres)
    private function Check_contrat_have_details($tkn_frm) {
        $table_echeance = $this->table_echeance;
        global $db;
        $req_sql = "SELECT COUNT($table_echeance.id) FROM $table_echeance,$this->table WHERE $this->table.id=$table_echeance.idcontrat and $this->table.idtype_echeance='Autres' and tkn_frm='$tkn_frm' ";
        if ($db->QuerySingleValue0($req_sql) == '0') {
            $this->error = false;
            $this->log .= '</br>Aucune date échéance enregistrée';
        }
    }

    //Insert new echeance
    public function save_new_echeance($tkn_frm) {
        $table_echeance = $this->table_echeance;
        $this->check_date_exist_in_echeance($tkn_frm, $this->_data['date_echeance']);


        //Check $this->error (true / false)
        if ($this->error == true) {

            //Get order line into echeance
            $this->get_order_echeance($tkn_frm);
            $order_echeance = $this->order_echeance;

            //Format values for Insert query 
            global $db;


            $values["tkn_frm"] = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["order"] = MySQL::SQLValue($order_echeance);
            $values["date_echeance"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_echeance'])));
            $values["commentaire"] = MySQL::SQLValue($this->_data['commentaire']);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));



            //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow($table_echeance, $values)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Enregistrement réussie: <b>' . $this->_data['date_echeance'] . ' ID: ' . $this->last_id;

                    //Check $this->error = false return Red message and Bol false   
                } else {
                    $this->log .= '</br>Enregistrement réussie: <b>' . $this->_data['date_echeance'];
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

    //Update echeance
    public function edit_echeance($tkn_frm) {
        $table_echeance = $this->table_echeance;
        $this->get_echeance_contrat();


        if ($this->s_echeance('date_echeance') != $this->_data['date_echeance']) {
            $this->check_date_exist_in_echeance($tkn_frm, $this->_data['date_echeance'], 1);
        }

        //Check $this->error (true / false)
        if ($this->error == true) {

            //Format values for Insert query 
            global $db;


            $values["date_echeance"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_echeance'])));
            $values["commentaire"] = MySQL::SQLValue($this->_data['commentaire']);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = MySQL::SQLValue($this->id_echeance_contrat);
            //Check if Insert Query been executed (False / True)
            if (!$db->UpdateRows($this->table_echeance, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Modification BD non réussie';
            } else {
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['date_echeance'] . ' ID: ' . $this->id_echeance_contrat;


                    //Check $this->error = false return Red message and Bol false   
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['date_echeance'];
                    $this->log .= '</br>Un problème d\'Modification ';
                }
            }
            //Else Error false  
        } else {
            $this->log .= '</br>Modification non réussie';
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //activer ou desactiver un contrat
   /* public function valid_contrat($etat = 0) {

        global $db;
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        $etat = $etat == 0 ? 1 : 0;
        //Format value for requet
        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $where["id"] = $this->id_contrat;

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
    
    */

    // afficher les infos d'un contrat
    public function printattribute($attibute) {
        if ($this->contrat_info[$attibute] != null) {
            echo $this->contrat_info[$attibute];
        } else {
            echo "";
        }
    }

    // afficher les infos d'un contrat
    public function Shw($key, $no_echo = "") {
        if ($this->contrat_info[$key] != null) {
            if ($no_echo != null) {
                return $this->contrat_info[$key];
            }

            echo $this->contrat_info[$key];
        } else {
            echo "";
        }
    }

    // afficher les infos echeance contrat
    public function Shw_echeance($key, $no_echo = "") {
        if ($this->echeance_contrat_info[$key] != null) {
            if ($no_echo != null) {
                return $this->echeance_contrat_info[$key];
            }

            echo $this->echeance_contrat_info[$key];
        } else {
            echo "";
        }
    }

    //get les infos  contrat
    public function s($key) {
        if ($this->contrat_info[$key] != null) {
            return $this->contrat_info[$key];
        } else {
            return null;
        }
    }

    // get les infos d'un devis
    public function g($key) {
        if ($this->devis_info[$key] != null) {
            return $this->devis_info[$key];
        } else {
            return null;
        }
    }

    //get les infos echeance contrat
    public function s_echeance($key) {
        if ($this->echeance_contrat_info[$key] != null) {
            return $this->echeance_contrat_info[$key];
        } else {
            return null;
        }
    }

    public function delete_contrat() {
        global $db;
        $id_contrat = $this->id_contrat;
        $this->get_contrat();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_contrat);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows('contrats', $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('contrats', $where);
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

    //delete echeance contrat
    public function Delete_echance_contrat($id_echeance_contrat) {
        global $db;
        $table_echeance = $this->table_echeance;
        $get_tkn_frm = $db->QuerySingleValue0("SELECT tkn_frm FROM $table_echeance  WHERE id = $id_echeance_contrat");

        //Format where clause
        $where['id'] = MySQL::SQLValue($id_echeance_contrat);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows($table_echeance, $where)) {
            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('contrat', $where); //???????
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
        $folder = MPATH_UPLOAD . 'contrats' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;



        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'contrats', $table, $column, $type, $edit = null)) {
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

    //get les infos d'une échéance
    public function h($key) {
        if ($this->echeance_contrat_info[$key] != null) {
            return $this->echeance_contrat_info[$key];
        } else {
            return null;
        }
    }

     public function get_contrat_info() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* , ref_type_echeance.type_echeance AS type_echeance "
                . "FROM $table,ref_type_echeance "
                . "WHERE  $table.idtype_echeance=ref_type_echeance.id AND $table.id = " . $this->id_contrat;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->contrat_info = $db->RowArray();
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


  
    //////////////////////////////////////////////////////////////////////////////
  
     public function Valid_contrat($etat)
    {
        global $db;
        $table = $this->table;
        $id_contrat = $this->id_contrat;
        $req_sql = " UPDATE $table SET etat = $etat+1 WHERE id = $id_contrat ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Validation";
            return false;
        }
        
        if(!$this->Get_detail_contrat_pdf())
        {
            $this->log .= $this->log;
            return false;

        }else{
            $this->log .= "Validation réussie";
            return true;
        }        
    }
   
  
  
///////////////////////////////////////////////////////////////////////////
    
    public function Get_detail_contrat_pdf()
    {
        global $db;
        
        
        $id_contrat = $this->id_contrat;
        $table = $this->table;
        $this->get_contrat();
        $contrat_info=$this->contrat_info;  
              
        $file_export = MPATH_TEMP.'contrats'.'_' .date('d_m_Y_H_i_s').'.pdf';

        //Load template 
        include_once MPATH_THEMES.'pdf_template/contrats_pdf.php';
        $new_file_target = MPATH_UPLOAD.'contrats'.date('m_Y');

        if(file_exists($file_export))
        {
              
            if(!Minit::save_file_upload($file_export, 'contrats_'.$id_contrat, $new_file_target, $id_contrat, 'contrats '.$id_contrat, 'contrats', 'contrats', 'contrats_pdf', 'document', $edit = null))
            {
                $this->error = false;
                $this->log .= "Erreur Archivage contrat";

            }
            
        }else{
            $this->error = false;
            $this->log .= "Erreur création template contrat";
        }

        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }
                
    }
    
    public function get_devis_info()
    {
    	$table = "devis";
    	global $db;

    	$sql = "SELECT $table.*,
		clients.denomination AS client , 
		clients.tel as tel , DATE_FORMAT($table.date_devis,'%d-%m-%Y') AS date_devis "
                . "FROM $table,clients "
                . "WHERE $table.id_client=clients.id  AND $table.id = ".$this->id_devis;

    	if(!$db->Query($sql))
    	{
    		$this->error = false;
    		$this->log  .= $db->Error();
    	}else{
    		if ($db->RowCount() == 0)
    		{
    			$this->error = false;
    			$this->log .= 'Aucun enregistrement trouvé ';
    		} else {
    			$this->devis_info = $db->RowArray();
    			$this->error = true;
    		}


    	}
    	//return Array
    	if($this->error == false)
    	{
    		return false;
    	}else{
    		return true ;
    	}
    }
    
}
    
