    <?php

    /**
     * Class Gestion des achats 1.0
     */
    class Machat {

        private $_data; //data receive from form
        var $table = 'stock'; //Main table of module
        var $last_id; //return last ID after insert command
        var $log = NULL; //Log of all opération.
        var $error = true; //Error bol changed when an error is occured
        var $id_achat; // achat ID append when request
        var $token; //user for recovery function
        var $achat_info; //Array stock all achat info
        var $app_action; //Array action for each 

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

        //Get all info achat from database for edit form

        public function get_achat_produit() {
            global $db;

            $table = $this->table;

            $sql = "SELECT $table.* FROM 
    		$table WHERE  $table.id = " . $this->id_achat;

            if (!$db->Query($sql)) {
                $this->error = false;
                $this->log .= $db->Error();
            } else {
                if ($db->RowCount() == 0) {
                    $this->error = false;
                    $this->log .= 'Aucun enregistrement trouvé ';
                } else {
                    $this->achat_info = $db->RowArray();
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

        //Save new produit after all check
        public function save_new_achat_produit() {


            global $db;
            $values["mouvement"] = MySQL::SQLValue('E');
            $values["qte"] = MySQL::SQLValue($this->_data['qte']);
            $values["prix_achat"] = MySQL::SQLValue($this->_data['prix_achat']);
            $values["prix_vente"] = MySQL::SQLValue($this->_data['prix_vente']);
            $values["idproduit"] = MySQL::SQLValue($this->_data['idproduit']);
            $values["date_achat"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_achat'])));
            $values["date_validite"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_validite'])));
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

            // If we have an error
            if ($this->error == true) {

                if (!$result = ($db->InsertRow("stock", $values))) {

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

        //activer ou valider une produit
        public function valid_achat_produit($etat = 0) {
            global $db;

            //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
            $etat = $etat == 0 ? 1 : 0;

            $values["etat"] = MySQL::SQLValue($etat);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres['id'] = $this->id_achat;

            // Execute the update and show error case error
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                $this->log .= '</br>Impossible de changer le statut!';
                $this->log .= '</br>' . $db->Error();
                $this->error = false;
            } else {
                $this->log .= '</br>Statut changé! ';
                //$this->log   .= $this->table.' '.$this->id_produit.' '.$etat;
                $this->error = true;
            }
            if ($this->error == false) {
                return false;
            } else {
                return true;
            }
        }

        //Edit produit after all check
        public function edit_achat_produit() {

            //Get existing data for produit
            $this->get_achat_produit();
            $this->last_id = $this->id_achat;


            global $db;
            $values["qte"] = MySQL::SQLValue($this->_data['qte']);
            $values["mouvement"] = MySQL::SQLValue("E");
            $values["prix_achat"] = MySQL::SQLValue($this->_data['prix_achat']);
            $values["prix_vente"] = MySQL::SQLValue($this->_data['prix_vente']);
            $values["idproduit"] = MySQL::SQLValue($this->_data['idproduit']);
            $values["date_achat"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_achat'])));
            $values["date_validite"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_validite'])));
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_achat;


            // If we have an error
            if ($this->error == true) {

                if (!$result = $db->UpdateRows("stock", $values, $wheres)) {
                    //$db->Kill();
                    $this->log .= $db->Error();
                    $this->error == false;
                    $this->log .= '</br>Enregistrement BD non réussie';
                } else {

                    //$this->last_id = $result;
                    $this->log .= '</br>Enregistrement  réussie ' . $this->_data['ref'] . ' - ' . $this->last_id . ' -';
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

        public function delete_achat_produit() {
            global $db;
            $id_achat = $this->id_achat;
            $this->get_achat_produit();
            //Format where clause
            $where['id'] = MySQL::SQLValue($id_achat);
            //check if id on where clause isset
            if ($where['id'] == null) {
                $this->error = false;
                $this->log .= '</br>L\' id est vide';
            }
            //execute Delete Query
            if (!$db->DeleteRows('stock', $where)) {

                $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('stock', $where);
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

        public function get_achat_produit_info() {
            global $db;
            $table = $this->table;
            //Format Select commande
            $sql = "SELECT $table.*,produits.ref
                    FROM $table,produits"
                    . " WHERE  $table.idproduit  = produits.id "
                    . " AND $table.id = " . $this->id_achat;
            if (!$db->Query($sql)) {
                $this->error = false;
                $this->log .= $db->Error();
            } else {
                if ($db->RowCount() == 0) {
                    $this->error = false;
                    $this->log .= 'Aucun enregistrement trouvé ';
                } else {
                    $this->achat_info = $db->RowArray();
                    $this->error = true;
                }
            }
            //return Array prm_info
            if ($this->error == false) {
                return false;
            } else {
                return true;
            }
        }

        public function printattribute($attibute) {
            if ($this->achat_info[$attibute] != null) {
                echo $this->achat_info[$attibute];
            } else {
                echo "";
            }
        }

        // afficher les infos d'une produit
        public function Shw($key, $no_echo = "") {
            if ($this->achat_info[$key] != null) {
                if ($no_echo != null) {
                    return $this->achat_info[$key];
                }

                echo $this->achat_info[$key];
            } else {
                echo "";
            }
        }

    }
