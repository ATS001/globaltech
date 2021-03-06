<?php

//SYS GLOBAL TECH
// Modul: clients => Model


class Mclients {

    private $_data; //data receive from form
    var $table = 'clients'; //Main table of module
    var $last_id; //return last ID after insert command
    var $log = NULL; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $id_client; // Ville ID append when request
    var $token; //user for recovery function
    var $client_info; //Array stock all client info
    var $compte_client_info; //Array stock all mouvements compte info
    var $devis_info; //Array stock all devis info
    var $abn_info; //Array stock all abonnement info
    var $bl_info; //Array stock all bl info
    var $factures_info; //Array stock all factures info
    var $enc_info; //Array stock all encaissement info
    var $tot_devis_info; //Array stock total devis info
    var $tot_factures_info; //Array stock total factures info
    var $tot_enc_info; //Array stock total encaissement info
    var $solde_final; //Solde final du client 
    var $tickets_info; //Array list tickets

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

//Get all info categorie_client from database for edit form

    public function get_client() {
        global $db;

        $sql = "SELECT  c.*,cat.categorie_client as categorie_client, p.pays as pays,v.ville as ville, d.devise as devise,d.abreviation as dev, IF(c.tva='O','Oui','Non') AS tva,c.tva as tva_brut, m.motif as motif,DATE_FORMAT(c.`date_blocage`,'%d-%m-%Y') AS date_blocage,b.banque as banque FROM  clients c
             LEFT JOIN categorie_client cat on c.id_categorie=cat.id 
             LEFT JOIN ref_pays p on c.id_pays=p.id 
             LEFT JOIN ref_ville v on c.id_ville=v.id
             LEFT JOIN ref_devise d on c.id_devise=d.id
             LEFT JOIN ref_motif_blocage m on c.id_motif_blocage=m.id and m.type='C'
             LEFT JOIN ste_info_banque b on c.id_banque=b.id
             WHERE c.id = " . $this->id_client;

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
//return Array client_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

//Return the list of a client devis

    public function get_list_devis() {

        $table = "devis";
        global $db;

        $sql = "SELECT d.`id`,d.`reference`,DATE_FORMAT(d.`date_devis`,'%d-%m-%Y') AS date_devis,(SELECT  GROUP_CONCAT(CONCAT(c.prenom,' ',c.nom) ORDER BY c.id ASC SEPARATOR ', ') AS prenoms FROM commerciaux c WHERE FIND_IN_SET(c.id, REPLACE(REPLACE(REPLACE((REPLACE(d.id_commercial,'[','')),']',''),'\"',''),'\"','')) > 0 ) as commercial,REPLACE(FORMAT(d.`totalht`,0),',',' '),REPLACE(FORMAT(d.`totaltva`,0),',',' '),REPLACE(FORMAT(d.`total_remise`,0),',',' '),REPLACE(FORMAT(d.`totalttc`,0),',',' '), d.etat as etat FROM devis d WHERE d.etat <> 200 and d.`id_client` = " . $this->id_client . " order by d.date_devis desc";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->devis_info = $db->RecordsSimplArray();
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

//Return the Total devis

    public function get_total_devis() {

        $table = "devis";
        global $db;

        $sql = "SELECT IFNULL(REPLACE(FORMAT(SUM(d.`totalht`),0),',',' '),0) as totalht,IFNULL(REPLACE(FORMAT(SUM(d.`totalttc`),0),',',' '),0)as totalttc FROM devis d WHERE d.etat<>200 and d.`id_client` = " . $this->id_client;

        /* $sql ="SELECT IFNULL(REPLACE(FORMAT(SUM(d.`totalht`),0),',',' '),0) as totalht,IFNULL(REPLACE(FORMAT(SUM(d.`totalttc`),0),',',' '),0)as totalttc FROM devis d WHERE d.`etat`<>".Msetting::get_set('etat_devis', 'valid_client')." and d.`id_client` = ".$this->id_client; */

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->tot_devis_info = $db->RowArray();
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

//Return the list of a client bl

    public function get_list_bls() {

        global $db;

        $sql = "SELECT bl.`id`,bl.`reference`,DATE_FORMAT(bl.`date_bl`,'%d-%m-%Y') AS date_bl,bl.`projet`, bl.etat as etat FROM devis d, bl WHERE bl.`iddevis`=d.`id` and bl.etat <>200 AND d.`id_client` = " . $this->id_client . " order by bl.date_bl desc";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->bl_info = $db->RecordsSimplArray();
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

//Return the list of a Total factures

    public function get_total_fact() {

        $table = "factures";
        global $db;

        $sql = "SELECT IFNULL(REPLACE(FORMAT(SUM(factures.`total_ht`),0),',',' '),0) as totalht,IFNULL(REPLACE(FORMAT(SUM(factures.`total_ttc`),0),',',' '),0)as totalttc,IFNULL(REPLACE(FORMAT(SUM(factures.`total_paye`),0),',',' '),0) as paye,IFNULL(REPLACE(FORMAT(SUM(factures.`reste`),0),',',' '),0)as reste FROM factures,devis d  WHERE  IF(factures.`base_fact`='C',(factures.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE factures.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client) ),(factures.iddevis=d.id ))  AND factures.etat <>200 AND d.`id_client` = " . $this->id_client;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->tot_factures_info = $db->RowArray();
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

    public function get_list_abn() {

        $table = "contrats";
        global $db;

        $sql = "SELECT c.`id`,c.`reference`,DATE_FORMAT(c.`credat`,'%d-%m-%Y') AS date_abn,e.`type_echeance`, DATE_FORMAT(c.`date_effet`,'%d-%m-%Y')AS date_effet, DATE_FORMAT(c.`date_fin`,'%d-%m-%Y') AS date_fin,c.`pj`,c.etat as etat FROM contrats c,ref_type_echeance e WHERE e.`id`=c.`idtype_echeance` and c.etat <>200 AND c.`iddevis`IN(SELECT id FROM devis d WHERE d.`id_client`= " . $this->id_client . ") ORDER BY c.credat DESC";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->abn_info = $db->RecordsSimplArray();
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

//Return the list of a client factures

    public function get_list_factures() {

        $table = "factures";
        global $db;

        $sql = "SELECT factures.id,factures.reference,DATE_FORMAT(factures.date_facture,'%d-%m-%Y'),IF(factures.`base_fact`='C','Contrat',IF(factures.`base_fact`='D','Devis','BL')) AS base_fact,REPLACE(FORMAT(factures.total_ht,0),',',' '),REPLACE(FORMAT(factures.total_ttc,0),',',' '),REPLACE(FORMAT(factures.total_tva,0),',',' '),REPLACE(FORMAT(factures.total_paye,0),',',' '),REPLACE(FORMAT(factures.reste,0),',',' '), factures.etat as etat FROM factures,clients c,devis d WHERE   IF(factures.`base_fact`='C',( factures.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE factures.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client=c.id ) ), (factures.iddevis=d.id AND d.id_client=c.id )) and c.id=" . $this->id_client . " AND factures.`etat`<>200 ORDER BY factures.credat DESC";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->factures_info = $db->RecordsSimplArray();
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

//Return the list of a client encaissements

    public function get_list_encaissements() {

        $table = "encaissements";
        global $db;

        $sql = "SELECT e.id,e.`reference`,designation ,depositaire ,e.`date_encaissement`,ref_payement, e.`mode_payement`,IFNULL(REPLACE(FORMAT((e.`montant`),0),',',' '),0) AS montant ,pj, e.etat as etat FROM encaissements e,factures f,devis d  WHERE  f.`id`=e.`idfacture` AND IF(f.`base_fact`='C',( f.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE f.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client) ), (f.iddevis=d.id )) AND d.`id_client`=" . $this->id_client . " and e.etat <> 200 ORDER BY e.date_encaissement DESC";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->enc_info = $db->RecordsSimplArray();
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

//Return the list of a Total encaissements

    public function get_total_enc() {

        global $db;

        $sql = "SELECT IFNULL(REPLACE(FORMAT(SUM(e.`montant`),0),',',' '),0) AS total_enc FROM encaissements e,factures f,devis d  WHERE  f.`id`=e.`idfacture` AND IF(f.`base_fact`='C',(f.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE f.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client) ),(f.iddevis=d.id )) and e.etat <>200 AND d.`id_client` = " . $this->id_client;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->tot_enc_info = $db->RowArray();
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
//var_dump('here');
            $this->error = false;
            $this->log .= '</br>' . $message . ' n\'existe pas';
            return false;
        }
    }

//Generate refrence client
    private function Generate_client_reference() {
        if ($this->error == false) {
            return false;
        }
        global $db;
        global $db;
        $max_id = $db->QuerySingleValue0('SELECT IFNULL(( MAX(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5))),0)+1  AS reference FROM clients WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))');
        $this->reference = 'GT-CLT-' . $max_id . '/' . date('Y');
    }

//Save new client after all check
    public function save_new_client() {

        /*  //Generate reference
          $this->Generate_client_reference(); */
        global $db;

//Generate reference
        if (!$reference = $db->Generate_reference($this->table, 'CLT')) {
            $this->log .= '</br>Problème Réference';
            return false;
        }

//Before execute do the multiple check

        $this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', null);

        $this->Check_exist('reference', $this->reference, 'Réference Client', null);

        $this->Check_exist('r_social', $this->_data['r_social'], 'Raison Sociale', null);

        $this->Check_exist('r_commerce', $this->_data['r_commerce'], 'N° de registre', null);

        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', null);


        $this->check_non_exist('categorie_client', 'id', $this->_data['id_categorie'], 'Catégorie');

        $this->check_non_exist('ref_pays', 'id', $this->_data['id_pays'], 'Pays');

        if ($this->_data['id_ville'] != NULL) {
            $this->check_non_exist('ref_ville', 'id', $this->_data['id_ville'], 'Ville');
        }

        if ($this->_data['id_devise'] != NULL) {
            $this->check_non_exist('ref_devise', 'id', $this->_data['id_devise'], 'Devise');
        }

//Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justifications du client.');
        }
//Check if PJ attached required
        if ($this->exige_pj_photo) {
            $this->check_file('pj_photo', 'La photo du client.');
        }

//Check $this->error (true / false)
        if ($this->error == true) {
//Format values for Insert query 
            global $db;

            $values["reference"] = MySQL::SQLValue($reference);
            $values["id_prospect"] = MySQL::SQLValue($this->_data['id_prospect']);
            $values["denomination"] = MySQL::SQLValue($this->_data['denomination']);
            $values["id_categorie"] = MySQL::SQLValue($this->_data['id_categorie']);
            $values["r_social"] = MySQL::SQLValue($this->_data['r_social']);
            $values["r_commerce"] = MySQL::SQLValue($this->_data['r_commerce']);
            $values["nif"] = MySQL::SQLValue($this->_data['nif']);
            $values["nom"] = MySQL::SQLValue($this->_data['nom']);
            $values["prenom"] = MySQL::SQLValue($this->_data['prenom']);
            $values["civilite"] = MySQL::SQLValue($this->_data['civilite']);
            $values["adresse"] = MySQL::SQLValue($this->_data['adresse']);
            $values["id_pays"] = MySQL::SQLValue($this->_data['id_pays']);
            $values["id_ville"] = MySQL::SQLValue($this->_data['id_ville']);
            $values["tel"] = MySQL::SQLValue($this->_data['tel']);
            $values["fax"] = MySQL::SQLValue($this->_data['fax']);
            $values["bp"] = MySQL::SQLValue($this->_data['bp']);
            $values["email"] = MySQL::SQLValue($this->_data['email']);
            $values["id_banque"] = MySQL::SQLValue($this->_data['id_banque']);
            $values["rib"] = MySQL::SQLValue($this->_data['rib']);
            $values["id_devise"] = MySQL::SQLValue($this->_data['id_devise']);
            if ($this->_data['tva'] == 'Oui') {

                $values["tva"] = MySQL::SQLValue('O');
            } else {

                $values["tva"] = MySQL::SQLValue('N');
            }

            $values["creusr"] = MySQL::SQLValue(session::get('userid'));
            $values["credat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            /*       var_dump($this->_data);
              var_dump($values["tva"]); */

//Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow($this->table, $values)) {
//False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
//If Attached required Save file to Archive

                $this->save_file('pj', 'Justifications du clients' . $this->_data['denomination'], 'Document');

                $this->save_file('pj_photo', 'Photo du client' . $this->_data['denomination'], 'Image');

//Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Enregistrement réussie: <b>' . $this->_data['denomination'] . ' ID: ' . $this->last_id;

                    if (!Mlog::log_exec($this->table, $this->last_id, 'Création client', 'Insert')) {
                        $this->log .= '</br>Un problème de log ';
                    }
//Check $this->error = false return Red message and Bol false   
                } else {
                    $this->log .= '</br>Enregistrement réussie: <b>' . $this->_data['denomination'];

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

//activer ou desactiver un client
    public function valid_client($etat = 0) {

        global $db;
//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        $etat = $etat == 0 ? 1 : 0;
//Format value for requet
        $values["etat"] = MySQL::SQLValue($etat);
        $values["type_client"] = MySQL::SQLValue('D');
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

        $where["id"] = $this->id_client;

// Execute the update and show error case error
        if (!$result = $db->UpdateRows($this->table, $values, $where)) {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>' . $db->Error();
            $this->error = false;
        } else {
            $this->log .= '</br>Statut changé! ';
            //$this->send_welcome_client_mail();
            $this->error = true;
            if (!Mlog::log_exec($this->table, $this->id_client, 'Validation client', 'Validate')) {
                $this->log .= '</br>Un problème de log ';
            }
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

// afficher les infos d'un client
    public function g($key) {
        if ($this->client_info[$key] != null) {
            return $this->client_info[$key];
        } else {
            return null;
        }
    }

// afficher les infos d'un client
    public function s($key) {
        if ($this->client_info[$key] != null) {
            echo $this->client_info[$key];
        } else {
            echo "";
        }
    }

// afficher les infos d'un client
    public function Shw($key, $no_echo = "") {
        if ($this->client_info[$key] != null) {
            if ($no_echo != null) {
                return $this->client_info[$key];
            }

            echo $this->client_info[$key];
        } else {
            echo "";
        }
    }

//Edit categorie_client after all check
    public function edit_client() {

//Get existing data for categorie_client
        $this->get_client();

        $this->last_id = $this->id_client;

        $this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', $this->id_client);

//$this->Check_exist('code', $this->_data['code'], 'Code Fournisseur', null);

        $this->Check_exist('r_social', $this->_data['r_social'], 'Raison Sociale', $this->id_client);

        $this->Check_exist('r_commerce', $this->_data['r_commerce'], 'N° de registre', $this->id_client);

        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', $this->id_client);


        $this->check_non_exist('categorie_client', 'id', $this->_data['id_categorie'], 'Catégorie');

        $this->check_non_exist('ref_pays', 'id', $this->_data['id_pays'], 'Pays');



        if ($this->_data['id_ville'] != NULL) {
            $this->check_non_exist('ref_ville', 'id', $this->_data['id_ville'], 'Ville');
        }


        //$this->check_non_exist('ref_devise', 'id', $this->_data['id_devise'], 'Devise');

//Check if PJ attached required
        if ($this->exige_pj) {
            $this->check_file('pj', 'Justifications du client.', $this->_data['pj_id']);
        }
//Check if PJ attached required
        if ($this->exige_pj_photo) {
            $this->check_file('pj_photo', 'La photo du client.', $this->_data['pj_photo_id']);
        }

//Check $this->error (true / false)
        if ($this->error == true) {
//Format values for Insert query 
            global $db;

//$values["code"]        = MySQL::SQLValue($this->_data['code']);
            $values["denomination"] = MySQL::SQLValue($this->_data['denomination']);
            $values["id_categorie"] = MySQL::SQLValue($this->_data['id_categorie']);
            $values["r_social"] = MySQL::SQLValue($this->_data['r_social']);
            $values["r_commerce"] = MySQL::SQLValue($this->_data['r_commerce']);
            $values["nom"] = MySQL::SQLValue($this->_data['nom']);
            $values["prenom"] = MySQL::SQLValue($this->_data['prenom']);
            $values["civilite"] = MySQL::SQLValue($this->_data['civilite']);
            $values["adresse"] = MySQL::SQLValue($this->_data['adresse']);
            $values["id_pays"] = MySQL::SQLValue($this->_data['id_pays']);
            $values["id_ville"] = MySQL::SQLValue($this->_data['id_ville']);
            $values["tel"] = MySQL::SQLValue($this->_data['tel']);
            $values["fax"] = MySQL::SQLValue($this->_data['fax']);
            $values["bp"] = MySQL::SQLValue($this->_data['bp']);
            $values["email"] = MySQL::SQLValue($this->_data['email']);
            $values["id_banque"] = MySQL::SQLValue($this->_data['id_banque']);
            $values["rib"] = MySQL::SQLValue($this->_data['rib']);
            //$values["id_devise"] = MySQL::SQLValue($this->_data['id_devise']);
            if ($this->_data['tva'] == 'Oui') {

                $values["tva"] = MySQL::SQLValue('O');
            } else {

                $values["tva"] = MySQL::SQLValue('N');
            }
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_client;

//Check if Insert Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
//False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $this->id_client;
//If Attached required Save file to Archive
                $this->save_file('pj', 'Justifications du clients' . $this->_data['denomination'], 'Document');


                $this->save_file('pj_photo', 'Photo du client' . $this->_data['denomination'], 'image');

//Esspionage
                if (!$db->After_update($this->table, $this->id_client, $values, $this->client_info)) {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;
                }

//Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['denomination'] . ' ID: ' . $this->last_id;

                    if (!Mlog::log_exec($this->table, $this->id_client, 'Modification client', 'Update')) {
                        $this->log .= '</br>Un problème de log ';
                    }
//Check $this->error = false return Red message and Bol false   
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['denomination'];
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

//Bloquer client after all check
    public function bloquer_client() {
        global $db;

        $this->get_client();


        $this->last_id = $this->id_client;

        $this->check_non_exist('ref_motif_blocage', 'id', $this->_data['id_motif_blocage'], 'Motif de Blocage');

//Check $this->error (true / false)
        if ($this->error == true) {
//Format values for Insert query 
            global $db;

            $values["id_motif_blocage"] = MySQL::SQLValue($this->_data['id_motif_blocage']);
            $values["commentaire"] = MySQL::SQLValue($this->_data['commentaire']);
            $values["date_blocage"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $values["etat"] = Msetting::get_set('etat_client', 'client_bloque');
            $values["updusr"] = MySQL::SQLValue(session::get('userid'));
            $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"] = $this->id_client;

//Check if Insert Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
//False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $this->id_client;
//If Attached required Save file to Archive
//Esspionage
                if (!$db->After_update($this->table, $this->id_client, $values, $this->client_info)) {
                    $this->log .= '</br>Problème Espionage';
                    $this->error = false;
                }

//Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Client bloqué: <b> ID: ' . $this->last_id;

                    if (!Mlog::log_exec($this->table, $this->id_client, 'Blocage client', 'Update')) {
                        $this->log .= '</br>Un problème de log ';
                    }
//Check $this->error = false return Red message and Bol false 
                } else {
                    $this->log .= '</br>Client bloqué: <b>' . $this->last_id;
                    ;
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

    public function delete_client() {
        global $db;
        $id_client = $this->id_client;
        $this->get_client();
//Format where clause
        $where['id'] = MySQL::SQLValue($id_client);
//check if id on where clause isset
        if ($where['id'] == null) {
            $this->error = false;
            $this->log .= '</br>L\' id est vide';
        }
//execute Delete Query
        if (!$db->DeleteRows('clients', $where)) {

            $this->log .= $db->Error() . '  ' . $db->BuildSQLDelete('clients', $where);
            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {

            $this->error = true;
            $this->log .= '</br>Suppression réussie ';
            if (!Mlog::log_exec($this->table, $this->id_client, 'Suppression client', 'Delete')) {
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
        $folder = MPATH_UPLOAD . 'clients' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;



//Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'clients', $table, $column, $type, $edit = null)) {
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

    public function Gettable_detail_clients($date_debut, $date_fin, $id_client) {
        $date_d= date('Y-m-d', strtotime($date_debut));
        $date_f= date('Y-m-d', strtotime($date_fin));

        global $db;

        $table = "compte_client";
        
        $req_sql = "(SELECT @n := @n + 1 n ,' ' AS DATE, 'ANCIEN SOLDE' AS description
,' ' AS debit
,' ' AS credit
,IFNULL((
SELECT 0  FROM DUAL WHERE NOT EXISTS (SELECT * FROM compte_client cc WHERE  cc.id_client=compte_client.id_client AND cc.date_mouvement < '$date_d' AND cc.etat <> 200 )
UNION
(SELECT  CONCAT(REPLACE(FORMAT( cc.solde,0),',',' '),' ', dev.abreviation) FROM compte_client cc WHERE cc.id_client=compte_client.id_client AND cc.date_mouvement < '$date_d' AND cc.etat <> 200  ORDER BY cc.id DESC LIMIT 1 )
),0) AS solde
FROM compte_client,clients c, ref_devise dev ,(SELECT @n := 0) m WHERE c.id=compte_client.id_client AND  dev.id=c.id_devise
AND  compte_client.id_client = $id_client AND compte_client.etat <> 200 LIMIT 1)


                UNION     

                (SELECT   @n := @n + 1 n ,DATE_FORMAT(compte_client.date_mouvement,'%d-%m-%Y')AS DATE, 
                compte_client.description AS description, 
                IF(type_mouvement='D', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS debit,  
                IF(type_mouvement='C', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS credit,
                CONCAT(REPLACE(FORMAT(compte_client.solde,0),',',' '),' ', dev.abreviation) AS solde
                FROM compte_client,clients c, ref_devise dev WHERE c.id=compte_client.id_client
                AND  dev.id=c.id_devise AND compte_client.id_client =  $id_client AND 
                compte_client.date_mouvement BETWEEN '$date_d' AND '$date_f'
                AND compte_client.etat <> 200 order by compte_client.id)";

        
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error() . ' ' . $req_sql;
            exit($this->log);
        }


        $headers = array(
            'Id' => '5[#]center',
            'Date' => '5[#]center',
            'Description' => '30[#]left',
            'Montant' => '8[#]alignRight',
            'Paiement' => '8[#]alignRight',
            'Solde' => '12[#]alignRight',
        );
        

        $tableau = $db->GetMTable($headers);
        
        $this->Get_detail_info_client($date_debut, $date_fin, $id_client);

//var_dump($this->client_info);
        $this->Get_solde_client_final($id_client);

        $this->solde_final=$this->solde_final['solde_final'];
        //var_dump($this->solde_final);
        return $tableau;
    }

    public function Get_detail_client_pdf() {

        global $db;

        $id_client = $this->$id_client;

//$this->get_d_facture($this->id_facture);
        $facture_details_info = $this->facture_details_info;

        $table = 'compte_client';

        $this->Get_detail_client_show($date_debut, $date_fin, $id_client);
        $compte_client_info = $this->compte_client_info;


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

    public function Get_detail_client_show($date_debut, $date_fin, $id_client) {
        $id_client = $this->id_client;
         $date_d= date('Y-m-d', strtotime($date_debut));
        $date_f= date('Y-m-d', strtotime($date_fin));
        
        global $db;
        $i = 0;
        /*
          $req_sql = "SELECT   compte_client.id AS id,DATE_FORMAT(compte_client.date_mouvement,'%d-%m-%Y')AS DATE,
          compte_client.description,IF(type_mouvement='D', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS debit,
          IF(type_mouvement='C', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS credit, REPLACE(FORMAT(compte_client.solde,0),',',' ') AS solde
          ,c.reference AS reference, (SELECT mvt.solde FROM compte_client mvt WHERE mvt.id_client = compte_client.`id_client` ORDER BY mvt.id DESC LIMIT 1) AS solde_final
          FROM compte_client,clients c
          WHERE compte_client.id_client=c.id and compte_client.id_client = $id_client AND DATE_FORMAT(compte_client.date_mouvement,'%d-%m-%Y') BETWEEN '$date_debut' and '$date_fin'";

         *  //var_dump($req_sql);
         */
         $req_sql = "(SELECT @n := @n + 1 n ,' ' AS DATE, 'ANCIEN SOLDE' AS description
,' ' AS debit
,' ' AS credit
,IFNULL((
SELECT 0  FROM DUAL WHERE NOT EXISTS (SELECT * FROM compte_client cc WHERE  cc.id_client=compte_client.id_client AND cc.date_mouvement < '$date_d' AND cc.etat <> 200)
UNION
(SELECT  REPLACE(FORMAT( cc.solde,0),',',' ') FROM compte_client cc WHERE cc.id_client=compte_client.id_client AND cc.date_mouvement < '$date_d' AND cc.etat <> 200  ORDER BY cc.id DESC LIMIT 1 )
),0) AS solde
FROM compte_client,clients c, (SELECT @n := 0) m WHERE c.id=compte_client.id_client
AND  compte_client.id_client = $id_client AND compte_client.etat <> 200 LIMIT 1)

UNION     

(SELECT   @n := @n + 1 n ,DATE_FORMAT(compte_client.date_mouvement,'%d-%m-%Y')AS DATE, 
compte_client.description AS description, 
IF(type_mouvement='D', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS debit,  
IF(type_mouvement='C', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS credit,
REPLACE(FORMAT(compte_client.solde,0),',',' ') AS solde
FROM compte_client,clients c WHERE c.id=compte_client.id_client AND compte_client.id_client = $id_client AND 
compte_client.date_mouvement BETWEEN  '$date_d' AND '$date_f'AND compte_client.etat <> 200 ORDER BY compte_client.id)
       ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                //$this->client_info = $db->RowArray();

                $this->compte_client_info = $db->RowArray();
                $this->error = true;
            }
        }
        //var_dump($this->compte_client_info);
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function Get_detail_info_client($date_debut, $date_fin, $id_client) {
        $date_d= date('Y-m-d', strtotime($date_debut));
        $date_f= date('Y-m-d', strtotime($date_fin));
     
        global $db;
        $i = 0;

        $req_sql = "SELECT   compte_client.id AS id,DATE_FORMAT(compte_client.date_mouvement,'%d-%m-%Y')AS DATE,
          compte_client.description,IF(type_mouvement='D', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS debit,
          IF(type_mouvement='C', REPLACE(FORMAT(compte_client.montant,0),',',' '), ' ') AS credit,
          REPLACE(FORMAT(compte_client.solde,0),',',' ') AS solde
          ,c.reference AS reference,dev.abreviation as devise,
          c.denomination AS denomination ,c.adresse AS adresse ,
          v.ville as ville
          FROM compte_client,clients c,ref_ville v,ref_devise dev
          WHERE c.id_devise=dev.id and c.id_ville=v.id and  compte_client.id_client=c.id and compte_client.id_client = $id_client 
          AND compte_client.date_mouvement BETWEEN '$date_d' and '$date_f'  AND compte_client.etat <> 200
          ORDER BY compte_client.id";


        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {

                $this->client_info = $db->RowArray();
                $this->error = true;
            }
        }
        
        //var_dump($this->client_info);
        //var_dump($db);
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    public function Get_solde_client_final($id_client) {
        
        global $db;


        $req_sql = "SELECT REPLACE(FORMAT(mvt.solde,0),',',' ') AS solde_final
          FROM compte_client mvt 
          WHERE mvt.id_client =  $id_client  ORDER BY mvt.id DESC LIMIT 1";


        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {

                $this->solde_final = $db->RowArray();
                $this->error = true;
            }
        }
        
        //var_dump($this->client_info);
        //var_dump($db);
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    
    
    private function send_welcome_client_mail() {
        //Get info abonnement
        $this->get_client();
        $client_info = $this->client_info;

        if ($this->verif_email($client_info["id"]) == FALSE) {
            $this->log .= '<br/>Ce client n\'a pas une adresse Mail';
            return false;
        }

        $client = new Mclients();
        $client->id_client = $client_info["id"];
        $client->get_client();
        $agent_name = $client->g('denomination');
      
        $mail = new PHPMailer();
        $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
        $mail->SMTPSecure = 'ssl'; // Accepter SSL
        $mail->setFrom($mail->Username, 'GlobalTech Direction'); // Personnaliser l'envoyeur
        $mail->addAddress($client_info["email"], $client_info["denomination"]);
       
        $mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

        $mail->Subject = "Bienvenue chez Globaltech";

        $mail->Body = "<b></br></br> Cher Client,</br>Bienvenue chez Globaltech. Nous sommes très heureux de vous compter parmi nos clients</br>
                    et vous confirmons par ce courrier l’activation de votre commande Offre (Internet via VSAT ou</br>
                    Internet via BLR).</br>
                    Nous espérons que cette offre vous donnera pleine satisfaction.</br>
                    Votre Numéro de Code Client est le ".$client_info["reference"]." nous vous remercions de le communiquer lors </br>
                    de vos demandes auprès de nos services.
                    </br></br>
                    Pour toutes vos demandes de renseignements, nous vous suggérons de nous contacter :</br>
                    Par Téléphone   (+235) 22 51 40 44</br>
                    Par Email   support@globaltech.td       Service Technique  </br>
                                commercial@globaltech.td    Service Commercial </br>

</br></br>
Toute l’équipe de Globaltech vous transmet, cher Client, ses salutations distinguées.
                    </b>";
        if (!$mail->send()) {
            $this->log .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $this->log .= "Mail validation clients envoyé  à " . $client_info["email"];
        }
    }

    private function verif_email($id_client) {
        global $db;
        $result = $db->QuerySingleValue0("SELECT email FROM clients WHERE id=" . $id_client);
        if ($result == "0") {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    
    // Chercher la liste des tickets du client

    public function get_list_tickets() {

        $table = "tickets";
        global $db;

         $sql = "SELECT tickets.id as id ,   
tickets.etat as etat,             
                CONCAT(users_sys.fnom,' ',users_sys.lnom) AS technicien ,
                clients.denomination AS CLIENT ,
                produits.designation AS prd,
                tickets.serial_number AS serial_number,
                sites.reference AS site,
                DATE_FORMAT(tickets.credat,'%d-%m-%Y') AS credat            
                FROM tickets LEFT JOIN produits ON produits.id=tickets.id_produit              
                LEFT JOIN users_sys ON users_sys.id=tickets.id_technicien
                LEFT JOIN clients ON clients.id=tickets.id_client
                LEFT JOIN sites ON sites.id=tickets.projet             
                WHERE tickets.id_client =  " . $this->id_client;
        
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->tickets_info = $db->RecordsArray();
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

}
