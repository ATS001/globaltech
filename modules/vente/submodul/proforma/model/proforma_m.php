<?php

/**
* Mproforma Gestion proforma et Détail 
* V1.0
*/
class Mproforma
{
    //Declared Private
    private $_data; //data receive from form
    //Declared Variable
    var $table           = 'proforma'; //Main table of module
    var $table_details   = 'd_proforma'; //Tables détails proforma
    var $last_id         = null; //return last ID after insert command
    var $log             = null; //Log of all opération.
    var $id_proforma     = null; // proforma ID append when request
    var $token           = null; //user for recovery function
    var $proforma_info   = null; //Array stock all ville info
    var $proforma_d_info = null;//
    var $reference       = null; // Reference proforma
    var $error           = true; //Error bol changed when an error is occured
    var $valeur_remis_d  = null;//
    var $prix_u_final   = null;//
    var $total_ht_d      = null; //
    var $total_tva_d     = null;//
    var $total_ttc_d     = null;//
    var $valeur_remis_t  = null;//
    var $total_ht_t      = null;// 
    var $total_tva_t     = null;//
    var $order_detail    = null; //
    var $sum_total_ht    = null;//
    var $total_commission = null;//



    public function __construct($properties = array()){
        $this->_data = $properties;
    }

    //magic methods!
    public function __set($property, $value){
        return $this->_data[$property] = $value;
    }

    public function __get($property){
        return array_key_exists($property, $this->_data)? $this->_data[$property]: null;
    }


    public function get_proforma()
    {
        $table = $this->table;
        global $db;

        $sql = "SELECT $table.*, DATE_FORMAT($table.date_proforma,'%d-%m-%Y') AS date_proforma from $table where $table.id = ".$this->id_proforma;

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
                $this->proforma_info = $db->RowArray();
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

    //////////////////////////////////////////////////////////////////
    public function get_proforma_d()
    {
        $table_details = $this->table_details;
        global $db;

        $sql = "SELECT $table_details.* FROM $table_details WHERE $table_details.id = ".$this->id_proforma_d;

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
                $this->proforma_d_info = $db->RowArray();
                $this->error = true;
            }


        }

        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }

    }


        public function Get_detail_proforma_show()
    {
        global $db;
        $req_sql = "SELECT
        proforma.*
        , DATE_FORMAT(proforma.date_proforma,'%d-%m-%Y') as date_proforma
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
        proforma
        INNER JOIN clients 
        ON (proforma.id_client = clients.id)
        LEFT JOIN ref_pays 
        ON (clients.id_pays = ref_pays.id)
        LEFT JOIN ref_ville
        ON (clients.id_ville = ref_ville.id)
        INNER JOIN ref_devise
        ON (clients.id_devise = ref_devise.id)
        INNER JOIN users_sys
        ON (proforma.creusr = users_sys.id)
        INNER JOIN services
        ON (users_sys.service = services.id)
        INNER JOIN commerciaux
        ON (devis.id_commercial=commerciaux.id)
 
        WHERE proforma.id = ".$this->id_proforma;
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error();
        }else{
            if (!$db->RowCount())
            {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->proforma_info = $db->RowArray();
                $this->error = true;
            }


        }
        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }

    }

    /**
     * [Get_detail_proforma_pdf Render query for export PDF]
     * @return bol send to controller
     */
    public function Get_detail_proforma_pdf()
    {
        global $db;

        $id_proforma = $this->id_proforma;
        $table    = $this->table_details;
        $this->Get_detail_proforma_show();
        $proforma_info = $this->proforma_info;
        $colms  = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.prix_unitaire,0),',',' '), ";
        //$colms .= " $table.type_remise, ";
        //$colms .= " CONCAT(REPLACE(FORMAT($table.remise_valeur,0),',',' '),'%'), ";
        
       // $colms .= " REPLACE(FORMAT($table.total_ht,0),',',' '), ";
       // $colms .= " REPLACE(FORMAT($table.total_tva,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_ht,0),',', ' ') ";
        
        $req_sql  = " SELECT $colms FROM $table WHERE id_proforma = $id_proforma ";
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            exit($this->log);
        }
        
        
        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }
        
        
    }

    public function Gettable_detail_proforma()
    {
        global $db;
        $id_proforma = $this->id_proforma;
        $table    = $this->table_details;
        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.prix_unitaire,0),',',' '), ";
        //$colms .= " $table.type_remise, ";
        $colms .= " $table.remise_valeur, ";
        
        $colms .= " REPLACE(FORMAT($table.total_ht,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_tva,0),',',' '), ";
        $colms .= " REPLACE(FORMAT($table.total_ttc,0),',', ' ') ";
        
        $req_sql  = " SELECT $colms FROM $table WHERE id_proforma = $id_proforma ";
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            exit($this->log);
        }
        
        
        $headers = array(
            'Item'        => '5[#]center',
            'Réf'         => '15[#]center',
            'Description' => '30[#]', 
            'Qte'         => '5[#]center', 
            'P.U'         => '10[#]alignRight', 
            'Re'          => '5[#]center',
            'Total HT'    => '10[#]alignRight',
            'TVA'         => '10[#]alignRight',
            'Total TTC'   => '15[#]alignRight',

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
    private function check_exist($column, $value, $message, $edit = null)
    {
        global $db;
        $table = $this->table;
        $sql_edit = $edit == null ? null: " AND  <> $edit";
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
            WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

        if ($result != "0") {
            $this->error = false;
            $this->log .='</br>'.$message.' existe déjà';
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
        private function check_non_exist($table, $column, $value, $message)
        {
            global $db;
            $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
                WHERE $table.$column = ". MySQL::SQLValue($value));
            if ($result == "0") {
                $this->error = false;
                $this->log .='</br>'.$message.' n\'exist pas';
            //exit('0#'.$this->log);
            }
        }
    /////////////////////////////////////////////////////////////////////////////////
        private function Make_proforma_reference()
        {
            if($this->error == false)
            {
                return false;
            }
            global $db;
            $max_id = $db->QuerySingleValue0('SELECT MAX(id)+1 FROM proforma');
            $this->reference = 'DEV_'.$max_id.'_'.date('Y');
        }
    /////////////////////////////////////////////////////////////////////////////////
        private function Check_proforma_exist($tkn_frm, $edit = null)
        {
            global $db;
            $count_id = $db->QuerySingleValue0("SELECT COUNT(id) FROM proforma WHERE tkn_frm = '$tkn_frm'");
      //exit("0#".$count_id);
            if(($count_id != '0' && $edit == Null ) OR ($count_id != '1' && $edit != null))
            {
                $this->error = false;
                $this->log .='</br>Ce proforma est déjà enregitré '.$count_id;
            }
        }
    /////////////////////////////////////////////////////////////////////////////////
        public function save_new_proforma()
        {
      //Check if proforma exist
            $this->Check_proforma_exist($this->_data['tkn_frm'], null);
      //Check if proforma have détails
            $this->Check_proforma_have_details($this->_data['tkn_frm']);
      //Make reference
            //$this->Make_proforma_reference();
        //Before execute do the multiple check
            $this->Check_exist('reference', $this->reference, 'Réference proforma', null);

            $this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );

            $this->check_non_exist('commerciaux','id',$this->_data['id_commercial'] ,'Commercial' );
      //Get sum of details
            $this->Get_sum_detail($this->_data['tkn_frm']); 
      //calcul values proforma
           // $this->Calculate_proforma_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva']);

            
      //Check $this->error (true / false)
            if($this->error == false)
            {
                $this->log .='</br>Enregistrement non réussie';
                return false;
            }

        //Format values for Insert query 
            global $db;
            $table = $this->table;
            //Generate reference
            if(!$reference = $db->Generate_reference($table, 'PROF'))
            {
                $this->log .= '</br>Problème Réference';
                return false;
            }
            //$totalht  = $this->total_ht_t;
            //$totaltva = $this->total_tva_t;
            //$totalttc = $this->total_ttc_t;
            //$valeur_remise = $this->valeur_remis_t;

            $values["reference"]       = MySQL::SQLValue($reference);
            $values["tkn_frm"]         = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["id_client"]       = MySQL::SQLValue($this->_data['id_client']);
            $values["tva"]             = MySQL::SQLValue($this->_data['tva']);
            $values["vie"]             = MySQL::SQLValue($this->_data['vie']);
            $values["id_commercial"]   = MySQL::SQLValue($this->_data['id_commercial']);
            $values["date_proforma"]   = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_proforma'])));
            //$values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise']);
            //$values["valeur_remise"] = MySQL::SQLValue($valeur_remise);
            $values["claus_comercial"] = MySQL::SQLValue($this->_data['claus_comercial']);
            //$values["totalht"]       = MySQL::SQLValue($totalht);
            //$values["totalttc"]      = MySQL::SQLValue($totalttc);
            //$values["totaltva"]      = MySQL::SQLValue($totaltva);
            $values["commission"] = MySQL::SQLValue($this->_data['commission']);
            $values["creusr"]          = MySQL::SQLValue(session::get('userid'));
            $values["credat"]          = MySQL::SQLValue(date("Y-m-d H:i:s"));
        //Check if Insert Query been executed (False / True)
            if(!$result = $db->InsertRow($table, $values))
            {
            //False => Set $this->log and $this->error = false
              $this->log .= $db->Error();
              $this->error = false;
              $this->log .='</br>Enregistrement BD non réussie'; 
          }else{
              $this->last_id = $result;
            //Check $this->error = true return Green message and Bol true
              if($this->error == true)
              {
               $this->log = '</br>Enregistrement réussie: <b>Réference: '.$reference;
               $this->save_temp_detail($this->_data['tkn_frm'], $this->last_id);
               //log
                if(!Mlog::log_exec($table, $this->id_proforma, 'Enregistrement proforma '.$this->last_id, 'Insert' ))
                {
                    $this->log .= '</br>Un problème de log ';
                        
                }
                //Check $this->error = false return Red message and Bol false   
           }else{
               $this->log .= '</br>Enregistrement réussie: <b>'.$this->reference;
               $this->log .= '</br>Un problème d\'Enregistrement ';
           }
        }//Else Error false 
        
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }

    }

    public function edit_exist_proforma()
    {
        $this->reference = $this->_data['reference'];
      //Check if proforma exist
        $this->Check_proforma_exist($this->_data['tkn_frm'], 1);
      //Check if proforma have détails
        $this->Check_proforma_have_details($this->_data['tkn_frm']);
      //Make reference
      //$this->Make_proforma_reference();
        //Before execute do the multiple check
        $this->Check_exist('reference', $this->reference, 'Réference proforma', 1);

        $this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );

        $this->check_non_exist('commerciaux','id',$this->_data['id_commercial'] ,'Commercial' );

      //Get sum of details
        $this->Get_sum_detail($this->_data['tkn_frm']); 
      //calcul values proforma
        //$this->Calculate_proforma_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva']);


      //Check $this->error (true / false)
        if($this->error == false)
        {
            $this->log .='</br>Enregistrement non réussie';
            return false;
        }
        //Get existing info
        $this->get_proforma();

    //Format values for Insert query 
        global $db;
        $table = $this->table;
        //$totalht  = $this->total_ht_t;
        //$totaltva = $this->total_tva_t;
        //$totalttc = $this->total_ttc_t;
        //$valeur_remise = $this->valeur_remis_t;
        //$this->reference = $this->_data['reference'];


        $values["reference"]       = MySQL::SQLValue($this->reference);
        $values["tkn_frm"]         = MySQL::SQLValue($this->_data['tkn_frm']);
        $values["id_client"]       = MySQL::SQLValue($this->_data['id_client']);
        $values["tva"]             = MySQL::SQLValue($this->_data['tva']);
        $values["vie"]             = MySQL::SQLValue($this->_data['vie']);
        $values["id_commercial"]   = MySQL::SQLValue($this->_data['id_commercial']);
        $values["commission"]      = MySQL::SQLValue($this->_data['commission']);
        /*$values["total_commission"]= MySQL::SQLValue($total_commission);*/
        $values["date_proforma"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_proforma'])));
        //$values["type_remise"]     = MySQL::SQLValue($this->_data['type_remise']);
        //$values["valeur_remise"]   = MySQL::SQLValue($valeur_remise);
        //$values["claus_comercial"] = MySQL::SQLValue($this->_data['claus_comercial']);
        //$values["totalht"]         = MySQL::SQLValue($totalht);
        //$values["totalttc"]        = MySQL::SQLValue($totalttc);
        //$values["totaltva"]        = MySQL::SQLValue($totaltva);
        $values["updusr"]  = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = ' CURRENT_TIMESTAMP ';
        $wheres["id"]     = MySQL::SQLValue($this->id_proforma);
        //Check if Insert Query been executed (False / True)
        if (!$result = $db->UpdateRows($table, $values, $wheres)) 
        {
        //False => Set $this->log and $this->error = false
          $this->log .= $db->Error();
          $this->error = false;
          $this->log .='</br>Modification BD non réussie'; 
        }else{
            $this->last_id = $result;
            //Check $this->error = true return Green message and Bol true
            if($this->error == true)
            {
                 $this->log = '</br>Modification réussie: <b>Réference: '.$this->reference;
                 $this->save_temp_detail($this->_data['tkn_frm'], $this->id_proforma);
                //log
                if(!Mlog::log_exec($table, $this->id_proforma, 'Modification proforma '.$this->id_proforma, 'Update' ))
                {
                    $this->log .= '</br>Un problème de log ';
                        
                }
                
                //Spy
                if(!$db->After_update($table, $this->last_id, $values, $this->proforma_info))
                {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false; 
                }
        //Check $this->error = false return Red message and Bol false 
         }else{
             $this->log .= '</br>Modification réussie: <b>'.$this->reference;
             $this->log .= '</br>Un problème d\'Modification ';
         }
        }//Else Error false 
        
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }

    }
   

    public function verif_commission($tkn_frm, $commission)
    {
        $table_details = $this->table_details;
        $tva_value     = Mcfg::get('tva');
        global $db;
        if($tva == 'N'){
            $req_sql = "UPDATE $table_details SET total_ttc = total_ht, total_tva = 0  WHERE tkn_frm = '$tkn_frm'";
        }else{
            $req_sql = "UPDATE $table_details SET  total_tva = ((total_ht * $tva_value)/100), total_ttc = (total_ht + total_tva)  WHERE tkn_frm = '$tkn_frm'";
        }

        //Run adaptation
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<\br>Problème Enregistrement détails dans le devis';
            return false;
        }else{
            $this->Get_sum_detail($tkn_frm);
            $this->log .='Adaptation TVA réussie';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
     
    }



    private function Calculate_proforma_d($prix_u, $qte, $type_remise, $value_remise, $tva)
    {
        if($type_remise == 'P')
        {
            $prix_u_remised = $prix_u - ($prix_u * $value_remise) / 100;
            $this->valeur_remis_d = $value_remise;

        }else if($type_remise == 'M'){
            $prix_u_remised = $prix_u - $value_remise;
            $this->valeur_remis_d = ($value_remise * 100) / $prix_u;
        }else{
            $prix_u_remised = $prix_u;
        }
        //TVA value get from app setting
        $tva_value = Mcfg::get('tva');
        $this->prix_u_final = $prix_u_remised;

      //Total HT 
        $this->total_ht_d = $prix_u_remised * $qte;
      //Calculate TVA
        if($tva == 'N')
        {
            $this->total_tva_d = 0;
        }else{
            $this->total_tva_d = ($this->total_ht_d * $tva_value) / 100; 
        }
        $this->total_ttc = $this->total_ht_d + $this->total_tva_d;
        return true;

    }

    private function Calculate_proforma_t($totalht, $type_remise, $value_remise, $tva,$commission)
    {
        if($type_remise == 'P')
        {
            $totalht_remised = $totalht - ($totalht * $value_remise) / 100;
            $this->valeur_remis_t = $value_remise;

        }else if($type_remise == 'M'){
            $totalht_remised = $totalht - $value_remise;
            $this->valeur_remis_t = ($value_remise * 100) / $totalht;

        }else{
            $totalht_remised = $totalht;
        }

      //Valeur remised en percentage
      
      //Total HT 
        $this->total_ht_t = $totalht_remised;
      //TVA value get from app setting
        $tva_value = Mcfg::get('tva');
      //Calculate TVA
        if($tva == 'N')
        {
            $this->total_tva_t = 0;
        }else{
            $this->total_tva_t = ($this->total_ht_t * $tva_value) / 100; //TVA value get from app setting
        }
        $this->total_ttc_t = $this->total_ht_t + $this->total_tva_t;

       //Total commission
        $this->total_commission = ($this->total_ttc_t * $commission) / 100;
        return true;

    }

    private function get_order_detail($tkn_frm)
    {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT IFNULL(MAX($table_details.order)+1,1) AS this_order FROM $table_details WHERE tkn_frm = '$tkn_frm'";
        $this->order_detail = $db->QuerySingleValue0($req_sql);
    }

    private function check_detail_exist_in_proforma($tkn_frm, $id_produit)
    {
        if($this->error == false)
        {
            return false;
        }
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id_produit) FROM $table_details WHERE tkn_frm='$tkn_frm' AND id_produit = $id_produit ";

        $count_id = $db->QuerySingleValue0($req_sql);
        if($count_id != '0') 
        {
          $this->error = false;
          $this->log .= '</br>Ce produit / service exist déjà dans la liste de ce proforma';
        }
    }

    private function Check_proforma_have_details($tkn_frm)
    {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id) FROM $table_details WHERE tkn_frm='$tkn_frm' ";
        if($db->QuerySingleValue0($req_sql) == '0')
        {
            $this->error = false;
            $this->log .= '</br>Pas de détails enregistrés';
        }
    }

    private function save_temp_detail($tkn_frm, $id_proforma)
    {

        $table_details = $this->table_details;
        global $db;
        $req_sql = "UPDATE $table_details SET id_proforma = $id_proforma WHERE tkn_frm = '$tkn_frm'";
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème Enregistrement détails dans le proforma';
        }
    }

    public function set_tva_for_detail_on_change_main_tva($tkn_frm, $tva)
    {
        $table_details = $this->table_details;
        $tva_value     = Mcfg::get('tva');
        global $db;
        if($tva == 'N'){
            $req_sql = "UPDATE $table_details SET total_ttc = total_ht, total_tva = 0  WHERE tkn_frm = '$tkn_frm'";
        }else{
            $req_sql = "UPDATE $table_details SET  total_tva = ((total_ht * $tva_value)/100), total_ttc = (total_ht + total_tva)  WHERE tkn_frm = '$tkn_frm'";
        }

        //Run adaptation
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<\br>Problème Enregistrement détails dans le proforma';
            return false;
        }else{
            $this->Get_sum_detail($tkn_frm);
            $this->log .='Adaptation TVA réussie';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
    }


    //update commission in details_devis after the change of commission in the main
    public function set_commission_for_detail_on_change_main_commission($tkn_frm, $commission)
    {
        //var_dump($commission);
        $table_details = $this->table_details;
        $tva_value     = Mcfg::get('tva');

        global $db;
        $req_sql = "UPDATE $table_details SET  prix_ht = (prix_unitaire +((prix_unitaire * $commission)/100)), total_ht = (prix_ht * qte), total_tva = ((total_ht * $tva_value)/100), total_ttc = (total_ht + total_tva)  WHERE tkn_frm = '$tkn_frm'";

        //Run adaptation
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<\br>Problème Enregistrement détails dans le devis';
            return false;
        }else{
            $this->Get_sum_detail($tkn_frm);
            $this->log .='Adaptation Commission réussie';
        }
        $arr_return = array('error' => $this->error, 'mess' => $this->log, 'sum' => $this->sum_total_ht);
        return $arr_return;
     
    }


    private function Get_sum_detail($tkn_frm)
    {
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT SUM($table_details.total_ht)  FROM $table_details WHERE tkn_frm = '$tkn_frm' ";
        $this->sum_total_ht = $db->QuerySingleValue0($req_sql);
    }

    public function save_new_details_proforma($tkn_frm)
    {
        $table_details = $this->table_details;
        $this->check_detail_exist_in_proforma($tkn_frm, $this->_data['id_produit']);
        $this->check_non_exist('produits','id',$this->_data['id_produit'] ,'Réference du produit' );


        //Check $this->error (true / false)
        if($this->error == true){
        //Calcul Montant
            $this->Calculate_proforma_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva_d']);
          //Get produit info
            $produit             = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();

            $ref_produit         = $produit->produit_info['reference'];
            $designation         = $produit->produit_info['designation'];
          //Valeu finance
            $total_ht       = $this->total_ht_d;
            $total_tva      = $this->total_tva_d;
            $total_ttc      = $this->total_ttc_d;
            //$valeur_remis_d = $this->valeur_remis_d;
            $valeur_remis_d = number_format($this->valeur_remis_d, 2,'.', '');
            $prix_u_final   = $this->prix_u_final;
          //Get order line into proforma
            $this->get_order_detail($tkn_frm);
            $order_detail = $this->order_detail;
            //Format values for Insert query 
            global $db;


            $values["tkn_frm"]       = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["id_produit"]    = MySQL::SQLValue($this->_data['id_produit']);
            $values["order"]         = MySQL::SQLValue($order_detail);
            $values["ref_produit"]   = MySQL::SQLValue($ref_produit);
            $values["designation"]   = MySQL::SQLValue($designation);
            $values["qte"]           = MySQL::SQLValue($this->_data['qte']);
            $values["prix_unitaire"] = MySQL::SQLValue(Mreq::tp('pu'));
            $values["prix_ht"]       = MySQL::SQLValue($prix_u_final);
            $values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["remise_valeur"] = MySQL::SQLValue($valeur_remis_d);
            $values["tva"]           = MySQL::SQLValue($this->_data['tva_d']);
            $values["total_ht"]      = MySQL::SQLValue($this->total_ht);
            $values["total_ttc"]     = MySQL::SQLValue($this->total_ttc);
            $values["total_tva"]     = MySQL::SQLValue($this->total_tva);
            $values["creusr"]        = MySQL::SQLValue(session::get('userid'));


        //Check if Insert Query been executed (False / True)
            if(!$result = $db->InsertRow($table_details, $values))
            {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Enregistrement BD non réussie'; 

            }else{

                $this->last_id = $result; 

                //Check $this->error = true return Green message and Bol true
                if($this->error == true)
                {
                    $this->log = '</br>Enregistrement réussie: <b>'.$this->_data['ref_produit'].' ID: '.$this->last_id;

                    $this->Get_sum_detail($this->_data['tkn_frm']);
                //Check $this->error = false return Red message and Bol false   
                }else{
                    $this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['ref_produit'];
                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }
            }
            //Else Error false  
        }else{
            $this->log .='</br>Enregistrement non réussie';
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }

    public function edit_exist_details_proforma($tkn_frm)
    {
        $table_details = $this->table_details;
        $this->get_proforma_d();
        if($this->h('id_produit') != $this->_data['id_produit'])
        {
            $this->check_detail_exist_in_proforma($tkn_frm, $this->_data['id_produit'], 1); 
        }

        $this->check_non_exist('produits','id',$this->_data['id_produit'] ,'Réference du produit' );


        //Check $this->error (true / false)
        if($this->error == true)
        {
        //Calcul Montant
            $this->Calculate_proforma_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva_d']);
        //Get produit info
            $produit             = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();

            $ref_produit         = $produit->produit_info['ref'];
            $designation         = $produit->produit_info['designation'];
        //Valeu finance
            $total_ht       = $this->total_ht_d;
            $total_tva      = $this->total_tva_d;
            $total_ttc      = $this->total_ttc_d;
            $valeur_remis_d = number_format($this->valeur_remis_d, 2,'.', '');
            $prix_u_final   = $this->prix_u_final;
        //Format values for Insert query 
            global $db;

            $values["id_produit"]    = MySQL::SQLValue($this->_data['id_produit']);
            $values["ref_produit"]   = MySQL::SQLValue($ref_produit);
            $values["designation"]   = MySQL::SQLValue($designation);
            $values["qte"]           = MySQL::SQLValue($this->_data['qte']);
            $values["prix_unitaire"] = MySQL::SQLValue(Mreq::tp('pu'));
            $values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["prix_ht"]       = MySQL::SQLValue($prix_u_final);
            $values["remise_valeur"] = MySQL::SQLValue($this->valeur_remis_d);
            $values["tva"]           = MySQL::SQLValue($this->_data['tva_d']);
            $values["total_ht"]      = MySQL::SQLValue($this->total_ht);
            $values["total_ttc"]     = MySQL::SQLValue($this->total_ttc);
            $values["total_tva"]     = MySQL::SQLValue($this->total_tva);
            $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
            $values["upddat"]        = ' CURRENT_TIMESTAMP ';
            $wheres["id"]            = MySQL::SQLValue($this->id_proforma_d);
        //Check if Insert Query been executed (False / True)
            if(!$db->UpdateRows($this->table_details, $values, $wheres))
            {
                //False => Set $this->log and $this->error = false
               $this->log .= $db->Error();
               $this->error = false;
               $this->log .='</br>Modification BD non réussie'; 

            }else{
        //Check $this->error = true return Green message and Bol true
                if($this->error == true)
                {
                    $this->log = '</br>Modification réussie: <b>'.$this->_data['ref_produit'].' ID: '.$this->id_proforma_d;

                    $this->Get_sum_detail($tkn_frm);
                    //log if is edit main devis
                    if($this->proforma_d_info['id_proforma'] != null)
                    {
                        if(!Mlog::log_exec($table, $this->id_proforma, 'Modification Détail proforma '.$this->proforma_d_info['id_proforma'], 'Update'))
                        {
                            $this->log .= '</br>Un problème de log ';
                        
                        }
                        //Spy
                        if(!$db->After_update($table_details, $this->id_proforma_d, $values, $this->proforma_d_info))
                        {
                            $this->log .= '</br>Problème Esspionage';
                            $this->error = false; 
                        }
                    }
                //Check $this->error = false return Red message and Bol false   
                }else{
                    $this->log .= '</br>Modification réussie: <b>'.$this->_data['ref_produit'];
                    $this->log .= '</br>Un problème d\'Modification ';
                }
            }
        //Else Error false  
        }else{
            $this->log .='</br>Modification non réussie';
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false)
        {
            return false;
        }else{
            return true;
        }
    }

    /**
     * [senddevis_to_client Basclly send devis to client and generat PDF]
     * @param [type] $etat [description]
     */
    public function sendproforma_to_client($etat)
    {
        //Send to client
        $old_etat = Msetting::get_set('etat_proforma', 'valid_proforma');
        if($old_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_proforma => valid_proforma";
            return false;
        }
        if($etat != $old_etat)
        {
            $this->error = false;
            $this->log .= "Ce proforma ne peux pas être envoyé pour le momement #Etat faild";
            return false;
        }
        global $db;
        $table = $this->table;
        $id_proforma = $this->id_proforma;
        
        $new_etat = Msetting::get_set('etat_proforma', 'send_proforma');
        if($new_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_proforma => send_proforma";
            return false;
        }
        $this->generate_proforma_pdf();
        $req_sql = " UPDATE $table SET etat = $new_etat WHERE id = $id_proforma ";
        
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération";
            return false;
        }
        if(Msetting::get_set('send_mail_proforma') == true){
            $this->send_proforma_mail();
        }
        //log
        if(!Mlog::log_exec($table, $this->id_devis, 'Expédition proforma '.$this->id_proforma, 'Update'))
        {
            $this->log .= '</br>Un problème de log ';
                        
        }        
        
        $this->log .= "<br/>Expédition réussie";
        return true;
        
    }
    /**
     * [valid_proforma valider proforma to prepare to send to client]
     * @param  [int] $etat [etat of proforma must be 0]
     * @return [type]       [description]
     */
    public function valid_proforma($etat)
    {
        $old_etat = Msetting::get_set('etat_proforma', 'creat_proforma');
        if($old_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_proforma => creat_proforma";
            return false;
        }
        if($etat != $old_etat)
        {
            $this->error = false;
            $this->log .= "Ce proforma ne peux pas être validée //Etat faild";
            return false;
        }
        global $db;
        $table    = $this->table;
        $id_proforma = $this->id_proforma;
        
        $new_etat = Msetting::get_set('etat_proforma', 'valid_proforma');
        if($new_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_proforma => valid_proforma";
            return false;
        }
        $req_sql = " UPDATE $table SET etat = $new_etat WHERE id = $id_proforma ";
        
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération";
            return false;
        }
        //log
        if(!Mlog::log_exec($table, $this->id_proforma, 'Validation PROFORMA:'.$this->id_proforma, 'Update'))
        {
            $this->log .= '</br>Un problème de log ';
                        
        }

            
        $this->log .= "<br/>Opération réussie";
        return true;
        
    }

    /**
     * [generate_devis_pdf Generate PDF for store and send to client]
     * @return [type] [description]
     */
    Private function generate_proforma_pdf()
    {
        $file_tplt = MPATH_THEMES.'pdf_template/proforma_pdf.php';
        $saf_ref = str_replace('/','_',$this->g('reference'));
        $file_export  = MPATH_TEMP.'#'.$saf_ref.'.pdf';
        
        $qr_code = true;
        include_once $file_tplt;
        //Format all parameteres
        $temp_file     =  $file_export;
        //If nofile uploaded return kill function
        
        if($temp_file == Null){
            return true;
        }
        
        $new_name_file = $this->g('id').'_'.$saf_ref;
        $folder        = MPATH_UPLOAD.SLASH.'proforma/mois_'.date('m_Y').SLASH.$this->g('id');
        $id_line       = $this->g('id');
        $title         = 'Proforma '.$this->g('id').' #'.$this->g('reference');
        $table         = $this->table;
        $column        = 'proforma_pdf';
        $type          = 'Document';


         
        //Call save_file_upload from initial class
        if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'proforma', $table, $column, $type, $edit = null))
        {
            $this->error = false;
            $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
        }
        $this->attached = $folder.SLASH.$new_name_file.'.pdf';
        
        
    }

    

    public function Delete_detail_proforma($id_detail)
    {
        global $db;
        $table_details = $this->table_details;
        $get_tkn_frm = $db->QuerySingleValue0("SELECT tkn_frm FROM $table_details  WHERE id = $id_detail");

      //Format where clause
        $where['id'] = MySQL::SQLValue($id_detail);
      //check if id on where clause isset
        if($where['id'] == null)
        {
          $this->error = false;
          $this->log .='</br>L\' id est vide';
        }
      //execute Delete Query
        if(!$db->DeleteRows($table_details ,$where))
        {

            $this->log .= $db->Error().'  '.$db->BuildSQLDelete('proforma',$where);
            $this->error = false;
            $this->log .='</br>Suppression non réussie';

        }else{

            $this->error = true;
            $this->log .='</br>Suppression réussie ';
            $this->Get_sum_detail($get_tkn_frm);
            $this->log .='#'.$this->sum_total_ht;
            //log if is edit main devis
            if($this->proforma_d_info['id_proforma'] != null)
            {
                if(!Mlog::log_exec($table, $this->id_proforma, 'Suppression détail proforma '.$this->proforma_d_info['id_proforma'], 'Delete'))
                {
                    $this->log .= '</br>Un problème de log ';
                        
                }
            }
        }
      //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }

    // afficher les infos d'un proforma
    public function s($key)
    {
        if($this->proforma_info[$key] != null)
        {
            echo $this->proforma_info[$key];
        }else{
            echo "";
        }

    }
  // get les infos d'un proforma
    public function g($key)
    {
        if($this->proforma_info[$key] != null)
        {
            return $this->proforma_info[$key];
        }else{
            return null;
        }

    }

    // afficher les infos d'un proforma_d
    public function c($key)
    {
        if($this->proforma_d_info[$key] != null)
        {
            echo $this->proforma_d_info[$key];
        }else{
            echo "";
        }

    }
    //get les infos d'un proforma_d
    public function h($key)
    {
        if($this->proforma_d_info[$key] != null)
        {
            return $this->proforma_d_info[$key];
        }else{
            return null;
        }

    }

    public function delete_proforma()
    {
        global $db;
        $id_proforma = $this->id_proforma;
        $table = $this->table;
        $this->get_proforma();
        //Format where clause
        $where['id'] = MySQL::SQLValue($id_proforma);
        //check if id on where clause isset
        if($where['id'] == null)
        {
            $this->error = false;
            $this->log .='</br>L\' id est vide';
        }
        //execute Delete Query
        if(!$db->DeleteRows($table, $where))
        {

            $this->log .= $db->Error().'  '.$db->BuildSQLDelete('proforma',$where);
            $this->error = false;
            $this->log .='</br>Suppression non réussie';

        }else{

            $this->error = true;
            $this->log .='</br>Suppression réussie ';
            //log
            if(!Mlog::log_exec($table, $this->id_proforma, 'Suppression proforma '.$this->id_proforma, 'Delete'))
            {
                $this->log .= '</br>Un problème de log ';
                        
            }
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }

        /**
     * [get_info_produit description] Get all product info
     * @param  [type] $id_produit [description]
     * @return [type]             [description]
     * @return array converted to JSON incontroller
     */
    public function get_info_produit($id_produit)
    {
        global $db;
        $req_sql = "SELECT 
        produits.designation,
        produits.reference,
        produits.prix_vente AS prix_vente,
        ref_unites_vente.unite_vente,
        ref_types_produits.type_produit,
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
            
        }else{
            
            $this->arr_prduit = $db->RowArray();
            if($this->arr_prduit['prix_vente'] == null)
            {
                $this->arr_prduit = array('error' => "Prix de produit n'est pas enregitré");
            }            
        }
        return true;
    }
    /**
     * [send_devis_mail Send email to client if have email adresse]
     * @return [bol] [fil log]
     */
    private function send_proforma_mail()
    {
        //Get info proforma
        $this->Get_detail_proforma_show();
        $proforma_info = $this->proforma_info;
        if($this->g('email') == null)
        {
            $this->log .='<br/>Ce client n\'a pas une adresse Mail';
            return false;
        }
        //prepare all variables
        $ste_c = new MSte_info();
        $ste = $ste_c->get_ste_info_report_footer(1);

        $agent = new Musers();
        $agent->id_user = session::get('userid');
        $agent->get_user();

        $agent_name    = $agent->g('fnom').' '.$agent->g('lnom');
        $agent_service = $agent->g('service_user');
        $agent_tel     = $agent->g('tel');

        date_default_timezone_set('Etc/UTC');
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        $mail->addAddress($this->g('email'), $this->g('denomination'));
        if($this->attached != null)
        {
           $mail->addAttachment($this->attached, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment'); 
        }
        
        //Set the subject line
        $mail->Subject = 'Proforma Réf: #'.$this->g('reference');
        //Read an HTML message body from an external file, convert referenced images to embedded,
        $Msg_body = file_get_contents(MPATH_MSG.'template_send_proforma.php');
        $Msg_body = str_replace(
            array('%ste_footer%','%agent%', '%service%', '%agenttel%'), 
            array($ste, $agent_name, $agent_service, $agent_tel ), 
            $Msg_body);
        $mail->msgHTML($Msg_body);

        if (!$mail->send()) {
            $this->log .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $this->log .= "Devis envoyé  à ".$this->g('email');
        }
    }
/**
 * End Class destrector
 */
    public function __destruct(){
        //
        
    }
}