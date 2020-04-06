<?php

/**
 * MDevis Gestion Devis et Détail
 * V1.0
 */
class Mdevis {

    //Declared Private
    private $_data; //data receive from form
    //Declared Variable
    var $table               = 'devis'; //Main table of module
    var $table_details       = 'd_devis'; //Tables détails devis
    var $last_id             = null; //return last ID after insert command
    var $log                 = null; //Log of all opération.
    var $id_devis            = null; // Devis ID append when request
    var $token               = null; //user for recovery function
    var $devis_info          = null; //Array stock all ville info
    var $devis_d_info        = null; //
    var $reference           = null; // Reference Devis
    var $error               = true; //Error bol changed when an error is occured
    var $valeur_remis_d      = null; //
    var $total_remise        = null; //
    var $prix_u_final        = null; //
    var $total_ht_d          = null; //
    var $total_tva_d         = null; //
    var $total_ttc_d         = null; //
    var $valeur_remis_t      = null; //
    var $total_ht_t          = null; //
    var $total_tva_t         = null; //
    var $order_detail        = null; //
    var $sum_total_ht        = null; //
    var $arr_prduit          = array();
    var $attached            = null;
    var $type_devis          = null; //Type Devis (ABN / VNT)
    var $info_temp_client    = array();
    var $total_commission    = null; //
    var $total_commission_ex = null; //
    var $etat_valid_devis    = 0;

    public function __construct($properties = array()) {
        $this->_data = $properties;
    }

    //magic methods!
    public function __set($property, $value) {
        return $this->_data[$property] = $value;
    }

    public function __get($property) {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null;
    }

    public function get_devis() {

        $table = $this->table;
        global $db;

        $sql = "SELECT $table.*, DATE_FORMAT($table.date_devis,'%d-%m-%Y') AS date_devis from $table where $table.id = " . $this->id_devis;

        if (!$db->Query($sql)) {
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
        //return Array
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //////////////////////////////////////////////////////////////////
    public function get_devis_d() {
        $table_details = $this->table_details;
        global $db;

        $sql = "SELECT $table_details.* ,
        ref_categories_produits.id as categ_id, ref_categories_produits.categorie_produit,
        ref_types_produits.id as type_id, produits.designation
        FROM
        $table_details , ref_types_produits, ref_categories_produits, produits
        WHERE
        produits.idcategorie = ref_categories_produits.id
        AND ref_types_produits.id = ref_categories_produits.type_produit
        AND $table_details.id_produit = produits.id
        AND $table_details.id = " . $this->id_devis_d;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error() . '  ' . $sql;
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->devis_d_info = $db->RowArray();
                $this->error = true;
            }
        }

        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Get_detail_devis_show() {
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
        , devis.id_banque
        , clients.adresse
        , CONCAT('BP', clients.bp) as bp
        , clients.tel
        , clients.nif
        , clients.email
        , ref_pays.pays
        , ref_ville.ville
        , ref_devise.abreviation as devise
        , services.service as comercial
        , (SELECT  GROUP_CONCAT(CONCAT(c.prenom,' ',c.nom) ORDER BY c.id ASC SEPARATOR ', ') AS prenoms FROM commerciaux c WHERE FIND_IN_SET(c.id, REPLACE(REPLACE(REPLACE((REPLACE(devis.id_commercial,'[','')),']',''),'\"',''),'\"','')) > 0 ) as commercial
        , CONCAT(users_sys.lnom,' ',users_sys.fnom) as cre_usr,
        IF(devis.devis_base  != null, (select reference from devis where id = devis.devis_base),null) as db 
        FROM
        devis
        INNER JOIN clients
        ON (devis.id_client = clients.id)
        LEFT JOIN ref_pays
        ON (clients.id_pays = ref_pays.id)
        LEFT JOIN ref_ville
        ON (clients.id_ville = ref_ville.id)
        INNER JOIN ref_devise
        ON (devis.id_devise = ref_devise.id)
        INNER JOIN users_sys
        ON (devis.creusr = users_sys.id)
        INNER JOIN services
        ON (users_sys.service = services.id)
        
        WHERE devis.id = " . $this->id_devis;


        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé';
            } else {
                $this->devis_info = $db->RowArray();
                $this->error = true;
            }
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * [Get_detail_devis_pdf Render query for export PDF]
     * @return bol send to controller
     */
    public function Get_detail_devis_pdf() {
        global $db;

        $id_devis = $this->id_devis;
        $table = $this->table_details;
        $this->Get_detail_devis_show();
        $devis_info = $this->devis_info;
        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.prix_ht,0),',',' '), ";
        //$colms .= " $table.type_remise, ";
        //$colms .= " CONCAT(REPLACE(FORMAT($table.remise_valeur,0),',',' '),'%'), ";
        // $colms .= " REPLACE(FORMAT($table.total_ht,0),',',' '), ";
        // $colms .= " REPLACE(FORMAT($table.total_tva,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_ht,0),',', ' ') ";

        $req_sql = " SELECT $colms FROM $table WHERE id_devis = $id_devis ";
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

    public function Gettable_detail_devis() {
        global $db;
        $id_devis = $this->id_devis;
        $table = $this->table_details;
        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.prix_ht,0),',',' '), ";
        //$colms .= " $table.type_remise, ";
        $colms .= " $table.remise_valeur, ";

        $colms .= " REPLACE(FORMAT($table.total_ht,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_tva,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_ttc,0),',', ' ') ";

        $req_sql = " SELECT $colms FROM $table WHERE id_devis = $id_devis ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
        }


        $headers = array(
            'Item' => '5[#]center',
            'Réf' => '7[#]center',
            'Description' => '29[#]',
            'Qte' => '5[#]center',
            'P.U' => '10[#]alignRight',
            'Re' => '5[#]center',
            'Total HT' => '10[#]alignRight',
            'TVA' => '10[#]alignRight',
            'Total TTC' => '10[#]alignRight',
        );


        $tableau = $db->GetMTable($headers);


        return $tableau;
    }

    //////////////////////////////////////////////////////////////////
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
     * [check_date_devis description]
     * @param  [type] $date_devis [description]
     * @return [type]             [description]
     */
    Private function check_date_devis($date_devis) {
        $today = date('Y-m-d');

        $date_devis = date('Y-m-d', strtotime($date_devis));
        $date_devis_moin = date('Y-m-d', strtotime($today . ' - 3 days'));
        $date_devis_plus = date('Y-m-d', strtotime($today . ' + 3 days'));

        if ($date_devis_moin > $date_devis OR $date_devis_plus < $date_devis) {
            $this->error = false;
            $this->log .= '</br>La date devis ne doit pas dépasser un interval de 3 jours';
        }
    }

    /////////////////////////////////////////////////////////////////////////////////
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

    /////////////////////////////////////////////////////////////////////////////////
    private function Check_devis_exist($tkn_frm, $edit = null) {
        global $db;
        $count_id = $db->QuerySingleValue0("SELECT COUNT(id) FROM devis WHERE tkn_frm = '$tkn_frm'");
        //exit("0#".$count_id);
        if (($count_id != '0' && $edit == Null ) OR ( $count_id != '1' && $edit != null)) {
            $this->error = false;
            $this->log .= '</br>Ce devis est déjà enregitré ' . $count_id;
        }
    }

    private function get_type_devis($tkn_frm) {
        if ($this->error == false) {
            return false;
        }
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id_produit) FROM $table_details, produits, ref_types_produits WHERE tkn_frm = '$tkn_frm' AND  $table_details.id_produit = produits.id AND produits.idtype = ref_types_produits.id AND ref_types_produits.type_produit like 'Abonnement'";

        $count_id = $db->QuerySingleValue0($req_sql);
        if ($count_id > 0) {
            $this->type_devis = 'ABN';
        } else {
            $this->type_devis = 'VNT';
        }
        $this->error == true;
    }

    public function renew_devis() {
        //Check if devis exist
        $this->Check_devis_exist($this->_data['tkn_frm'], null);
        //Check if devis have détails
        $this->Check_devis_have_details($this->_data['tkn_frm']);
        //Check interval date devis
        $this->check_date_devis($this->_data['date_devis']);
        //Before execute do the multiple check
        $this->Check_exist('reference', $this->reference, 'Réference Devis', null);

        $this->check_non_exist('clients', 'id', $this->_data['id_client'], 'Client');

        $this->check_non_exist('commerciaux', 'id', $this->_data['id_commercial'], 'Commercial');

        //Get sum of details
        $this->Get_sum_detail($this->_data['tkn_frm']);
        //calcul values devis
        $this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva'], $this->_data['commission']);
        global $db;
        //Generate reference
        if (!$reference = $db->Generate_reference($this->table, 'DEV')) {
            $this->log .= '</br>Problème Réference';
            return false;
        }
        $this->get_type_devis($this->_data['tkn_frm']);
        //Check $this->error (true / false)
        if ($this->error == false) {
            $this->log .= '</br>Enregistrement non réussie';
            return false;
        }

        //Format values for Insert query
        $montant_remise = $this->sum_total_ht - $this->total_ht_t;
        $totalht = $this->total_ht_t;
        $totaltva = $this->total_tva_t;
        $totalttc = $this->total_ttc_t;
        $valeur_remise = $this->valeur_remis_t;
        $total_remise = $this->total_remise;
        $total_commission = $this->total_commission;

        $client = new Mclients;
        $client->id_client = $this->_data['id_client'];
        $client->get_client();

        $values["reference"]        = MySQL::SQLValue($this->reference);
        $values["tkn_frm"]          = MySQL::SQLValue($this->_data['tkn_frm']);
        $values["type_devis"]       = MySQL::SQLValue($this->type_devis);
        $values["reference"]        = MySQL::SQLValue($reference);
        $values["id_client"]        = MySQL::SQLValue($this->_data['id_client']);
        $values["id_banque"]        = $client->client_info['id_banque'];
        $values["tva"]              = MySQL::SQLValue($this->_data['tva']);
        $values["id_commercial"]    = MySQL::SQLValue($this->_data['id_commercial']);
        $values["commission"]       = MySQL::SQLValue($this->_data['commission']);
        $values["type_commission"]  = MySQL::SQLValue($this->_data['type_commission']);
        $values["total_commission"] = MySQL::SQLValue($total_commission);
        $values["date_devis"]       = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_devis'])));
        $values["type_remise"]      = MySQL::SQLValue($this->_data['type_remise']);
        $values["valeur_remise"]    = MySQL::SQLValue($valeur_remise);
        $values["total_remise"]     = MySQL::SQLValue($montant_remise);
        $values["projet"]           = MySQL::SQLValue($this->_data['projet']);
        $values["vie"]              = MySQL::SQLValue($this->_data['vie']);
        $values["claus_comercial"]  = MySQL::SQLValue($this->_data['claus_comercial']);
        $values["totalht"]          = MySQL::SQLValue($totalht);
        $values["totalttc"]         = MySQL::SQLValue($totalttc);
        $values["totaltva"]         = MySQL::SQLValue($totaltva);
        $values["creusr"]           = MySQL::SQLValue(session::get('userid'));
        $values["credat"]           = MySQL::SQLValue(date("Y-m-d H:i:s"));
        //Check if Insert Query been executed (False / True)
        if (!$result = $db->InsertRow($this->table, $values)) {
            //False => Set $this->log and $this->error = false
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '</br>Enregistrement BD non réussie';
        } else {
            $this->last_id = $result;
            //Check $this->error = true return Green message and Bol true
            if ($this->error == true) {
                $this->log = '</br>Enregistrement réussie: <b>Réference: ' . $reference;
                $this->save_temp_detail($this->_data['tkn_frm'], $this->last_id);
                //log
                if (!Mlog::log_exec($this->table, $this->last_id, 'Enregistrement Devis ' . $this->last_id, 'Insert')) {
                    $this->log .= '</br>Un problème de log ';
                }
                //Check $this->error = false return Red message and Bol false
            } else {
                $this->log .= '</br>Enregistrement réussie: <b>' . $reference;
                $this->log .= '</br>Un problème d\'Enregistrement ';
            }
        }//Else Error false
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////
    public function save_new_devis() {
        //Check if devis exist
        $this->Check_devis_exist($this->_data['tkn_frm'], null);
        //Check if devis have détails
        $this->Check_devis_have_details($this->_data['tkn_frm']);
        //Check interval date devis
        $this->check_date_devis($this->_data['date_devis']);
        //Before execute do the multiple check
        $this->Check_exist('reference', $this->reference, 'Réference Devis', null);

        $this->check_non_exist('clients', 'id', $this->_data['id_client'], 'Client');

        $this->check_commercial_exist($this->_data['id_commercial']);

        //Get sum of details
        $this->Get_sum_detail($this->_data['tkn_frm']);
        //calcul values devis
        $this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva'], $this->_data['commission'], $this->_data['commission_ex']);
        global $db;
        //Generate reference
        if (!$reference = $db->Generate_reference($this->table, 'DEV')) {
            $this->log .= '</br>Problème Réference';
            return false;
        }
        $this->get_type_devis($this->_data['tkn_frm']);
        //Check $this->error (true / false)
        if ($this->error == false) {
            $this->log .= '</br>Enregistrement non réussie';
            return false;
        }



        //Format values for Insert query
        $montant_remise = $this->sum_total_ht - $this->total_ht_t;
        $totalht = $this->total_ht_t;
        $totaltva = $this->total_tva_t;
        $totalttc = $this->total_ttc_t;
        $valeur_remise = $this->valeur_remis_t;
        $total_remise = $this->total_remise;
        $total_commission = $this->total_commission;
        $total_commission_ex = $this->total_commission_ex;



        //var_dump("ID User connectée".session::get('userid'));

        if (!$this->get_commerciale_remise_plafond(session::get('userid'), $valeur_remise)) {
            return false;
        }

        $etat_line = $this->etat_valid_devis;

        $client = new Mclients;
        $client->id_client = $this->_data['id_client'];
        $client->get_client();

        $values["reference"] = MySQL::SQLValue($this->reference);
        $values["tkn_frm"] = MySQL::SQLValue($this->_data['tkn_frm']);
        $values["type_devis"] = MySQL::SQLValue($this->type_devis);
        $values["reference"] = MySQL::SQLValue($reference);
        $values["id_client"] = MySQL::SQLValue($this->_data['id_client']);
        $values["id_devise"]       = $client->client_info['id_devise'];            
        $values["id_banque"] = $client->client_info['id_banque'];
        $values["tva"] = MySQL::SQLValue($this->_data['tva']);

        $values["id_commercial"] = MySQL::SQLValue(json_encode($this->_data['id_commercial']));
        $values["commission"] = MySQL::SQLValue($this->_data['commission']);
        $values["total_commission"] = MySQL::SQLValue($total_commission);
        $values["type_commission"] = MySQL::SQLValue($this->_data['type_commission']);

        $values["id_commercial_ex"] = MySQL::SQLValue($this->_data['id_commercial_ex']);
        $values["commission_ex"] = MySQL::SQLValue($this->_data['commission_ex']);
        $values["total_commission_ex"] = MySQL::SQLValue($total_commission_ex);
        $values["type_commission_ex"] = MySQL::SQLValue($this->_data['type_commission_ex']);

        $values["date_devis"] = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_devis'])));
        $values["type_remise"] = MySQL::SQLValue($this->_data['type_remise']);

        $values["devis_base"] = MySQL::SQLValue($this->_data['devis_base']);

        $values["valeur_remise"] = MySQL::SQLValue($valeur_remise);
        $values["total_remise"] = MySQL::SQLValue($montant_remise);
        $values["projet"] = MySQL::SQLValue($this->_data['projet']);
        $values["vie"] = MySQL::SQLValue($this->_data['vie']);
        $values["claus_comercial"] = MySQL::SQLValue($this->_data['claus_comercial']);
        $values["totalht"] = MySQL::SQLValue($totalht);
        $values["totalttc"] = MySQL::SQLValue($totalttc);
        $values["totaltva"] = MySQL::SQLValue($totaltva);
        $values["etat"] = MySQL::SQLValue($etat_line);
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
            //Check $this->error = true return Green message and Bol true
            if ($this->error == true) {
                $this->log .= '</br>Enregistrement réussie: <b>Réference: ' . $reference;
                // $this->send_creat_devis_mail($values["id_commercial"]);
                $this->save_temp_detail($this->_data['tkn_frm'], $this->last_id);

                if($this->_data['devis_base'] != null){

                  $etat_devis_revise = Msetting::get_set('etat_devis', 'devis_revise');
                  $etat_contrat_revise = Msetting::get_set('etat_contrat', 'devis_revise');

                  $this->change_etat($etat_devis_revise,$this->table, $this->_data['devis_base']); // Etat résvisé pour le devis (Parent

                  $this->change_etat($etat_contrat_revise,'contrats',$this->_data['devis_base']); // Etat annulé/expiré  pour l'abonnement lié au devis (Parent)
                } 

                //log
                if (!Mlog::log_exec($this->table, $this->last_id, 'Enregistrement Devis ' . $this->last_id, 'Insert')) {
                    $this->log .= '</br>Un problème de log ';
                }
                //Check $this->error = false return Red message and Bol false
            } else {
                $this->log .= '</br>Enregistrement réussie: <b>' . $reference;
                $this->log .= '</br>Un problème d\'Enregistrement ';
            }
        }//Else Error false
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function edit_exist_devis() {
        //$this->reference = $this->_data['reference'];
        //Check if devis exist
        $this->Check_devis_exist($this->_data['tkn_frm'], 1);
        //Check if devis have détails
        $this->Check_devis_have_details($this->_data['tkn_frm']);
        //Make reference
        //$this->Make_devis_reference();
        //Before execute do the multiple check
        $this->Check_exist('reference', $this->reference, 'Réference Devis', 1);

        $this->check_non_exist('clients', 'id', $this->_data['id_client'], 'Client');

        $this->check_commercial_exist($this->_data['id_commercial']);
        //Get sum of details
        $this->Get_sum_detail($this->_data['tkn_frm']);
        //calcul values devis

        $this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva'], $this->_data['commission'], $this->_data['commission_ex']);


        //Get Type devis
        $this->get_type_devis($this->_data['tkn_frm']);
        //Check $this->error (true / false)
        if ($this->error == false) {
            $this->log .= '</br>Enregistrement non réussie';
            return false;
        }
        $this->get_devis();
        //Format values for Insert query
        global $db;
        $montant_remise = $this->sum_total_ht - $this->total_ht_t;
        $totalht = $this->total_ht_t;
        $totaltva = $this->total_tva_t;
        $totalttc = $this->total_ttc_t;
        $total_commission = $this->total_commission;
        $total_commission_ex = $this->total_commission_ex;
        $valeur_remise = number_format($this->valeur_remis_t, 2, '.', '');
        $this->reference = $this->devis_info['reference'];

        if (!$this->get_commerciale_remise_plafond(session::get('userid'), $valeur_remise)) {
            return false;
        }
        $etat_line = $this->etat_valid_devis;

        $client = new Mclients;
        $client->id_client = $this->_data['id_client'];
        $client->get_client();

        $values["reference"]           = MySQL::SQLValue($this->reference);
        $values["tkn_frm"]             = MySQL::SQLValue($this->_data['tkn_frm']);
        $values["type_devis"]          = MySQL::SQLValue($this->type_devis);
        $values["id_client"]           = MySQL::SQLValue($this->_data['id_client']);
        $values["id_devise"]           = $client->client_info['id_devise'];
        $values["id_banque"]           = $client->client_info['id_banque'];
        $values["tva"]                 = MySQL::SQLValue($this->_data['tva']);
        
        $values["id_commercial"] = MySQL::SQLValue(json_encode($this->_data['id_commercial']));
        $values["commission"]          = MySQL::SQLValue($this->_data['commission']);
        $values["total_commission"]    = MySQL::SQLValue($total_commission);
        $values["type_commission"]     = MySQL::SQLValue($this->_data['type_commission']);
        
        $values["id_commercial_ex"]    = MySQL::SQLValue($this->_data['id_commercial_ex']);
        $values["commission_ex"]       = MySQL::SQLValue($this->_data['commission_ex']);
        $values["total_commission_ex"] = MySQL::SQLValue($total_commission_ex);
        $values["type_commission_ex"]  = MySQL::SQLValue($this->_data['type_commission_ex']);
        
        $values["date_devis"]          = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_devis'])));
        $values["type_remise"]         = MySQL::SQLValue($this->_data['type_remise']);
        $values["valeur_remise"]       = MySQL::SQLValue($valeur_remise);
        $values["total_remise"]        = MySQL::SQLValue($montant_remise);
        $values["projet"]              = MySQL::SQLValue($this->_data['projet']);
        $values["vie"]                 = MySQL::SQLValue($this->_data['vie']);
        $values["claus_comercial"]     = MySQL::SQLValue($this->_data['claus_comercial']);
        
        $values["totalht"]             = MySQL::SQLValue($totalht);
        $values["totalttc"]            = MySQL::SQLValue($totalttc);
        $values["totaltva"]            = MySQL::SQLValue($totaltva);
        $values["etat"]                = MySQL::SQLValue($etat_line);
        $values["updusr"]              = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]              = ' CURRENT_TIMESTAMP ';
        $wheres["id"]                  = MySQL::SQLValue($this->id_devis);
        //Check if Insert Query been executed (False / True)
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
            //False => Set $this->log and $this->error = false
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '</br>Modification BD non réussie';
        } else {
            $this->last_id = $this->id_devis;
            //Check $this->error = true return Green message and Bol true
            if ($this->error == true) {
                $this->log .= '</br>Modification réussie: <b>Réference: ' . $this->reference;
                $this->save_temp_detail($this->_data['tkn_frm'], $this->id_devis);
                //log
                if (!Mlog::log_exec($this->table, $this->last_id, 'Modification Devis ' . $this->last_id, 'Update')) {
                    $this->log .= '</br>Un problème de log ';
                }
                //Spy
                if (!$db->After_update($this->table, $this->last_id, $values, $this->devis_info)) {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;
                }
                //Check $this->error = false return Red message and Bol false
            } else {
                $this->log .= '</br>Modification réussie: <b>' . $this->reference;
                $this->log .= '</br>Un problème d\'Modification ';
            }
        }//Else Error false
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function verif_commission($tkn_frm, $commission) {
        $table_details = $this->table_details;
        $tva_value = Mcfg::get('tva');
        global $db;
        if ($tva == 'N') {
            $req_sql = "UPDATE $table_details SET total_ttc = total_ht, total_tva = 0  WHERE tkn_frm = '$tkn_frm'";
        } else {
            $req_sql = "UPDATE $table_details SET  total_tva = ((total_ht * $tva_value)/100), total_ttc = (total_ht + total_tva)  WHERE tkn_frm = '$tkn_frm'";
        }

        //Run adaptation
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<\br>Problème Enregistrement détails dans le devis';
            return false;
        } else {
            $this->Get_sum_detail($tkn_frm);
            $this->log .= 'Adaptation TVA réussie';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
    }

    private function Calculate_devis_d($prix_u, $qte, $type_remise, $value_remise, $tva) {

        $val_remise = $value_remise == null ? '0' : $value_remise;

        if ($type_remise == 'P') {
            $prix_u_remised = $prix_u - ($prix_u * $val_remise) / 100;
            $this->valeur_remis_d = $val_remise;
        } else if ($type_remise == 'M') {
            $prix_u_remised = $prix_u - $val_remise;
            $this->valeur_remis_d = ($val_remise * 100) / $prix_u;
        } else {
            $prix_u_remised = $prix_u;
        }
        //TVA value get from app setting
        $tva_value = Msetting::get_set('tva');
        $this->prix_u_final = $prix_u_remised;
        //Total HT
        $this->total_ht_d = $prix_u_remised * $qte;
        //Calculate TVA
        if ($tva == 'N') {
            $this->total_tva_d = 0;
        } else {
            $this->total_tva_d = ($this->total_ht_d * $tva_value) / 100;
        }
        $this->total_ttc_d = $this->total_ht_d + $this->total_tva_d;


        /* $arr = array('Pu' => $prix_u, 'qte' => $qte, 'typ_remise' => $type_remise, 'val_remise' => $val_remise, 'prix_u_remised' => $prix_u_remised, 'tva' => $tva, 'total_tva' => $this->total_tva_d, 'total_ht_d' => $this->total_ht_d, 'total_ttc_d' => $this->total_ttc_d);
          var_dump($arr);
          exit(); */
        return true;
    }

    private function Calculate_devis_t($totalht, $type_remise, $value_remise, $tva, $commission, $commission_ex) {
        if ($type_remise == 'P') {
            $totalht_remised = $totalht - ($totalht * $value_remise) / 100;
            $this->valeur_remis_t = $value_remise;
            $this->total_remise = ($totalht * $value_remise) / 100;
        } elseif ($type_remise == 'M') {
            $totalht_remised = $totalht - $value_remise;
            $this->valeur_remis_t = ($value_remise * 100) / $totalht;
            $this->total_remise = $value_remise;
        } else {
            $totalht_remised = $totalht;
        }

        //Valeur remised en percentage
        //Total HT
        $this->total_ht_t = $totalht_remised;
        //TVA value get from app setting
        $tva_value = Msetting::get_set('tva');
        //Calculate TVA
        if ($tva == 'N') {
            $this->total_tva_t = 0;
        } else {
            $this->total_tva_t = ($this->total_ht_t * $tva_value) / 100; //TVA value get from app setting
        }
        $this->total_ttc_t = $this->total_ht_t + $this->total_tva_t;

        //Total commission
        $this->total_commission = ($this->total_ht_t * $commission) / 100;
        $this->total_commission_ex = ($this->total_ht_t * $commission_ex) / 100;

        return true;
    }

    private function get_order_detail($tkn_frm) {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT IFNULL(MAX($table_details.order)+1,1) AS this_order FROM $table_details WHERE tkn_frm = '$tkn_frm'";
        $this->order_detail = $db->QuerySingleValue0($req_sql);
    }

    private function check_detail_exist_in_devis($tkn_frm, $id_produit) {
        if ($this->error == false) {
            return false;
        }
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id_produit) FROM $table_details WHERE tkn_frm='$tkn_frm' AND id_produit = $id_produit ";

        $count_id = $db->QuerySingleValue0($req_sql);
        if ($count_id != '0') {
            $this->error = false;
            $this->log .= '</br>Ce produit / service exist déjà dans la liste de ce devis';
        }
    }

    private function check_detail_have_more_abn($tkn_frm) {
        if ($this->error == false) {
            return false;
        }
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id_produit) FROM $table_details, produits, ref_types_produits WHERE tkn_frm = '$tkn_frm' AND  $table_details.id_produit = produits.id AND produits.idtype = ref_types_produits.id AND ref_types_produits.type_produit like 'Abonnement'";

        $count_id = $db->QuerySingleValue0($req_sql);
        if ($count_id > 0) {
            $this->error = false;
            $this->log .= '</br>Impossible d\'insérer deux abonnement sur le même devis';
        }
    }

    private function Check_devis_have_details($tkn_frm) {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id) FROM $table_details WHERE tkn_frm='$tkn_frm' ";
        if ($db->QuerySingleValue0($req_sql) == '0') {
            $this->error = false;
            $this->log .= '</br>Pas de détails enregistrés';
        }
    }

    private function save_temp_detail($tkn_frm, $id_devis) {

        $table_details = $this->table_details;
        global $db;
        $req_sql = "UPDATE $table_details SET id_devis = $id_devis WHERE tkn_frm = '$tkn_frm'";
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème Enregistrement détails dans le devis';
        }
    }

    public function set_tva_for_detail_on_change_main_tva($tkn_frm, $tva) {
        $table_details = $this->table_details;
        $tva_value = Mcfg::get('tva');
        global $db;
        if ($tva == 'N') {
            $req_sql = "UPDATE $table_details SET total_ttc = total_ht, total_tva = 0  WHERE tkn_frm = '$tkn_frm'";
        } else {
            $req_sql = "UPDATE $table_details SET  total_tva = ((total_ht * $tva_value)/100), total_ttc = (total_ht + total_tva)  WHERE tkn_frm = '$tkn_frm'";
        }

        //Run adaptation
        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<\br>Problème Enregistrement détails dans le devis';
            return false;
        } else {
            $this->Get_sum_detail($tkn_frm);
            $this->log .= 'Adaptation TVA réussie';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
    }

    //update commission in details_devis after the change of commission in the main
    public function set_commission_for_detail_on_change_main_commission($tkn_frm, $commission, $type_commission = 'C') {
        //var_dump($commission);
        $table_details = $this->table_details;
        $tva_value = Mcfg::get('tva');

        if ($type_commission == 'S') {
            $commission = 0;
        }

        global $db;
        $req_sql = "UPDATE $table_details SET  prix_ht = (prix_unitaire +((prix_unitaire * $commission)/100)), total_ht = (prix_ht * qte), total_tva = ((total_ht * $tva_value)/100), total_ttc = (total_ht + total_tva)  WHERE tkn_frm = '$tkn_frm'";

        //Run adaptation

        if (!$db->Query($req_sql)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<\br>Problème Enregistrement détails dans le devis';
            return false;
        } else {
            $this->Get_sum_detail($tkn_frm);
            $this->log .= 'Adaptation Commission réussie';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
    }

    public function prices_update_on_devise_change($tkn_frm, $taux_change)
    {
        $table_details = $this->table_details;
        global $db;
//var_dump($taux_change);
        if($taux_change == null){
            $req_sql = "UPDATE $table_details d
                        SET d.`prix_unitaire`= d.`pu_devise_pays`,
                            d.`prix_ht`=IF(IFNULL(d.`remise_valeur`,0) <> 0,(d.`pu_devise_pays`- (d.`pu_devise_pays`* d.`remise_valeur`)/100),d.`pu_devise_pays`),
                            d.`total_ht`=(d.`prix_ht` * d.`qte`), d.`total_tva`=((d.`total_ht`* d.`tva`) / 100),
                            d.`total_ttc`=(d.`total_ht` + d.`total_tva`),
                            d.`taux_change`= NULL,d.`updusr`=1,d.`upddat`=(SELECT NOW() FROM DUAL)
                        WHERE tkn_frm = '$tkn_frm'";
        }else{
            $req_sql = "UPDATE $table_details d
                        SET d.`prix_unitaire`=(d.`pu_devise_pays` * $taux_change),
                            d.`prix_ht`=IF(IFNULL(d.`remise_valeur`,0) <> 0,((d.`pu_devise_pays`- (d.`pu_devise_pays`* d.`remise_valeur`)/100)* $taux_change),(d.`pu_devise_pays`* $taux_change)),
                            d.`total_ht`=(d.`prix_ht` * d.`qte`), d.`total_tva`=((d.`total_ht`* d.`tva`) / 100),
                            d.`total_ttc`=(d.`total_ht` + d.`total_tva`),
                            d.`taux_change`= $taux_change,d.`updusr`=1,d.`upddat`=(SELECT NOW() FROM DUAL)
                        WHERE tkn_frm = '$tkn_frm'";
        }

        //Run adaptation
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            return false;
        }else
        {           
            $this->Get_sum_detail($tkn_frm);            
            $this->log .='Adaptation Taux de change réussite';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
     
    }

    public function devis_update_on_client_change($id, $id_client, $id_devise, $id_banque, $tva, $totalht, $totalttc,$totaltva)
    {
        $table = $this->table;
        global $db;
        
        $req_sql = "UPDATE $table d
                       SET d.`id_client`= $id_client, d.`id_devise`=$id_devise, d.`id_banque`=$id_banque, d.`tva`='$tva', d.`totalht`= $totalht, d.`totalttc`=$totalttc, d.`totaltva`=$totaltva ,d.`updusr`=1,d.`upddat`=(SELECT NOW() FROM DUAL)
                     WHERE d.`id` = $id";

        //Run adaptation
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            return false;
        }else
        {           
            $this->log .='Modification client réussite';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log);
        return $arr_return;
     
    }

    private function Get_sum_detail($tkn_frm) {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT SUM($table_details.total_ht)  FROM $table_details WHERE tkn_frm = '$tkn_frm' ";
        $this->sum_total_ht = $db->QuerySingleValue0($req_sql);
    }

    private function Get_sum_detail_no_remise($tkn_frm) {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT SUM($table_details.prix_unitaire)  FROM $table_details WHERE tkn_frm = '$tkn_frm' ";
        $sum_total_no_remise = $db->QuerySingleValue0($req_sql);
        return $sum_total_no_remise;
    }

    private function check_remise_value_by_service($value_remise, $type_remise, $prix_u, $comercial_id) {
        global $db;
        $val_remise = $value_remise == null ? '0' : $value_remise;

        if ($type_remise == 'P') {
            $prix_u_remised = $prix_u - ($prix_u * $val_remise) / 100;
            $this->valeur_remis_d = $val_remise;
        } else if ($type_remise == 'M') {
            $prix_u_remised = $prix_u - $val_remise;
            $this->valeur_remis_d = ($val_remise * 100) / $prix_u;
        } else {
            $prix_u_remised = $prix_u;
        }
    }

    public function save_new_details_devis($tkn_frm) {

        $table_details = $this->table_details;
        $this->check_detail_exist_in_devis($tkn_frm, $this->_data['id_produit']);
        $this->check_non_exist('produits', 'id', $this->_data['id_produit'], 'Réference du produit');
        $this->check_detail_have_more_abn($tkn_frm);

        //Check $this->error (true / false)
        if ($this->error == true) {
            //Calcul Montant
            $this->Calculate_devis_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva_d']);
            //Get produit info
            $produit = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();

            $ref_produit = $produit->produit_info['reference'];
            $designation = $produit->produit_info['designation'];
            //Valeu finance
            $total_ht = $this->total_ht_d;
            $total_tva = $this->total_tva_d;
            $total_ttc = $this->total_ttc_d;

            $valeur_remis_d = number_format($this->valeur_remis_d, 2, '.', '');
            $prix_u_final = $this->prix_u_final;
            if (!$this->get_commerciale_remise_plafond(session::get('userid'), $valeur_remis_d)) {
                //var_dump("USER ID CONNECTE".session::get('userid'));
                return false;
            }

            //Get order line into devis
            $this->get_order_detail($tkn_frm);
            $order_detail = $this->order_detail;
            $tva = Msetting::get_set('tva');
            //Format values for Insert query
            global $db;


            $values["tkn_frm"] = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["id_produit"] = MySQL::SQLValue($this->_data['id_produit']);
            $values["order"] = MySQL::SQLValue($order_detail);
            $values["ref_produit"] = MySQL::SQLValue($ref_produit);
            $values["designation"] = MySQL::SQLValue($designation);
            $values["taux_change"]   = MySQL::SQLValue(Mreq::tp('taux_devise'));
            $values["qte"]           = MySQL::SQLValue($this->_data['qte']);
            $values["pu_devise_pays"] = MySQL::SQLValue(Mreq::tp('pu_devise_pays')); 
            $values["prix_unitaire"] = MySQL::SQLValue(Mreq::tp('pu'));
            //$this->_data['prix_unitaire']);
            $values["type_remise"] = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["prix_ht"] = MySQL::SQLValue($prix_u_final);
            $values["remise_valeur"] = MySQL::SQLValue($valeur_remis_d);
            $values["tva"] = MySQL::SQLValue($tva);
            $values["total_ht"] = MySQL::SQLValue($this->total_ht);
            $values["total_ttc"] = MySQL::SQLValue($this->total_ttc);
            $values["total_tva"] = MySQL::SQLValue($this->total_tva);
            $values["creusr"] = MySQL::SQLValue(session::get('userid'));


            //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow($table_details, $values)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;

                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Enregistrement réussie: <b>' . $this->_data['ref_produit'] . ' ID: ' . $this->last_id;
                    //log
                    if (!Mlog::log_exec($table_details, $this->last_id, 'Enregistrement Détail Devis ' . $this->last_id, 'Insert')) {
                        $this->log .= '</br>Un problème de log ';
                    }


                    $this->Get_sum_detail($this->_data['tkn_frm']);
                    //Check $this->error = false return Red message and Bol false
                } else {
                    $this->log .= '</br>Enregistrement réussie: <b>' . $this->_data['ref_produit'];
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

    public function edit_exist_details_devis($tkn_frm) {
        $table_details = $this->table_details;
        $this->get_devis_d();
        if ($this->h('id_produit') != $this->_data['id_produit']) {
            $this->check_detail_exist_in_devis($tkn_frm, $this->_data['id_produit'], 1);
            $this->check_detail_have_more_abn($tkn_frm);
        }

        $this->check_non_exist('produits', 'id', $this->_data['id_produit'], 'Réference du produit');


        //Check $this->error (true / false)
        if ($this->error == true) {
            //Calcul Montant
            $this->Calculate_devis_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva_d']);
            //Get produit info
            $produit = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();
            
            //Valeu finance
            $total_ht = $this->total_ht_d;

            $total_tva = $this->total_tva_d;
            $total_ttc = $this->total_ttc_d;
            $valeur_remis_d = number_format($this->valeur_remis_d, 2, '.', '');
            $prix_u_final = $this->prix_u_final;
            
            if (!$this->get_commerciale_remise_plafond(session::get('userid'), $this->valeur_remis_d)) {
                return false;
            }

            $ref_produit = $produit->produit_info['reference'];
            $designation = $produit->produit_info['designation'];
            
            $tva = Msetting::get_set('tva');
            
            
            if (!$this->get_commerciale_remise_plafond(session::get('userid'), $valeur_remis_d)) {
                return false;
            }

            $ref_produit = $produit->produit_info['reference'];
            $designation = $produit->produit_info['designation'];
            
            //Format values for Insert query
            global $db;

            $values["id_produit"] = MySQL::SQLValue($this->_data['id_produit']);
            $values["ref_produit"] = MySQL::SQLValue($ref_produit);
            $values["designation"] = MySQL::SQLValue($designation);
            $values["taux_change"]   = MySQL::SQLValue(Mreq::tp('taux_devise'));
            $values["qte"]           = MySQL::SQLValue($this->_data['qte']);
            $values["pu_devise_pays"] = MySQL::SQLValue(Mreq::tp('pu_devise_pays')); 
            $values["prix_unitaire"] = MySQL::SQLValue(Mreq::tp('pu'));
            //$values["prix_unitaire"] = MySQL::SQLValue($this->_data['prix_unitaire']);
            $values["type_remise"] = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["remise_valeur"] = MySQL::SQLValue($valeur_remis_d);
            $values["prix_ht"] = MySQL::SQLValue($prix_u_final);
            $values["tva"] = MySQL::SQLValue($tva);
            $values["total_ht"] = MySQL::SQLValue($total_ht);
            $values["total_ttc"] = MySQL::SQLValue($total_ttc);
            $values["total_tva"] = MySQL::SQLValue($total_tva);
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = ' CURRENT_TIMESTAMP ';
            $wheres["id"] = MySQL::SQLValue($this->id_devis_d);
            //Check if Insert Query been executed (False / True)
            if (!$db->UpdateRows($this->table_details, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Modification BD non réussie';
            } else {
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['ref_produit'] . ' ID: ' . $this->id_devis_d;

                    $this->Get_sum_detail($tkn_frm);
                    //log if is edit main devis
                    if ($this->devis_d_info['id_devis'] != null) {
                        if (!Mlog::log_exec($this->table, $this->id_devis, 'Modification Détail Devis ' . $this->devis_d_info['id_devis'], 'Update')) {
                            $this->log .= '</br>Un problème de log ';
                        }
                        //Spy
                        if (!$db->After_update($table_details, $this->id_devis_d, $values, $this->devis_d_info)) {
                            $this->log .= '</br>Problème Esspionage';
                            $this->error = false;
                        }
                    }


                    //Check $this->error = false return Red message and Bol false
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['ref_produit'];
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

    private function check_client_temp($id_client) {
        global $db;
        $type_client = $db->QuerySingleValue0("SELECT type_client FROM clients  WHERE id = $id_client");

        if ($type_client == 'T') {
            $this->log .= '</br>Le client est temporaire</br>Vous devez complèter le profile de client ' . $type_client;
            return false;
        } else {
            return true;
        }
    }

    private function insert_realise_into_objectif_mensuel($test)
    {

        $commercials_array = json_decode($this->devis_info['id_commercial'], true);
        $nbr_commercials = count($commercials_array);
        if(!is_array($commercials_array)){
            $this->log .="</br>Impossible de trouver commercial(s) pour ce devis ";
            return false;
        }
        global $db;

        //Début FZ pour Upgrade le 04/04/2020
        if ($this->devis_info['devis_base'] == NULL){
            $realise = intval(($this->g('totalht') - $this->g('total_commission_ex')) / $nbr_commercials);             
        }else{
            $devis_upgrade = new  Mdevis();
            $devis_upgrade->id_devis = $this->devis_info['devis_base'];
            $devis_upgrade->get_devis();

            $realis_devis  = ($this->g('totalht') - $this->g('total_commission_ex'));
            $realis_upgrade= ($devis_upgrade->g('totalht') - $devis_upgrade->g('total_commission_ex'));

            if( $realis_devis > $realis_upgrade){
                $realise = intval(( $realis_devis - $realis_upgrade)/ $nbr_commercials);
            }else{
                $realise = 0;                 
            }              
        
        }
        //Fin FZ le 04/04/2020

        /*============================================================
        =            test if all commercial have objectif            =
        ============================================================*/   
        $year = date('Y', strtotime($this->_data['date_valid_client']));
        $month = date('m', strtotime($this->_data['date_valid_client']));    
        foreach ($commercials_array as $id_commercial) {
        //Début FZ pour éliminer la vérification de l'objectif mensuel s'il s'agit de la DG ou Admin le 27/03/2020
        $commercial = new Mcommerciale();
        $commercial->id_commerciale= $id_commercial;
        $commercial->get_commerciale();
        $commercial_id_user = $commercial->g('id_user_sys');
        //var_dump($commercial_id_user);

        $commercial_d = new Musers();
        $commercial_d->id_user = $commercial_id_user;
        $commercial_d->get_user();
        $service_comm = $commercial_d->g('service');
        //var_dump($service_comm);

        if (in_array($service_comm, array(1, 3))) {
                null;
        } 
        else{
            $sql = "SELECT id FROM objectif_mensuels WHERE id_commercial = $id_commercial AND annee = $year  AND mois = $month";
            $id_objectif = $db->QuerySingleValue0($sql);
            if($id_objectif == '0')
            {
                $this->log .="</br>Impossible de trouver le ID d'Objectif mensuel de commercial $id_commercial ";
                return false;
            }
            if($test == 1){
                $objectif = new Mobjectif_mensuel();
                if(!$objectif->auto_update_realise_objectif_mensuel($id_objectif, $realise, $this->devis_info['id']))
                {
                    $this->log .="</br>Impossible d'ajouter la réalisation au objectif $id_objectif ";
                    return false;
                }
            }  

        }
        // FIN FZ le 27/03/2020
                      
        } 
        return true;       
        /*=====  End of test if all commercial have objectif  ======*/       
    }

    /**
     * [validdevisclient_devis Action valid client]
     *  'creat_devis' => string '0' (length=1)
      'valid_devis' => string '1' (length=1)
      'send_devis' => string '2' (length=1)
      'modif_client' => string '3' (length=1)
      'valid_client' => string '4' (length=1)
      'refus_client' => string '5' (length=1)
      'devis_expir' => string '6' (length=1)
      'devis_archive' => string '7' (length=1)
     * @return [type] [update etat depend the choise of action]
     */
    public function validdevisclient_devis() {
        
        $this->get_devis();
        
        if(!MInit::compare_date($this->devis_info['date_devis'], $this->_data['date_valid_client']))
        {
            $this->log .= '</br>La date de validation doit être égale ou plus que la date d\'enregistrement';
            return false;
        }
        $reponse = $this->_data['reponse'];        
        $ref_bc = null;
        switch ($reponse) {
            case 'valid':
                $etat = 'valid_client';
                $ref_bc = " , ref_bc = " . MySQL::SQLValue($this->_data['ref_bc']);
                $message = "Validation client";
                break;
            case 'modif':
                $etat = 'modif_client';
                $message = "Demande modification devis";
                break;
            case 'refus':
                $etat = 'refus_client';
                $message = "Refus devis par le client";
                break;
            default:
                # code...
                break;
        }
        
        $id_client = $this->devis_info['id_client'];
        if ($etat == 'valid_client' and ! $this->check_client_temp($id_client)) {
            $this->error = false;
            var_dump('test1');
            return false;
        }

        if ($this->g('type_devis') == 'VNT' && $reponse == 'valid') {
            $this->loop_check_qte();
            if ($id_bl = $this->generate_bl($this->id_devis)) {
                $this->insert_d_bl($id_bl);
            }
            $this->check_livraison();
        }
        
        if($reponse == 'valid' && !$this->insert_realise_into_objectif_mensuel(1)){
                        var_dump('test1');
            return false;
        }
        global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;
        if ($this->g('type_devis') == 'VNT' && $reponse == 'valid') {
            $new_etat = 'etat = etat';
        } else {
            $new_etat = 'etat = ' . Msetting::get_set('etat_devis', $etat);
        }
        var_dump($etat);
        //$new_etat = $this->g('type_devis') == 'VNT' ? 'etat = etat' : 'etat = '.Msetting::get_set('etat_devis', $etat) ;
        if ($etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => $etat";
            return false;
        }
        
        $date_valid_client = MySQL::SQLValue($this->_data['date_valid_client'], 'date');
        
        $req_sql = " UPDATE $table SET  $new_etat, date_valid_client = $date_valid_client  $ref_bc WHERE id = $id_devis ";
var_dump($req_sql);
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération ";
            return false;
        } else {
            //log
            if (!Mlog::log_exec($table, $this->id_devis, $message . ' #Devis:' . $this->id_devis, 'Update')) {
                $this->log .= '</br>Un problème de log ';
            }
            $this->last_id = $this->_data['id'];
            $this->save_file('scan', 'PJ réponse devis ' . $this->_data['id'], 'Document');
            if ($this->g('type_devis') == 'VNT' && $reponse == 'valid') {
                $id_bl = 'id=' . $id_bl;
                $task = MInit::crypt_tp('task', 'detailbl');


                $this->log .= '</br>Opération réussie ' . '<a left_menu="1" class="fa-double_angle_right this_url_jump" rel="seturl" title="Detail BL" data="' . $id_bl . '&' . $task . '"><b> : Détail Bon de livraison</a>';
            } else {
                $this->log .= '</br>Opération réussie: <b> ' . $message;
            }
var_dump('fin');
            //$this->get_devis();
            //If TYPE Devis is VNT then Generate Facture
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function devislivraison_devis() {
        $this->get_devis();
        if ($this->g('type_devis') == 'VNT') {
            $this->loop_check_qte(false);



            if ($id_bl = $this->generate_bl($this->id_devis)) {
                $this->insert_d_bl($id_bl);
            }
            $this->check_livraison();
            $id_bl = 'id=' . $id_bl;
            $task = MInit::crypt_tp('task', 'detailbl');
            $this->log .= '</br>Opération réussie ' . '<a left_menu="1" class="fa-double_angle_right this_url_jump" rel="seturl" title="Detail BL" data="' . $id_bl . '&' . $task . '"><b> : Détail Bon de livraison</a>';
        }
        //$this->get_devis();

        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    private function loop_check_qte($first = true) {

        $data_d_bl = $this->_data['line_d_d'];
        $id_devis = $this->id_devis;
        $creusr = session::get('userid');
        $count_lines = count($data_d_bl);
        $all_liv_qte = 0;
        for ($i = 0, $c = $count_lines; $i < $c; $i++) {

            $id_line = $data_d_bl[$i];
            $qte_liv = MReq::tp('qte_liv_' . $id_line);
            $id_produit = MReq::tp('id_produit_' . $id_line);
            $this->check_qte_by_product($id_produit, $qte_liv, $first);
            $all_liv_qte += $qte_liv;
        }
        if ($all_liv_qte == 0) {
            exit('0#Impossible de génerer un BL sans quantité à livrer');
        }
        return true;
    }

    public function check_exist_no_validate_bl($etat = 0) {
        global $db;
        $id_devis = $this->id_devis;
        $req_sql = " SELECT bl.reference FROM bl WHERE bl.iddevis = $id_devis AND etat = $etat ";
        $count = $db->QuerySingleValue0($req_sql);

        if ($count != '0') {

            $result = '<div class="alert alert-danger">Impossible de gènerer nouveau BL, il faut valider BL Réf: <b>#' . $count . '</b> Bon de Livraison pour le même devis.</div>';
            print($result);
            exit();
        }
    }

    private function check_qte_by_product($id_produit, $qte_input, $first = true) {
        global $db;
        $id_devis = $this->id_devis;
        if ($first) {
            $req_check_produit = "SELECT  d_devis.ref_produit,  d_devis.designation,  d_devis.id_produit AS produit
                ,d_devis.qte AS qte_c,  qte_actuel.`qte_act` AS stock, produits.idtype
                FROM d_devis, qte_actuel, produits WHERE  d_devis.id_devis = $id_devis AND
                qte_actuel.id_produit = d_devis.id_produit AND  d_devis.id_produit = $id_produit AND produits.id =  d_devis.id_produit ";
        } else {


            $req_check_produit = "SELECT  d_devis.ref_produit,  d_devis.designation, d_devis.qte AS qte_c,
            qte_actuel.qte_act AS  stock, produits.idtype,
            ( SELECT IFNULL(SUM(d_bl.qte),0) FROM d_bl, bl
            WHERE d_devis.id_devis = bl.iddevis AND d_devis.id_devis = bl.iddevis AND d_bl.id_bl = bl.id
            AND d_devis.id_produit = d_bl.id_produit ) AS qte_dej_liv,
            d_devis.qte - ( SELECT IFNULL(SUM(d_bl.qte),0)  FROM d_bl, bl
            WHERE d_devis.id_devis = bl.iddevis AND d_devis.id_devis = bl.iddevis AND d_bl.id_bl = bl.id
            AND d_devis.id_produit = d_bl.id_produit )  AS qte_l, d_devis.qte - ( SELECT IFNULL(SUM(d_bl.qte),0) FROM d_bl, bl
            WHERE d_devis.id_devis = bl.iddevis  AND d_devis.id_devis = bl.iddevis AND d_bl.id_bl = bl.id
            AND d_devis.id_produit = d_bl.id_produit ) AS qte_rest
            FROM d_devis, qte_actuel, d_bl, bl , produits
            WHERE d_devis.id_devis = $id_devis AND qte_actuel.id_produit = d_devis.id_produit
            AND  d_devis.id_produit = $id_produit AND produits.id =  d_devis.id_produit   GROUP BY d_devis.id_produit ";
        }



        if (!$db->Query($req_check_produit)) {
            $this->log .= '</br>Erreur Check Qte by product';
            exit('0#' . $this->log);
            return false;
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé xx ' . $req_check_produit;
            } else {

                $arr_qte = $db->RowArray();
                if ($arr_qte['idtype'] == 2) {
                    return true;
                }
                $produit = $arr_qte['ref_produit'];
                if ($arr_qte['stock'] < $qte_input) {

                    $this->log .= "</br>L'article <b>$produit</b> dépasse la Qte en Stock !";
                    exit('0#' . $this->log);
                }
                if ($arr_qte['qte_c'] < $qte_input) {

                    $this->log .= "</br>L'article <b>$produit</b> dépasse la Qte en commandée !";
                    exit('0#' . $this->log);
                }

                if (!$first) {
                    if ($arr_qte['qte_c'] < ($arr_qte['qte_dej_liv'] + $qte_input)) {

                        $this->log .= "</br>L'article <b>$produit</b> dépasse la Qte commandée !";
                        exit('0#' . $this->log);
                    }
                }
                return true;
            }
        }
    }

    Private function check_livraison() {
        global $db;
        $id_devis = $this->id_devis;
        $table = $this->table;

        $req_check_livr = "SELECT (SELECT SUM(qte) FROM d_devis WHERE id_devis = $id_devis ) -
        (SELECT SUM(b.qte) FROM  d_bl b,bl WHERE  bl.iddevis = $id_devis  AND b.id_bl = bl.id  ) AS rest;";
        $result = $db->QuerySingleValue0($req_check_livr);

        if ($result == 0) {
            $etat_devis = 'valid_client';
        } else {
            $etat_devis = 'devis_livr';
        }
        $new_etat = Msetting::get_set('etat_devis', $etat_devis);
        $req_sql = " UPDATE $table SET etat = $new_etat  WHERE id = $id_devis ";

        if (!$db->Query($req_sql)) {
            $this->log .= '</br>Erreur MAJ devis après BL' . $req_sql;
            $this->error = false;
            return false;
        }
        return true;
    }

    Private function generate_bl($id_devis) {

        global $db;
        $ref_bl = $db->Generate_reference('bl', 'BL');
        $sql_req = "INSERT INTO bl (reference, client, projet, ref_bc, iddevis, date_bl, creusr, credat)
            SELECT '$ref_bl', c.denomination, d.projet, d.ref_bc,
            d.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
            FROM clients c, devis d  WHERE d.id_client=c.id  AND d.id = $id_devis;";
        if (!$new_id_bl = $db->Query($sql_req)) {
            $this->log .= '</br>Erreur génération de Bon Livraison' . $sql_req;
            $this->error = false;
            return false;
        }
        return $new_id_bl;
    }

    Private function insert_d_bl($id_bl) {
        global $db;
        $data_d_bl = $this->_data['line_d_d'];
        $id_devis = $this->id_devis;
        $creusr = session::get('userid');
        $count_lines = count($data_d_bl);

        for ($i = 0, $c = $count_lines; $i < $c; $i++) {



            $id_line = $data_d_bl[$i];
            $qte_liv = MReq::tp('qte_liv_' . $id_line);
            $id_produit = MReq::tp('id_produit_' . $id_line);

            $sql_req_d_bl = "  INSERT INTO d_bl (`order`, id_bl, id_produit, ref_produit, designation, qte, creusr, credat)
                SELECT d.order, '$id_bl' , id_produit, ref_produit, designation, '$qte_liv', '$creusr', CURRENT_TIMESTAMP FROM d_devis d WHERE d.id_devis= $id_devis AND d.id_produit = $id_produit ";
            if ($qte_liv > 0) {
                if (!$db->Query($sql_req_d_bl)) {
                    $this->log .= '</br>Erreur Insértion linge ' . $id_line . ' Produit:' . $id_produit . '  ' . $sql_req_d_bl;
                    $this->error = false;
                    return false;
                }
            }
        }


        return true;
    }

    Private function generate_facture($id_devis) {
        global $db;
        $sql_req = " CALL generate_devis_fact($id_devis)";
        if (!$db->Query($sql_req)) {
            $this->log .= '</br>Erreur génération de facture' . $sql_req;
        }
    }

    /**
     * [generate_devis_pdf Generate PDF for store and send to client]
     * @return [type] [description]
     */
    Private function generate_devis_pdf() {
        $file_tplt = MPATH_THEMES . 'pdf_template/devis_pdf.php';
        $saf_ref = str_replace('/', '_', $this->g('reference'));
        $file_export = MPATH_TEMP . '#' . $saf_ref . '.pdf';

        $qr_code = true;
        include_once $file_tplt;
        //Format all parameteres
        $temp_file = $file_export;
        //If nofile uploaded return kill function

        if ($temp_file == Null) {
            return true;
        }

        $new_name_file = $this->g('id') . '_' . $saf_ref;
        $folder = MPATH_UPLOAD . SLASH . 'devis/mois_' . date('m_Y') . SLASH . $this->g('id');
        $id_line = $this->g('id');
        $title = 'Devis ' . $this->g('id') . ' #' . $this->g('reference');
        $table = $this->table;
        $column = 'devis_pdf';
        $type = 'Document';



        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'devis', $table, $column, $type, $edit = null)) {
            $this->error = false;
            $this->log .= '</br>Enregistrement ' . $item . ' dans BD non réussie';
        }
        $this->attached = $folder . SLASH . $new_name_file . '.pdf';
    }

    /**
     * [senddevis_to_client Basclly send devis to client and generat PDF]
     * @param [type] $etat [description]
     */
    public function senddevis_to_client($etat) {
        //Send to client
        $old_etat = Msetting::get_set('etat_devis', 'valid_devis');
        if ($old_etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis";
            return false;
        }
        if ($etat != $old_etat) {
            $this->error = false;
            $this->log .= "Ce devis ne peux pas être envoyé pour le momement #Etat faild";
            return false;
        }
        global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;

        $new_etat = Msetting::get_set('etat_devis', 'send_devis');

        if ($new_etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => send_devis";
            return false;
        }
        $this->generate_devis_pdf();
        $req_sql = " UPDATE $table SET etat = $new_etat WHERE id = $id_devis ";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération";
            return false;
        }

        if (Msetting::get_set('send_mail_devis') == true) {

            $this->send_devis_mail();
        }
        //log
        if (!Mlog::log_exec($table, $this->id_devis, 'Expédition devis ' . $this->id_devis, 'Update')) {
            $this->log .= '</br>Un problème de log ';
        }
        $this->log .= "<br/>Expédition réussie";
        return true;
    }

    /**
     * [valid_devis valider devis to prepare to send to client]
     * @param  [int] $etat [etat of devis must be 0]
     * @return [type]       [description]
     */
    public function valid_devis($etat) {
        $old_etat = Msetting::get_set('etat_devis', 'creat_devis');
        $old_etat_dg = Msetting::get_set('etat_devis', 'valid_devis_dg');
        $old_etat_dcm = Msetting::get_set('etat_devis', 'valid_devis_dcm');

        if ($old_etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => creat_devis";
            return false;
        }

        if ($old_etat_dg == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis_dg";
            return false;
        }
        if ($old_etat_dcm == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis_dcm";
            return false;
        }
        if ($etat != $old_etat AND $etat != $old_etat_dg AND $etat != $old_etat_dcm) {
            $this->error = false;
            $this->log .= "Ce devis ne peux pas être validé //Etat faild";
            return false;
        }
        global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;

        $new_etat = Msetting::get_set('etat_devis', 'valid_devis');

        if ($new_etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis";
            return false;
        }
        $req_sql = " UPDATE $table SET etat = $new_etat WHERE id = $id_devis ";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération";
            return false;
        }
        //log
        if (!Mlog::log_exec($table, $this->id_devis, 'Validation devis ' . $this->id_devis, 'Update')) {
            $this->log .= '</br>Un problème de log ';
        }
        $this->log .= "<br/>Opération réussie";
        $this->get_devis();

        //$this->send_valid_devis_mail();

        return true;
    }

    /**
     * [debloqdevis description]
     * @param  [type] $etat [description]
     * @return [type]       [description]
     */
    public function debloqdevis($etat) {
        $old_etat = Msetting::get_set('etat_devis', 'modif_client');
        $new_etat_dg = Msetting::get_set('etat_devis', 'valid_devis_dg');

        if ($old_etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => modif_client";
            return false;
        }
        if ($new_etat_dg == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis_dg";
            return false;
        }
        if ($etat != $old_etat) {
            $this->error = false;
            $this->log .= "Ce devis ne peux pas être débloqué //Etat faild";
            return false;
        }
        $new_etat = Msetting::get_set('etat_devis', 'creat_devis');
        if ($new_etat == null) {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis";
            return false;
        }
        $montant_ttc = $this->devis_info['totalttc'];
        $plafond_validate_dg = Msetting::get_set('plafond_valid_dg');

        if ($plafond_validate_dg < $montant_ttc) {
            $new_etat = $new_etat_dg;
        }


        global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;
        $doc = $this->g('devis_pdf');

        $req_sql = " UPDATE $table SET etat = $new_etat, devis_pdf = NULL WHERE id = $id_devis ";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération";
            return false;
        }
        //Delete file form archive table
        $db->Query("DELETE FROM archive WHERE doc = $doc ");
        $this->log .= "<br/>Opération réussie";
        //log
        if (!Mlog::log_exec($table, $this->id_devis, 'Débloquer devis ' . $this->id_devis, 'Update')) {
            $this->log .= '</br>Un problème de log ';
        }
        return true;
    }

    public function Delete_detail_devis($id_detail) {
        global $db;
        $table_details = $this->table_details;
        $get_tkn_frm = $db->QuerySingleValue0("SELECT tkn_frm FROM $table_details  WHERE id = $id_detail");

        //Format where clause
        $where['id'] = MySQL::SQLValue($id_detail);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows($table_details, $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('devis', $where);
            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Suppression réussie ';
            $this->Get_sum_detail($get_tkn_frm);
            $this->log .= '#' . $this->sum_total_ht;
            //log if is edit main devis
            if ($this->devis_d_info['id_devis'] != null) {
                if (!Mlog::log_exec($table, $this->id_devis, 'Suppression détail devis ' . $this->devis_d_info['id_devis'], 'Delete')) {
                    $this->log .= '</br>Un problème de log ';
                }
            }
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    // afficher les infos d'un devis
    public function s($key) {
        if ($this->devis_info[$key] != null) {
            echo $this->devis_info[$key];
        } else {
            echo "";
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

    // afficher les infos d'un devis_d
    public function c($key) {
        if ($this->devis_d_info[$key] != null) {
            echo $this->devis_d_info[$key];
        } else {
            echo "";
        }
    }

    //get les infos d'un devis_d
    public function h($key) {
        if ($this->devis_d_info[$key] != null) {
            return $this->devis_d_info[$key];
        } else {
            return null;
        }
    }

    public function archivedevis() {
        global $db;
        $id_devis = $this->id_devis;
        $table = $this->table;
        $this->get_devis();
        //Format where clause
        $id_devis = MySQL::SQLValue($id_devis);
        $sql_req = "UPDATE $table SET etat = 100 WHERE id = $id_devis";

        //check if id on where clause isset
        if ($id_devis == null) {
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
            if (!Mlog::log_exec($table, $this->id_devis, 'Archivage devis ' . $this->id_devis, 'Update')) {
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

    public function delete_devis() {
        global $db;
        $id_devis = $this->id_devis;
        $table = $this->table;
        $this->get_devis();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_devis);
        //check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
        //execute Delete Query
        if (!$db->DeleteRows($table, $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('devis', $where);
            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Suppression réussie ';
            //log
            if (!Mlog::log_exec($table, $this->id_devis, 'Suppression devis ' . $this->id_devis, 'Delete')) {
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

    /**
     * [get_info_produit description] Get all product info
     * @param  [type] $id_produit [description]
     * @return [type]             [description]
     * @return array converted to JSON incontroller
     */
    public function get_info_produit($id_produit) {
        global $db;
        $req_sql = "SELECT
        produits.designation,
        produits.reference,
        produits.idtype,
        produits.prix_vente AS prix_vente,
        ref_unites_vente.unite_vente,
        ref_types_produits.type_produit, ref_types_produits.check_stock,
        IFNULL((SELECT MAX(d_devis.prix_ht) FROM d_devis WHERE d_devis.id_produit = $id_produit), 0) AS prix_vendu,
        IFNULL(SUM(stock.qte), 0) AS qte_in_stock,
        IFNULL(
        (SELECT
        SUM(d_devis.qte)
        FROM
        d_devis,
        devis
        WHERE d_devis.id_produit = produits.id
        AND d_devis.id_devis = devis.id
        AND devis.etat = 1),
        0
        ) AS qte_comand
        FROM
        stock
        INNER JOIN produits
        ON (
        stock.idproduit = produits.id
        )
        INNER JOIN ref_types_produits
        ON (
        produits.idtype = ref_types_produits.id
        )
        INNER JOIN ref_unites_vente
        ON (
        produits.iduv = ref_unites_vente.id
        )

        WHERE produits.id = $id_produit ";

        if (!$db->Query($req_sql)) {

            $this->arr_prduit = array('error' => "Erreur get product info");

            return false;
        } else {

            $this->arr_prduit = $db->RowArray();
            if ($this->arr_prduit['type_produit'] == 'Abonnement') {
                $this->arr_prduit['abn'] = true;
            }
            if ($this->arr_prduit['prix_vente'] == null) {
                $this->arr_prduit = array('error' => "Prix de produit n'est pas enregitré");
            }
            if ($this->arr_prduit['check_stock'] == 'Y') {
                $this->arr_prduit['qte_dispo'] = ' / Qte disponible : ' . $this->arr_prduit['qte_in_stock'] . ' ' . $this->arr_prduit['unite_vente'];
            } else {
                $this->arr_prduit['qte_dispo'] = '';
            }
        }
        return true;
    }

    public function Gettable_detail_product_livraison() {
        global $db;
        $table = $this->table_details;

        $input_qte_c = "CONCAT('<input type=\"hidden\" name=\"line_d_d[]\" value=\"',$table.id,'\"/><input type=\"hidden\" name=\"id_produit_',$table.id,'\" value=\"',$table.id_produit,'\"/>
        <span id=\"qte_',$table.id_produit,'\" tp=\"',produits.idtype,'\" class=\"badge badge-info\">',$table.qte,'</span>') as qte_c";
        $input_qte_l = "CONCAT('<input id=\"liv_',$table.id_produit,'\" class=\"liv center  is-number\" name=\"qte_liv_',$table.id,'\" type=\"text\" value=\"',$table.qte,'\"/>') as qte_l";
        $etat_stock = "CASE WHEN d_devis.qte > qte_actuel.`qte_act` AND produits.`idtype` <> 2 THEN
  CONCAT('<span id=\"stok_',$table.id_produit,'\" class=\"badge badge-danger\">', qte_actuel.`qte_act`,'</span>')
   WHEN d_devis.qte <= qte_actuel.`qte_act` AND produits.`idtype` <> 2 THEN
    CONCAT('<span id=\"stok_',$table.id_produit,'\" class=\"badge badge-success\">', qte_actuel.`qte_act`,'</span>')
    ELSE 'NA' END AS stock";
        $appro_stock = "CASE WHEN d_devis.qte > qte_actuel.`qte_act` AND produits.`idtype` <> 2 THEN
  CONCAT('</span>','<a id=\"appro_stok_',$table.id_produit,'\" class=\"btn btn-white btn-info btn-sm appro_stock\" data=\"',$table.id_produit,'\"><i class=\"ace-icon fa fa-plus bigger-120 blue\"></i>','</a>')
   ELSE  '-' END AS appro_stock";
        $id_devis = $this->id_devis;

        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";

        $colms .= " $input_qte_c, ";

        $colms .= " $etat_stock, ";
        $colms .= " $appro_stock, ";
        $colms .= " $input_qte_l";


        $req_sql = " SELECT $colms FROM $table, qte_actuel, produits WHERE d_devis.id_produit = qte_actuel.id_produit AND id_devis = $id_devis AND produits.id = d_devis.id_produit ";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            ini_set('xdebug.var_display_max_data', -1);
           // var_dump($this->log);
        }


        $headers = array(
            'Item' => '5[#]center',
            'Réf' => '10[#]center',
            'Description' => '30[#]',
            'Qte commandée' => '15[#]center',
            'En Stock' => '15[#]center',
            'Appro' => '10[#]center',
            'Qte à livrer' => '15[#]center',
        );


        $tableau = $db->GetMTable($headers);


        return $tableau;
    }

    public function Gettable_detail_product_add_livraison() {
        global $db;
        $table = $this->table_details;
        $input_qte_c = "CONCAT('<input type=\"hidden\" name=\"line_d_d[]\" value=\"',$table.id,'\"/><input type=\"hidden\" name=\"id_produit_',$table.id,'\" value=\"',$table.id_produit,'\"/>
        <span id=\"qte_',$table.id_produit,'\" class=\"badge badge-info\">',$table.qte,'</span>') as qte_c";
        $input_qte_l = "CONCAT('<input id=\"liv_',$table.id_produit,'\" class=\"liv center  is-number\" name=\"qte_liv_',$table.id,'\" type=\"text\" value=\"',$table.`qte` - SUM(d_bl.qte),'\"/>') as qte_l";
        $etat_stock = "CASE WHEN d_devis.qte > qte_actuel.`qte_act` THEN
  CONCAT('<span id=\"stok_',$table.id_produit,'\" class=\"badge badge-danger\">', qte_actuel.`qte_act`,'</span>')
   ELSE  CONCAT('<span id=\"stok_',$table.id_produit,'\" class=\"badge badge-success\">', qte_actuel.`qte_act`,'</span>') END AS stock";
        $id_devis = $this->id_devis;

        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " $input_qte_c, ";
        $colms .= " $etat_stock, ";
        $colms .= " CONCAT('<span id=\"qte_dej_liv_',$table.id_produit,'\" class=\"badge badge-warrning\">',SUM(d_bl.qte),'</span>') AS qte_dej_liv, ";
        $colms .= " $input_qte_l,";
        $colms .= " $table.`qte` - SUM(d_bl.qte) as qte_rest ";

        $req_sql = "SELECT  d_devis.order item,  d_devis.ref_produit,  d_devis.designation,
            CONCAT('<input name=\"line_d_d[]\" value=\"',d_devis.id,'\" type=\"hidden\"><input name=\"id_produit_',
            d_devis.id,'\" value=\"',d_devis.id_produit,'\" type=\"hidden\"><span id=\"qte_',d_devis.id_produit,
            '\" class=\"badge badge-info\">',d_devis.qte,'</span>') AS qte_c,
            CASE WHEN d_devis.qte > qte_actuel.`qte_act` THEN
            CONCAT('<span id=\"stok_',d_devis.id_produit,'\" class=\"badge badge-danger\">', qte_actuel.`qte_act`,'</span>')
            ELSE  CONCAT('<span id=\"stok_',d_devis.id_produit,'\" class=\"badge badge-success\">', qte_actuel.`qte_act`,'</span>') END AS             stock,
            CONCAT('<span id=\"qte_dej_liv_',d_devis.id_produit,'\" class=\"badge badge-warrning\">',
            ( SELECT IFNULL(SUM(d_bl.qte),0) FROM d_bl, bl
            WHERE d_devis.id_devis = bl.iddevis AND d_devis.id_devis = bl.iddevis AND d_bl.id_bl = bl.id
            AND d_devis.id_produit = d_bl.id_produit ),'</span>') AS qte_dej_liv,
            CONCAT('<input id=\"liv_',d_devis.id_produit,'\" class=\"liv center  is-number\" name=\"qte_liv_',d_devis.id,'\" value=\"',
            d_devis.`qte` - ( SELECT IFNULL(SUM(d_bl.qte),0)  FROM d_bl, bl
            WHERE d_devis.id_devis = bl.iddevis AND d_devis.id_devis = bl.iddevis AND d_bl.id_bl = bl.id
            AND d_devis.id_produit = d_bl.id_produit ) ,'\" type=\"text\">') AS qte_l, d_devis.`qte` - ( SELECT IFNULL(SUM(d_bl.qte),0)              FROM d_bl, bl
            WHERE d_devis.id_devis = bl.iddevis  AND d_devis.id_devis = bl.iddevis AND d_bl.id_bl = bl.id
            AND d_devis.id_produit = d_bl.id_produit ) AS qte_rest
            FROM d_devis, qte_actuel, bl
            WHERE d_devis.id_devis = $id_devis AND qte_actuel.id_produit = d_devis.id_produit
            GROUP BY d_devis.id_produit HAVING qte_rest > 0 ORDER BY item ";
            

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
        }


        $headers = array(
            'Item' => '5[#]center',
            'Réf' => '10[#]center',
            'Description' => '30[#]',
            'Qte commandée' => '15[#]center',
            'En Stock' => '15[#]center',
            'Qte déjà livrée' => '15[#]center',
            'Qte à livrer' => '15[#]center',
            'Qte_rest' => '15[#]hidden',
        );


        $tableau = $db->GetMTable($headers);


        return $tableau;
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
        $folder = MPATH_UPLOAD . SLASH . 'devis/mois_' . date('m_Y') . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;



        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'devis', $table, $column, $type, $edit = null)) {
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

    /**
     * [send_devis_mail Send email to client if have email adresse]
     * @return [bol] [fil log]
     */
    private function send_devis_mail() {
        //Get info devis
        $this->Get_detail_devis_show();
        $devis_info = $this->devis_info;
        if ($this->g('email') == null) {
            $this->log .= '<br/>Ce client n\'a pas une adresse Mail';
            return false;
        }
        //prepare all variables
        $ste_c = new MSte_info();
        $ste = $ste_c->get_ste_info_report_footer(1, $this->g('id_banque'));

        $agent = new Musers();
        $agent->id_user = session::get('userid');
        $agent->get_user();

        $agent_name = $agent->g('fnom') . ' ' . $agent->g('lnom');
        $agent_service = $agent->g('service_user');
        $agent_tel = $agent->g('tel');

        date_default_timezone_set('Etc/UTC');
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        $mail->Username = Msetting::get_set('mail_comercial', 'user');
        $mail->Password = Msetting::get_set('mail_comercial', 'pass');

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        $mail->addAddress($this->g('email'), $this->g('denomination'));
        if ($this->attached != null) {
            $mail->addAttachment($this->attached, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment');
        }

        //Set the subject line
        $mail->Subject = 'Devis Réf: #' . $this->g('reference');
        //Read an HTML message body from an external file, convert referenced images to embedded,
        $Msg_body = file_get_contents(MPATH_MSG . 'template_send_devis.php');
        $Msg_body = str_replace(
                array('%ste_footer%', '%agent%', '%service%', '%agenttel%'), array($ste, $agent_name, $agent_service, $agent_tel), $Msg_body);
        $mail->msgHTML($Msg_body);

        if (!$mail->send()) {
            $this->log .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $this->log .= "Devis envoyé  à " . $this->g('email');
        }
    }

    static public function get_client_name($id_client) {
        global $db;
        $sql = "SELECT denomination FROM clients WHERE id = $id_client";
        $denomination = $db->QuerySingleValue($sql);
        return $denomination;
    }

    public function save_new_client_temp() {
        global $db;

        if (!$reference = $db->Generate_reference('clients', 'CLT')) {
            $this->log .= '</br>Problème Réference';
            return false;
        }
        $values["reference"] = MySQL::SQLValue($reference);
        $values["denomination"] = MySQL::SQLValue($this->_data['denomination']);
        $values["type_client"] = MySQL::SQLValue('T');
        $values["adresse"] = MySQL::SQLValue($this->_data['adresse']);
        $values["tel"] = MySQL::SQLValue($this->_data['tel']);
        $values["email"] = MySQL::SQLValue($this->_data['email']);
        $values["creusr"] = MySQL::SQLValue(session::get('userid'));


        //Check if Insert Query been executed (False / True)
        if (!$result = $db->InsertRow("clients", $values)) {
            //False => Set $this->log and $this->error = false
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '</br>Enregistrement BD non réussie';
        } else {
            $this->info_temp_client = array('mess' => 'Client ' . $this->_data['denomination'] . ' a était ajouté', 'nom' => $this->_data['denomination'], 'id' => $result);
            $this->error = true;
        }

        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function duplicate_devis() {
        global $db;
        //Get All devis to be duplicate
        $table = $this->table;
        $table_details = $this->table_details;
        global $db;
        $ssid = 'duplicat_devis';
        session::clear($ssid);
        session::set($ssid, session::generate_sid());
        $verif_value = md5(session::get($ssid));
        $curent_usr = session::get('userid');
        //Generate reference
        if (!$reference = $db->Generate_reference($this->table, 'DEV')) {
            $this->log .= '</br>Problème Réference new devis';
            return false;
        }
        $date_devis = date('Y-m-d');


        $old_devis_id = $this->id_devis;
        //Insert duplicated Devis
        $all_fields_f = "`tkn_frm`, `type_devis`,
                `projet`, `id_client`, `id_devise`, `id_banque`, `commission`, `total_commission`, `tva`, `id_commercial`, `reference`,
                `date_devis`, `type_remise`, `total_remise`, `valeur_remise`, `totalht`, `totalttc`,
                `totaltva`, `vie`, `claus_comercial`, `ref_bc`, `scan`, `devis_pdf`, `etat`, `creusr`";

        $all_fields_v = "'$verif_value', `type_devis`,
                `projet`, `id_client`, `id_devise`, `id_banque`, `commission`, `total_commission`, `tva`, `id_commercial`, '$reference',
                '$date_devis', `type_remise`, `total_remise`, `valeur_remise`, `totalht`, `totalttc`,
                `totaltva`, `vie`, `claus_comercial`, `ref_bc`, `scan`, `devis_pdf`, 0, '$curent_usr' ";

        $sql_duplicat_devis = "INSERT INTO $table ($all_fields_f) SELECT  $all_fields_v FROM $table WHERE $table.id = $old_devis_id";

        if (!$new_devis = $db->Query($sql_duplicat_devis)) {
            $this->log .= "</br>Problème Insert devis";
            return false;
        }

        //Insert duplicated detail devis
        $all_fields_d_f = "`order`, `id_devis`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `taux_change`, `qte`, `pu_devise_pays`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`";
        $all_fields_d_v = "`order`, '$new_devis', '$verif_value', `id_produit`, `ref_produit`, `designation`, `taux_change`, `qte`, `pu_devise_pays`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, '$curent_usr'";

        $sql_duplicat_devis_d = "INSERT INTO $table_details ($all_fields_d_f) SELECT  $all_fields_d_v FROM $table_details WHERE $table_details.id_devis = $old_devis_id";

        if (!$new_devis_d = $db->Query($sql_duplicat_devis_d)) {
            $this->log .= "</br>Problème Insert devis_d";
            return false;
        }

        $cryp_data = MInit::crypt_tp('id', $new_devis);
        $this->log .= "</br>Devis crée avec succès sous réf: <b>$reference</b></br><a class='this_url' rel='editdevis' data='$cryp_data'>Modifier le nouveau Devis</a>";
        return true;
    }

    public function get_list_bl() {
        global $db;

        $add_set = array('return' => '<a href="#" class="report_tplt" rel="' . MInit::crypt_tp('tplt', 'bl') . '" data="%crypt%"> <i class="ace-icon fa fa-print"></i></a>', 'data' => 'id');
        $id_devis = $this->id_devis;
        $req_sql = " SELECT id, reference, date_bl, '#' FROM bl WHERE iddevis = $id_devis ";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
        }
        if (!$db->RowCount()) {
            $output = '<div class="alert alert-danger">Pas de Bons de Livraison enregistrés pour ce devis</div>';
            return $output;
        }


        $headers = array(
            'ID' => '5[#]center',
            'Référence' => '10[#]center',
            'Date création' => '30[#]',
            'Voir détails' => '15[#]center[#]crypt',
        );


        $tableau = $db->GetMTable($headers, $add_set);
        return $tableau;
    }

    public function get_list_factures() {
        global $db;

        $add_set = array('return' => '<a href="#" class="report_tplt" rel="' . MInit::crypt_tp('tplt', 'facture') . '" data="%crypt%"> <i class="ace-icon fa fa-print"></i></a>', 'data' => 'id');
        $id_devis = $this->id_devis;
        $req_sql = "SELECT factures.id, factures.reference,DATE_FORMAT(factures.date_facture,'%d-%m-%Y') as datfact, REPLACE(FORMAT(factures.total_ht,0),',',' ') total_ht, REPLACE(FORMAT(factures.total_ttc,0),',',' ') total_ttc, REPLACE(FORMAT(factures.total_tva,0),',',' ') as total_tva, REPLACE(FORMAT(factures.total_paye,0),',',' ') as total_paye, REPLACE(FORMAT(factures.reste,0),',',' ') as total_reste, '#' FROM factures WHERE  factures.iddevis = $id_devis   ORDER BY factures.credat DESC";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
        }
        if (!$db->RowCount()) {
            $output = '<div class="alert alert-danger">Pas de Factures enregistrés pour ce devis</div>';
            return $output;
        }


        $headers = array(
            'ID' => '5[#]center',
            'Référence' => '10[#]center',
            'Date création' => '10[#]',
            'Total HT' => '10[#]alignRight',
            'Total TTC' => '10[#]alignRight',
            'Total TVA' => '10[#]alignRight',
            'Payé' => '10[#]alignRight',
            'Rest' => '10[#]alignRight',
            'Voir détails' => '5[#]center[#]crypt',
        );


        $tableau = $db->GetMTable($headers, $add_set);
        return $tableau;
    }

    public function get_list_encaissement() {
        global $db;

        $add_set = array('return' => '<a href="#" class="this_url" rel="detailsencaissement" data="%crypt%"> <i class="ace-icon fa fa-eye blue bigger-100"></i></a>', 'data' => 'id');
        $id_devis = $this->id_devis;
        $req_sql = "SELECT e.id, e.reference, e.designation  ,e.date_encaissement, e.ref_payement, e.mode_payement, IFNULL(REPLACE(FORMAT((e.montant),0),',',' '),0) AS montant ,  '#' FROM encaissements e,factures f,devis d  WHERE  f.id=e.`idfacture` AND f.iddevis = d.id and d.id = $id_devis ORDER BY e.date_encaissement DESC";

        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
        }
        if (!$db->RowCount()) {
            $output = '<div class="alert alert-danger">Pas d\'Encaissement enregistrés pour ce devis</div>';
            return $output;
        }


        $headers = array(
            'ID' => '5[#]center',
            'Référence' => '10[#]center',
            'Designation' => '10[#]center',
            'Date' => '10[#]',
            'Réf paiement' => '10[#]center',
            'Mode' => '10[#]center',
            'Montant' => '10[#]center',
            'Voir détails' => '15[#]center[#]crypt',
        );


        $tableau = $db->GetMTable($headers, $add_set);
        return $tableau;
    }

    static public function indicator_nbr_devis() {

        global $db;
        $id_user = session::get('userid');
        $id_service = session::get('service');
        $where_user = null;
        if (in_array($id_service, array(1, 3, 2))) {
            $where_user = null;
        } elseif ($id_service == 7) {
            $where_user = " AND id_user_sys = $id_user";
        }
        $req_sql = "SELECT COUNT(`devis`.`id`) AS nbr_devis FROM `devis`
        INNER JOIN `commerciaux` ON (`devis`.`id_commercial` LIKE CONCAT('%',`commerciaux`.`id`,'%'))
        INNER JOIN `users_sys` ON (`commerciaux`.`id_user_sys` = `users_sys`.`id`)
        WHERE YEAR(`devis`.date_devis) = YEAR(CURDATE()) $where_user";

        if (!$db->Query($req_sql)) {
            return false;
        } else {
            if (!$db->RowCount()) {
                return false;
            } else {
                $arr_result = $db->RowArray();
            }
        }

        $output = '<div class="col-lg-3 col-6">
                        <div class="small-box btn-success">
                            <div class="inner">
                                <h3>' . $arr_result['nbr_devis'] . '</h3>
                                <p>Devis enregistrés</p>
                            </div>
                        <div class="icon">
                            <i class="ace-icon fa fa-paper-plane home-icon"></i>
                        </div>
                        <a href="#" rel="devis" class="small-box-footer this_url">Voir détails <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>';
        return print($output);
    }

    static public function indicator_nbr_facture_non_paye() {

        global $db;
        $id_user = session::get('userid');
        $id_service = session::get('service');
        $where_user = null;
        if (in_array($id_service, array(1, 3, 2))) {
            $where_user = null;
        } elseif ($id_service == 7) {
            $where_user = " AND id_user_sys = $id_user";
        }
        $req_sql = "SELECT
        COUNT(`factures`.`id`) AS nbr_facture FROM `factures`
        INNER JOIN `devis` ON (`devis`.`id` = `factures`.`iddevis`)
        INNER JOIN `commerciaux` ON (`devis`.`id_commercial` LIKE CONCAT('%',`commerciaux`.`id`,'%'))
        INNER JOIN `users_sys` ON (`commerciaux`.`id_user_sys` = `users_sys`.`id`)
        WHERE  YEAR(`factures`.date_facture) = YEAR(CURDATE()) AND factures.etat IN(3 ,2) $where_user";

        if (!$db->Query($req_sql)) {
            return false;
        } else {
            if (!$db->RowCount()) {
                return false;
            } else {
                $arr_result = $db->RowArray();
            }
        }

        $output = '<div class="col-lg-3 col-6">
                        <div class="small-box btn-red">
                            <div class="inner">
                                <h3>' . $arr_result['nbr_facture'] . '</h3>
                                <p>Facture non payées</p>
                            </div>
                        <div class="icon">
                            <i class="ace-icon fa fa-file home-icon"></i>
                        </div>
                        <a href="#" rel="factures" class="small-box-footer this_url">Voir détails <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>';
        return print($output);
    }

    /**
     * End Class destrector
     */
    public function __destruct() {
        //
    }

    private function send_creat_devis_mail($id_commercial) {
        //Get info devis
        $this->id_devis = $id_commercial;
        $this->get_devis();
        $devis_info = $this->devis_info;

        if ($this->verif_email($devis_info["creusr"]) == FALSE) {
            $this->log .= '<br/>Ce Commercial n\'a pas une adresse Mail';
            return false;
        }

        $commerciale = new Musers();
        $commerciale->id_user = $devis_info["creusr"];
        $commerciale->get_user();
        $agent_name = $commerciale->g('fnom') . ' ' . $commerciale->g('lnom');
        $agent_service = $commerciale->g('service_user');
        $agent_tel = $commerciale->g('tel');

        $mail = new PHPMailer();
        $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP
        $mail->SMTPSecure = 'ssl'; // Accepter SSL

        $mail->setFrom($mail->Username, 'GlobalTech Direction'); // Personnaliser l'envoyeur
        $mail->addAddress($commerciale->g('mail'), $commerciale->g('lnom') . " " . $commerciale->g('fnom'));
        $mail->addCC('commercial@globaltech.td', ' Globaltech DG');


        if (intval($devis_info["totalttc"]) > Msetting::get_set('plafond_valid_dg')) {
            $mail->addCC('contact@globaltech.td', ' Globaltech DG');
        }

        $mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

        $mail->Subject = "Devis Réf: #" . $devis_info['reference'];

        $mail->Body = "<b> Bonjour,"
                . "</br></br> Le devis REF: " . $devis_info['reference'] . " a été crée et il est en attente de validation.
                </br></br> Cordialement</b>";

        if (!$mail->send()) {
            $this->log .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $this->log .= "Mail Création devis envoyé  à " . $commerciale->g('mail');
        }
    }

    private function send_valid_devis_mail() {
        //Get info devis
        $this->get_devis();
        $devis_info = $this->devis_info;

        if ($this->verif_email($devis_info["creusr"]) == FALSE) {
            $this->log .= '<br/>Ce Commercial n\'a pas une adresse Mail';
            return false;
        }

        $commerciale = new Musers();
        $commerciale->id_user = $devis_info["creusr"];
        $commerciale->get_user();
        $agent_name = $commerciale->g('fnom') . ' ' . $commerciale->g('lnom');
        $agent_service = $commerciale->g('service_user');
        $agent_tel = $commerciale->g('tel');

        $mail = new PHPMailer();
        $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP
        $mail->SMTPSecure = 'ssl'; // Accepter SSL

        $mail->setFrom($mail->Username, 'GlobalTech Direction'); // Personnaliser l'envoyeur
        $mail->addAddress($commerciale->g('mail'), $commerciale->g('lnom') . " " . $commerciale->g('fnom'));

        if ($devis_info["totalttc"] > 5000000) {
            $mail->addCC('commercial@globaltech.td', 'DCM');
        }
        $mail->isHTML(true); // Paramétrer le format des emails en HTML ou non
        $mail->Subject = "Devis Réf: #" . $devis_info['reference'];

        $mail->Body = "<b> Bonjour, "
                . ",</br></br> Le devis REF: " . $devis_info['reference'] . " a été validé.
                </br></br> Cordialement</b>";

        if (!$mail->send()) {
            $this->log .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $this->log .= "Mail validation devis envoyé  à " . $commerciale->g('mail');
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

    private function get_commerciale_remise_plafond($id_commercial, $valeur_remise) {
        global $db;
        $etat_valid_dg = Msetting::get_set('etat_devis', 'valid_devis_dg');
        $etat_valid_dcm = Msetting::get_set('etat_devis', 'valid_devis_dcm');
        if ($etat_valid_dg == null) {
            $this->error = false;
            $this->log .= "Manque paramètre valid_devis_dg";
            return false;
        }
        if ($etat_valid_dcm == null) {
            $this->error = false;
            $this->log .= "Manque paramètre valid_devis_dcm";
            return false;
        }

        $req_sql = "SELECT remise, remise_valid_dcm, remise_valid_dg FROM commerciaux WHERE id_user_sys = $id_commercial";
        //var_dump($req_sql);
        if (!$db->Query($req_sql)) {
            $this->log .= '</br>Impossible récuperation plafonds remises #SQL';
            return false;
        } else {
            if (!$db->RowCount()) {
                $this->log .= '</br>Impossible récuperation plafonds remises pour ce commercial';
                return false;
            }
            $arr_result = $db->RowArray();
            $plafond_remise = $arr_result['remise'];
            $plafond_remise_valid_dcm = $arr_result['remise_valid_dcm'];
            $plafond_remise_valid_dg = $arr_result['remise_valid_dg'];
            if ($valeur_remise > $plafond_remise_valid_dg) {
                $this->log .= '</br>La remise appliquée dépasse le plafond autorisé (' . $plafond_remise_valid_dg . '%)';
                return false;
            } elseif ($valeur_remise > $plafond_remise && $valeur_remise <= $plafond_remise_valid_dcm) {
                $this->log .= '</br>La remise appliquée dépasse le plafond autorisé (' . $plafond_remise . '%)</br>Le devis doit être validé par le DCM';
                $this->etat_valid_devis = $etat_valid_dcm;
            } elseif ($valeur_remise > $plafond_remise && $valeur_remise > $plafond_remise_valid_dcm) {
                $this->log .= '</br>La remise appliquée dépasse le plafond autorisé (' . $plafond_remise_valid_dcm . '%)</br>Le devis doit être validé par le DG';
                $this->etat_valid_devis = $etat_valid_dg;
            }
            return true;
        }
    }

    //Vérifie si l'un des commerciaux n'existe pas
    private function check_commercial_exist($commerciaux) {
        /*$commerciauxIds = str_replace('[', '', $values);
        $commerciauxIds = str_replace(']', '', $commerciauxIds);
        $listCommerciauxInconnus = array();
        $nbr = substr_count($values, ',') + 1;*/

        global $db;
        foreach ($commerciaux as $key)
        {
            $sql = "SELECT * FROM commerciaux WHERE commerciaux.id = $key";

            if (!$db->Query($sql)) {
                $this->error = false;
                $this->log .= $db->Error();
            } else {
                if (!$db->RowCount())
                {
                    $this->error = false;
                    $this->log .= '</br>Un élément ne fait pas partie du service commerciale';
                }
            }


        }

    }

    //Fonction pour changer etat - A deplacer dans une classe utilitaire après la rendre générique
   public function change_etat($etat,$tab,$id_ligne) {
       global $db;

       $values["etat"] = MySQL::SQLValue($etat);
       $values["updusr"] = MySQL::SQLValue(session::get('userid'));
       $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
       $wheres = array();

      if($tab == 'devis'){
          $wheres['id'] = $id_ligne;}
     if($tab == 'contrats'){
          $wheres['iddevis'] = $id_ligne;}
       // Execute the update and show error case error
       if (!$result = $db->UpdateRows($tab, $values, $wheres)) {
           $this->log .= '</br>Impossible de changer le statut!';
           $this->log .= '</br>' . $db->Error();
           $this->error = false;
       } else {

           $this->log .= '</br>Statut '.$tab. 'changé!';
           $this->error = true;

                   if(!Mlog::log_exec($this->table, $this->id_produit , 'Changement Etat', 'Validate'))
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
}
