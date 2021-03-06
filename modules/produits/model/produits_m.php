<?php

/**
 * Class Gestion des produits 1.0
 */
class Mproduit {

    private $_data; //data receive from form
    var $table = 'produits'; //Main table of module
    var $last_id; //return last ID after insert command
    var $log = NULL; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $id_produit; // Produit ID append when request
    var $token; //user for recovery function
    var $produit_info; //Array stock all produit info
    var $mouvement_info; //Array mouvement info
    var $app_action; //Array action for each 
    static $etat_produit;

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

    //Get all info produit from database for edit form

    public function get_produit() {
        global $db;

        $table = $this->table;

        $sql = "SELECT $table.* FROM 
    		$table WHERE  $table.id = " . $this->id_produit;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->produit_info = $db->RowArray();
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
    public function save_new_produit() {

        global $db;
        //Generate reference
        //$this->Generate_produit_reference();
    if(!$reference = $db->Generate_reference($this->table, 'PRD', false))
        {
                $this->log .= '</br>Problème Réference';
                return false;
        } 
        //Before execute do the multiple check
        // check Region
        $this->Check_exist('reference', $this->reference, 'Référence', null);


        global $db;
        $values["reference"] = MySQL::SQLValue($reference);
        $values["designation"] = MySQL::SQLValue($this->_data['designation']);
        $values["stock_min"] = MySQL::SQLValue($this->_data['stock_min']);
         $values["exige-sn"] = MySQL::SQLValue($this->_data['exige-sn']);
        $values["idcategorie"] = MySQL::SQLValue($this->_data['idcategorie']);
        $values["iduv"] = MySQL::SQLValue($this->_data['iduv']);
        $values["idtype"] = MySQL::SQLValue($this->_data['idtype']);
        $values["prix_vente"] = MySQL::SQLValue($this->_data['prix_vente']);
        $values["id_entrepot"] = MySQL::SQLValue($this->_data['id_entrepot']);
        $values["creusr"] = MySQL::SQLValue(session::get('userid'));
        $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
        if ($this->error == true) {

            if (!$result = $db->InsertRow("produits", $values)) {

                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                $this->log .= '</br>Enregistrement  réussie ' . $reference . ' - ' . $this->last_id . ' -';

                    if(!Mlog::log_exec($this->table, $this->last_id , 'Insertion produit', 'Insert'))
                    {
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
    
    private function refresh_products()
    {
        global $db;
        $sql_req = " CALL refresh_products()";

        if(!$db->Query($sql_req))
        {
            $this->log .= '</br>Erreur actualisation de produits'.$sql_req;
            $this->error = false;
        }else{
            $this->error = true;
        }

        if($this->error == false){
          return false;
        }else{
          return true;
        }

    }

    //activer ou valider un produit
    public function valid_produit($etat) {
        global $db;
        $this->get_produit();
        if($this->produit_info["idtype"] == "2" OR $this->produit_info["idtype"] == "3")
        {
            $etat = Msetting::get_set('etat_produit', 'produit_valide_ap');
        }else{
        $etat = Msetting::get_set('etat_produit', 'produit_valide_p');
        }

        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres['id'] = $this->id_produit;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->refresh_products();
            $this->log .= '</br>Statut changé! ';
            $this->error = true;

                    if(!Mlog::log_exec($this->table, $this->id_produit , 'Validation produit', 'Validate'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    
    //activer ou valider un produit
    public function desactiver_produit() {
        global $db;
        $this->get_produit();
       
            $etat = Msetting::get_set('etat_produit', 'attente_validation');
       
        $values["etat"] = MySQL::SQLValue($etat);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres['id'] = $this->id_produit;

        // Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->log .= '</br>Statut changé! ';
            //$this->log   .= $this->table.' '.$this->id_produit.' '.$etat;
            $this->error = true;

                    if(!Mlog::log_exec($this->table, $this->id_produit , 'Validation produit', 'Validate'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }


    //Edit produit after all check
    public function edit_produit() {

        //Get existing data for produit
        $this->get_produit();

        $this->last_id = $this->id_produit;

        global $db;
        $values["designation"] = MySQL::SQLValue($this->_data['designation']);
        $values["stock_min"] = MySQL::SQLValue($this->_data['stock_min']);
        $values["idcategorie"] = MySQL::SQLValue($this->_data['idcategorie']);
        $values["iduv"] = MySQL::SQLValue($this->_data['iduv']);
        $values["idtype"] = MySQL::SQLValue($this->_data['idtype']);
        $values["prix_vente"] = MySQL::SQLValue($this->_data['prix_vente']);
        $values["id_entrepot"] = MySQL::SQLValue($this->_data['id_entrepot']);
        $values["exige-sn"] = MySQL::SQLValue($this->_data['exige-sn']);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id"] = $this->id_produit;


        // If we have an error
        if ($this->error == true) {

            if (!$result = $db->UpdateRows("produits", $values, $wheres)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .= '</br>Modification BD non réussie';
            } else {


                //Esspionage
                if(!$db->After_update($this->table, $this->id_produit, $values, $this->produit_info)){
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false; 
                }

                //$this->last_id = $result;
                $this->log .= '</br>Modification  réussie ' . $this->produit_info['reference'] . ' - ' . $this->last_id . ' -';

                    if(!Mlog::log_exec($this->table, $this->id_produit , 'Modification produit', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }

            }
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

    public function delete_produit() {
        global $db;
        $id_produit = $this->id_produit;
        $this->get_produit();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_produit);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows('produits', $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('produits', $where);
            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Suppression réussie ';

                if(!Mlog::log_exec($this->table, $this->id_produit , 'Suppression produit', 'Delete'))
                    {
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

    public function get_produit_info() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.*,ref_categories_produits.categorie_produit,ref_unites_vente.unite_vente,ref_types_produits.type_produit
                    FROM $table,ref_categories_produits, ref_unites_vente,ref_types_produits"
                . " WHERE  $table.idcategorie  = ref_categories_produits.id AND $table.iduv = ref_unites_vente.id AND $table.idtype = ref_types_produits.id "
                . " AND $table.id = " . $this->id_produit;
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->produit_info = $db->RowArray();
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
        if ($this->produit_info[$attibute] != null) {
            echo $this->produit_info[$attibute];
        } else {
            echo "";
        }
    }

    // afficher les infos d'une produit
    public function Shw($key, $no_echo = "") {
        if ($this->produit_info[$key] != null) {
            if ($no_echo != null) {
                return $this->produit_info[$key];
            }

            echo $this->produit_info[$key];
        } else {
            echo "";
        }
    }

    public function get_mouvement_info() {
        global $db;
        $table = $this->table;
       
        $sql = "SELECT mouvements_stock.*,DATE_FORMAT(DATE,'%d-%m-%Y') as DATE,IF(mouvement='Sortie',CONCAT('<span class=\"badge badge-danger\">',mouvement,'</span>'),CONCAT('<span class=\"badge badge-success\">',mouvement,'</span>')) AS mouvement
                        FROM  mouvements_stock where mouvements_stock.id_produit = " . $this->id_produit;
        
    if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->mouvement_info = $db->RecordsArray();
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
}
