<?php

/**
* MDevis Gestion Devis et Détail 
* V1.0
*/
class Mdevis
{
	//Declared Private
	private $_data; //data receive from form
    //Declared Variable
    var $table            = 'devis'; //Main table of module
    var $table_details    = 'd_devis'; //Tables détails devis
    var $last_id          = null;//return last ID after insert command
    var $log              = null;//Log of all opération.
    var $id_devis         = null;// Devis ID append when request
    var $token            = null;//user for recovery function
    var $devis_info       = null;//Array stock all ville info
    var $devis_d_info     = null;//
    var $reference        = null;// Reference Devis
    var $error            = true;//Error bol changed when an error is occured
    var $valeur_remis_d   = null;//
    var $total_remise     = null;//
    var $prix_u_final     = null;//
    var $total_ht_d       = null;//
    var $total_tva_d      = null;//
    var $total_ttc_d      = null;//
    var $valeur_remis_t   = null;//
    var $total_ht_t       = null;// 
    var $total_tva_t      = null;//
    var $order_detail     = null; //
    var $sum_total_ht     = null;//
    var $arr_prduit       = array();
    var $attached         = null;
    var $type_devis       = null;//Type Devis (ABN / VNT)
    var $info_temp_client = array();
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


    public function get_devis()
    {
    	
        $table = $this->table;
    	global $db;

    	$sql = "SELECT $table.*, DATE_FORMAT($table.date_devis,'%d-%m-%Y') AS date_devis from $table where $table.id = ".$this->id_devis;

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

    //////////////////////////////////////////////////////////////////
    public function get_devis_d()
    {
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
        AND $table_details.id = ".$this->id_devis_d;

    	if(!$db->Query($sql))
    	{
    		$this->error = false;
    		$this->log  .= $db->Error().'  '.$sql;
    	}else{ 
    		if ($db->RowCount() == 0)
    		{
    			$this->error = false;
    			$this->log .= 'Aucun enregistrement trouvé ';
    		} else {
    			$this->devis_d_info = $db->RowArray();
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


    public function Get_detail_devis_show()
    {
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
        WHERE devis.id = ".$this->id_devis;
        if(!$db->Query($req_sql))
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
        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }

    }

    /**
     * [Get_detail_devis_pdf Render query for export PDF]
     * @return bol send to controller
     */
    public function Get_detail_devis_pdf()
    {
        global $db;

        $id_devis = $this->id_devis;
        $table    = $this->table_details;
        $this->Get_detail_devis_show();
        $devis_info = $this->devis_info;
        $colms  = null;
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
        
        $req_sql  = " SELECT $colms FROM $table WHERE id_devis = $id_devis ";
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

    public function Gettable_detail_devis()
    {
        global $db;
        $id_devis = $this->id_devis;
        $table    = $this->table_details;
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
        
        $req_sql  = " SELECT $colms FROM $table WHERE id_devis = $id_devis ";
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            exit($this->log);
        }
        
        
        $headers = array(
            'Item'        => '5[#]center',
            'Réf'         => '17[#]center',
            'Description' => '30[#]', 
            'Qte'         => '5[#]center', 
            'P.U'         => '10[#]alignRight', 
            'Re'          => '5[#]center',
            'Total HT'    => '12[#]alignRight',
            'TVA'         => '8[#]alignRight',
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
    /**
     * [check_date_devis description]
     * @param  [type] $date_devis [description]
     * @return [type]             [description]
     */
    Private function check_date_devis($date_devis)
    {
        $today = date('Y-m-d');
        
        $date_devis = date('Y-m-d', strtotime($date_devis));
        $date_devis_moin = date('Y-m-d', strtotime($today. ' - 3 days'));
        $date_devis_plus = date('Y-m-d', strtotime($today. ' + 3 days'));

        if($date_devis_moin > $date_devis OR $date_devis_plus < $date_devis)
        {
            $this->error = false;
            $this->log .='</br>La date devis ne doit pas dépasser un interval de 3 jours';
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
        private function Check_devis_exist($tkn_frm, $edit = null)
        {
        	global $db;
        	$count_id = $db->QuerySingleValue0("SELECT COUNT(id) FROM devis WHERE tkn_frm = '$tkn_frm'");
      //exit("0#".$count_id);
        	if(($count_id != '0' && $edit == Null ) OR ($count_id != '1' && $edit != null))
        	{
        		$this->error = false;
        		$this->log .='</br>Ce devis est déjà enregitré '.$count_id;
        	}
        }
        private function get_type_devis($tkn_frm)
        {
            if($this->error == false)
            {
                return false;
            }
            $table_details = $this->table_details;
            global $db;
            $req_sql = "SELECT COUNT($table_details.id_produit) FROM $table_details, produits, ref_types_produits WHERE tkn_frm = '$tkn_frm' AND  $table_details.id_produit = produits.id AND produits.idtype = ref_types_produits.id AND ref_types_produits.type_produit like 'Abonnement'";

            $count_id = $db->QuerySingleValue0($req_sql);
            if($count_id > 0) 
            {
                $this->type_devis = 'ABN';            
            }else{
                $this->type_devis = 'VNT';
            }  
            $this->error == true;
      }
    /////////////////////////////////////////////////////////////////////////////////
        public function save_new_devis()
        {
      //Check if devis exist
        	$this->Check_devis_exist($this->_data['tkn_frm'], null);
      //Check if devis have détails
        	$this->Check_devis_have_details($this->_data['tkn_frm']);
            //Check interval date devis
            $this->check_date_devis($this->_data['date_devis']);
        //Before execute do the multiple check
        	$this->Check_exist('reference', $this->reference, 'Réference Devis', null);

        	$this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );

            $this->check_non_exist('commerciaux','id',$this->_data['id_commercial'] ,'Commercial' );

      //Get sum of details
        	$this->Get_sum_detail($this->_data['tkn_frm']); 
      //calcul values devis
        	$this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva'],$this->_data['commission']);
            global $db;
            //Generate reference
            if(!$reference = $db->Generate_reference($this->table, 'DEV'))
            {
                $this->log .= '</br>Problème Réference';
                return false;
            }
            $this->get_type_devis($this->_data['tkn_frm']);  
      //Check $this->error (true / false)
        	if($this->error == false)
        	{
        		$this->log .='</br>Enregistrement non réussie';
        		return false;
        	}

		//Format values for Insert query 
        	$montant_remise = $this->sum_total_ht - $this->total_ht_t;
        	$totalht  = $this->total_ht_t;
        	$totaltva = $this->total_tva_t;
        	$totalttc = $this->total_ttc_t;
            $valeur_remise = $this->valeur_remis_t;
            $total_remise = $this->total_remise;
            $total_commission=$this->total_commission;


        	$values["reference"]       = MySQL::SQLValue($this->reference);
        	$values["tkn_frm"]         = MySQL::SQLValue($this->_data['tkn_frm']);
            $values["type_devis"]      = MySQL::SQLValue($this->type_devis);
            $values["reference"]       = MySQL::SQLValue($reference);
        	$values["id_client"]       = MySQL::SQLValue($this->_data['id_client']);
            $values["tva"]             = MySQL::SQLValue($this->_data['tva']);
            $values["id_commercial"]   = MySQL::SQLValue($this->_data['id_commercial']);
            $values["commission"]      = MySQL::SQLValue($this->_data['commission']);
            $values["total_commission"]= MySQL::SQLValue($total_commission);
            $values["date_devis"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_devis'])));
            $values["type_remise"]     = MySQL::SQLValue($this->_data['type_remise']);
            $values["valeur_remise"]   = MySQL::SQLValue($valeur_remise);
            $values["total_remise"]    = MySQL::SQLValue($montant_remise);
            $values["projet"]          = MySQL::SQLValue($this->_data['projet']);
            $values["vie"]             = MySQL::SQLValue($this->_data['vie']);
            $values["claus_comercial"] = MySQL::SQLValue($this->_data['claus_comercial']);
            $values["totalht"]         = MySQL::SQLValue($totalht);
            $values["totalttc"]        = MySQL::SQLValue($totalttc);
            $values["totaltva"]        = MySQL::SQLValue($totaltva);
            $values["creusr"]          = MySQL::SQLValue(session::get('userid'));
            $values["credat"]          = MySQL::SQLValue(date("Y-m-d H:i:s"));
        //Check if Insert Query been executed (False / True)
            if(!$result = $db->InsertRow($this->table, $values))
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
                    if(!Mlog::log_exec($this->table, $this->last_id, 'Enregistrement Devis '.$this->last_id, 'Insert'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
		   //Check $this->error = false return Red message and Bol false	
                }else{
                 $this->log .= '</br>Enregistrement réussie: <b>'.$reference;
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

    public function edit_exist_devis()
    {
    	//$this->reference = $this->_data['reference'];
        //Check if devis exist
    	$this->Check_devis_exist($this->_data['tkn_frm'], 1);
        //Check if devis have détails
    	$this->Check_devis_have_details($this->_data['tkn_frm']);
        //Make reference
        //$this->Make_devis_reference();
        //Before execute do the multiple check
    	$this->Check_exist('reference', $this->reference, 'Réference Devis', 1);

    	$this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );

        $this->check_non_exist('commerciaux','id',$this->_data['id_commercial'] ,'Commercial' );

        //Get sum of details
    	$this->Get_sum_detail($this->_data['tkn_frm']); 
        //calcul values devis
        
    	$this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva'],$this->_data['commission']);


        //Get Type devis
        $this->get_type_devis($this->_data['tkn_frm']);
        //Check $this->error (true / false)
    	if($this->error == false)
    	{
    		$this->log .='</br>Enregistrement non réussie';
    		return false;
    	}
        $this->get_devis();
        //Format values for Insert query 
    	global $db;
        $montant_remise = $this->sum_total_ht - $this->total_ht_t;
    	$totalht  = $this->total_ht_t;
    	$totaltva = $this->total_tva_t;
    	$totalttc = $this->total_ttc_t;
        $total_commission=$this->total_commission;
        $valeur_remise = number_format($this->valeur_remis_t, 2,'.', '');
        $this->reference = $this->devis_info['reference'];


        $values["reference"]       = MySQL::SQLValue($this->reference);
        $values["tkn_frm"]         = MySQL::SQLValue($this->_data['tkn_frm']);
        $values["type_devis"]      = MySQL::SQLValue($this->type_devis);
        $values["id_client"]       = MySQL::SQLValue($this->_data['id_client']);
        $values["tva"]             = MySQL::SQLValue($this->_data['tva']);
        $values["id_commercial"]   = MySQL::SQLValue($this->_data['id_commercial']);
        $values["commission"]      = MySQL::SQLValue($this->_data['commission']);
        $values["total_commission"]= MySQL::SQLValue($total_commission);
        $values["date_devis"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_devis'])));
        $values["type_remise"]     = MySQL::SQLValue($this->_data['type_remise']);
        $values["valeur_remise"]   = MySQL::SQLValue($valeur_remise);
        $values["total_remise"]   = MySQL::SQLValue($montant_remise);
        $values["projet"]          = MySQL::SQLValue($this->_data['projet']);
        $values["vie"]             = MySQL::SQLValue($this->_data['vie']);
        $values["claus_comercial"] = MySQL::SQLValue($this->_data['claus_comercial']);
        $values["totalht"]         = MySQL::SQLValue($totalht);
        $values["totalttc"]        = MySQL::SQLValue($totalttc);
        $values["totaltva"]        = MySQL::SQLValue($totaltva);
        $values["updusr"]          = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]          = ' CURRENT_TIMESTAMP ';
        $wheres["id"]              = MySQL::SQLValue($this->id_devis);
        //Check if Insert Query been executed (False / True)
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) 
        {
            //False => Set $this->log and $this->error = false
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .='</br>Modification BD non réussie'; 
        }else{
            $this->last_id = $this->id_devis;
            //Check $this->error = true return Green message and Bol true
            if($this->error == true)
            {
                $this->log = '</br>Modification réussie: <b>Réference: '.$this->reference;
                $this->save_temp_detail($this->_data['tkn_frm'], $this->id_devis);
                //log
                if(!Mlog::log_exec($this->table, $this->last_id, 'Modification Devis '.$this->last_id, 'Update'))
                {
                    $this->log .= '</br>Un problème de log ';
                }
                //Spy
                if(!$db->After_update($this->table, $this->last_id, $values, $this->devis_info))
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


    private function Calculate_devis_d($prix_u, $qte, $type_remise, $value_remise, $tva)
    {
        
        $val_remise = $value_remise == null ? '0' : $value_remise;
        
    	if($type_remise == 'P')
    	{
    		$prix_u_remised = $prix_u - ($prix_u * $val_remise) / 100;
            $this->valeur_remis_d = $val_remise;

    	}else if($type_remise == 'M'){
    		$prix_u_remised = $prix_u - $val_remise;
            $this->valeur_remis_d = ($val_remise * 100) / $prix_u;
    	}else{
    		$prix_u_remised = $prix_u;
    	}
        //TVA value get from app setting
        $tva_value = Msetting::get_set('tva');
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
        $this->total_ttc_d = $this->total_ht_d + $this->total_tva_d;

        
        /*$arr = array('Pu' => $prix_u, 'qte' => $qte, 'typ_remise' => $type_remise, 'val_remise' => $val_remise, 'prix_u_remised' => $prix_u_remised, 'tva' => $tva, 'total_tva' => $this->total_tva_d, 'total_ht_d' => $this->total_ht_d, 'total_ttc_d' => $this->total_ttc_d);
        var_dump($arr);
        exit();*/
        return true;

    }

    private function Calculate_devis_t($totalht, $type_remise, $value_remise, $tva,$commission)
    {
    	if($type_remise == 'P')
    	{
    		$totalht_remised = $totalht - ($totalht * $value_remise) / 100;
            $this->valeur_remis_t = $value_remise;
            $this->total_remise = ($totalht * $value_remise) / 100;

    	}elseif($type_remise == 'M'){
    		$totalht_remised = $totalht - $value_remise;
            $this->valeur_remis_t = ($value_remise * 100) / $totalht;
            $this->total_remise = $value_remise;

    	}else{
    		$totalht_remised = $totalht;
    	}

      //Valeur remised en percentage
      
      //Total HT 
    	$this->total_ht_t = $totalht_remised;
      //TVA value get from app setting
        $tva_value = Msetting::get_set('tva');
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

    private function check_detail_exist_in_devis($tkn_frm, $id_produit)
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
          $this->log .= '</br>Ce produit / service exist déjà dans la liste de ce devis';
        }
    }

    private function check_detail_have_more_abn($tkn_frm)
    {
        if($this->error == false)
        {
            return false;
        }
        $table_details = $this->table_details;
        global $db;
        $req_sql = "SELECT COUNT($table_details.id_produit) FROM $table_details, produits, ref_types_produits WHERE tkn_frm = '$tkn_frm' AND  $table_details.id_produit = produits.id AND produits.idtype = ref_types_produits.id AND ref_types_produits.type_produit like 'Abonnement'";
         
        $count_id = $db->QuerySingleValue0($req_sql);
        if($count_id > 0) 
        {
          $this->error = false;
          $this->log .= '</br>Impossible d\'insérer deux abonnement sur le même devis';
        }
    }

    private function Check_devis_have_details($tkn_frm)
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

    private function save_temp_detail($tkn_frm, $id_devis)
    {

        $table_details = $this->table_details;
        global $db;
        $req_sql = "UPDATE $table_details SET id_devis = $id_devis WHERE tkn_frm = '$tkn_frm'";
        if(!$db->Query($req_sql))
        {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '<br>Problème Enregistrement détails dans le devis';
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
            $this->log .= '<\br>Problème Enregistrement détails dans le devis';
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

    public function save_new_details_devis($tkn_frm)
    {

        $table_details = $this->table_details;
        $this->check_detail_exist_in_devis($tkn_frm, $this->_data['id_produit']);
        $this->check_non_exist('produits','id',$this->_data['id_produit'] ,'Réference du produit' );
        $this->check_detail_have_more_abn($tkn_frm);
        //var_dump($this->_data['commission']);
        //Check $this->error (true / false)
        if($this->error == true){
        //Calcul Montant
            $this->Calculate_devis_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva_d']);
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
            
            $valeur_remis_d = number_format($this->valeur_remis_d, 2,'.', '');
            $prix_u_final   = $this->prix_u_final;
          //Get order line into devis
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
            //$this->_data['prix_unitaire']);
            $values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["prix_ht"]       = MySQL::SQLValue($prix_u_final);
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
                    //log
                    if(!Mlog::log_exec($table_details, $this->last_id, 'Enregistrement Détail Devis '.$this->last_id, 'Insert'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
                    

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

    public function edit_exist_details_devis($tkn_frm)
    {
        $table_details = $this->table_details;
        $this->get_devis_d();
        if($this->h('id_produit') != $this->_data['id_produit'])
        {
            $this->check_detail_exist_in_devis($tkn_frm, $this->_data['id_produit'], 1);
            $this->check_detail_have_more_abn($tkn_frm); 
        }

        $this->check_non_exist('produits','id',$this->_data['id_produit'] ,'Réference du produit' );


        //Check $this->error (true / false)
        if($this->error == true)
        {
        //Calcul Montant
            $this->Calculate_devis_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva_d']);
        //Get produit info
            $produit             = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();

            $ref_produit         = $produit->produit_info['reference'];
            $designation         = $produit->produit_info['designation'];
        //Valeu finance
            $total_ht            = $this->total_ht_d;

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
            //$values["prix_unitaire"] = MySQL::SQLValue($this->_data['prix_unitaire']);
            $values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["remise_valeur"] = MySQL::SQLValue($valeur_remis_d);
            $values["prix_ht"]       = MySQL::SQLValue($prix_u_final);
            $values["tva"]           = MySQL::SQLValue($this->_data['tva_d']);
            $values["total_ht"]      = MySQL::SQLValue($total_ht);
            $values["total_ttc"]     = MySQL::SQLValue($total_ttc);
            $values["total_tva"]     = MySQL::SQLValue($total_tva);
            $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
            $values["upddat"]        = ' CURRENT_TIMESTAMP ';
            $wheres["id"]            = MySQL::SQLValue($this->id_devis_d);
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
                    $this->log = '</br>Modification réussie: <b>'.$this->_data['ref_produit'].' ID: '.$this->id_devis_d;

                    $this->Get_sum_detail($tkn_frm);
                    //log if is edit main devis
                    if($this->devis_d_info['id_devis'] != null)
                    {
                        if(!Mlog::log_exec($this->table, $this->id_devis, 'Modification Détail Devis '.$this->devis_d_info['id_devis'], 'Update'))
                        {
                            $this->log .= '</br>Un problème de log ';
                        
                        }
                        //Spy
                        if(!$db->After_update($table_details, $this->id_devis_d, $values, $this->devis_d_info))
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
    private function check_client_temp($id_client)
    {
        global $db;
        $type_client = $db->QuerySingleValue0("SELECT type_client FROM clients  WHERE id = $id_client");

        if($type_client == 'T')
        {
            $this->log .='</br>Le client est temporaire</br>Vous devez complèter le profile de client ' .$type_client;
            return false;
        }else{
            return true;
        }

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
    public function validdevisclient_devis()
    {
        $this->get_devis();
        $reponse = $this->_data['reponse'];
        $ref_bc = null;
        switch ($reponse) {
            case 'valid':
                $etat = 'valid_client';
                $ref_bc = " , ref_bc = '".$this->_data['ref_bc']."'";
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
        global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;
        $new_etat = Msetting::get_set('etat_devis', $etat);
        if($etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => $etat";
            return false;
        }
        $id_client = $this->devis_info['id_client'];
        if($etat == 'valid_client' and !$this->check_client_temp($id_client)){
            $this->error = false;
            return false;
        }
        $req_sql = " UPDATE $table SET etat = $new_etat  $ref_bc WHERE id = $id_devis ";
        
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Opération";
            return false;
        }else{
            //log
            if(!Mlog::log_exec($table, $this->id_devis, $message.' #Devis:'.$this->id_devis, 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                        
            }
            $this->last_id = $this->_data['id'];
            $this->save_file('scan', 'PJ réponse devis '.$this->_data['id'], 'Document');
            $this->log .= '</br>Opération réussie ';
            $this->get_devis();
            //If TYPE Devis is VNT then Generate Facture
            
            if($this->g('type_devis') == 'VNT' && $reponse == 'valid')
            {
                $this->generate_facture($this->id_devis);
            }
            
        }
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }
    Private function generate_facture($id_devis)
    {
        global $db;
        $sql_req = " CALL generate_devis_fact($id_devis)";
        if(!$db->Query($sql_req))
        {
            $this->log .= '</br>Erreur génération de facture'.$sql_req;
        }
    }
    /**
     * [generate_devis_pdf Generate PDF for store and send to client]
     * @return [type] [description]
     */
    Private function generate_devis_pdf()
    {
        $file_tplt = MPATH_THEMES.'pdf_template/devis_pdf.php';
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
        $folder        = MPATH_UPLOAD.SLASH.'devis/mois_'.date('m_Y').SLASH.$this->g('id');
        $id_line       = $this->g('id');
        $title         = 'Devis '.$this->g('id').' #'.$this->g('reference');
        $table         = $this->table;
        $column        = 'devis_pdf';
        $type          = 'Document';


         
        //Call save_file_upload from initial class
        if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'devis', $table, $column, $type, $edit = null))
        {
            $this->error = false;
            $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
        }
        $this->attached = $folder.SLASH.$new_name_file.'.pdf';
        
        
    }
    /**
     * [senddevis_to_client Basclly send devis to client and generat PDF]
     * @param [type] $etat [description]
     */
    public function senddevis_to_client($etat)
    {
        //Send to client
        $old_etat = Msetting::get_set('etat_devis', 'valid_devis');
        if($old_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis";
            return false;
        }
        if($etat != $old_etat)
        {
            $this->error = false;
            $this->log .= "Ce devis ne peux pas être envoyé pour le momement #Etat faild";
            return false;
        }
        global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;
        
        $new_etat = Msetting::get_set('etat_devis', 'send_devis');
        if($new_etat == null)
        {
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
        
        if(Msetting::get_set('send_mail_devis') == true){
            
            $this->send_devis_mail();
        }        
        //log
        if(!Mlog::log_exec($table, $this->id_devis, 'Expédition devis '.$this->id_devis, 'Update'))
        {
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
    public function valid_devis($etat)
    {
        $old_etat = Msetting::get_set('etat_devis', 'creat_devis');
        if($old_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => creat_devis";
            return false;
        }
        if($etat != $old_etat)
        {
            $this->error = false;
            $this->log .= "Ce devis ne peux pas être validé //Etat faild";
            return false;
        }
        global $db;
        $table    = $this->table;
        $id_devis = $this->id_devis;
        
        $new_etat = Msetting::get_set('etat_devis', 'valid_devis');
        if($new_etat == null)
        {
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
            if(!Mlog::log_exec($table, $this->id_devis, 'Validation devis '.$this->id_devis, 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                        
            }
            $this->log .= "<br/>Opération réussie";
            return true;
        
    }
    /**
     * [debloqdevis description]
     * @param  [type] $etat [description]
     * @return [type]       [description]
     */
    public function debloqdevis($etat)
    {
        $old_etat = Msetting::get_set('etat_devis', 'modif_client');
        if($old_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => modif_client";
            return false;
        }
        if($etat != $old_etat)
        {
            $this->error = false;
            $this->log .= "Ce devis ne peux pas être débloqué //Etat faild";
            return false;
        }
        $new_etat = Msetting::get_set('etat_devis', 'creat_devis');
        if($new_etat == null)
        {
            $this->error = false;
            $this->log .= "Manque paramètre etat_devis => valid_devis";
            return false;
        }
        global $db;
        $table    = $this->table;
        $id_devis = $this->id_devis;
        $doc      = $this->g('devis_pdf');
        
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
            if(!Mlog::log_exec($table, $this->id_devis, 'Débloquer devis '.$this->id_devis, 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                        
            }
            return true;
        
    }

    public function Delete_detail_devis($id_detail)
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

            $this->log .= $db->Error().'  '.$db->BuildSQLDelete('devis',$where);
            $this->error = false;
            $this->log .='</br>Suppression non réussie';

        }else{

            $this->error = true;
            $this->log .='</br>Suppression réussie ';
            $this->Get_sum_detail($get_tkn_frm);
            $this->log .='#'.$this->sum_total_ht;
            //log if is edit main devis
            if($this->devis_d_info['id_devis'] != null)
            {
                if(!Mlog::log_exec($table, $this->id_devis, 'Suppression détail devis '.$this->devis_d_info['id_devis'], 'Delete'))
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

    // afficher les infos d'un devis
    public function s($key)
    {
        if($this->devis_info[$key] != null)
        {
            echo $this->devis_info[$key];
        }else{
            echo "";
        }

    }
  // get les infos d'un devis
    public function g($key)
    {
        if($this->devis_info[$key] != null)
        {
            return $this->devis_info[$key];
        }else{
            return null;
        }

    }

    // afficher les infos d'un devis_d
    public function c($key)
    {
        if($this->devis_d_info[$key] != null)
        {
            echo $this->devis_d_info[$key];
        }else{
            echo "";
        }

    }
    //get les infos d'un devis_d
    public function h($key)
    {
        if($this->devis_d_info[$key] != null)
        {
            return $this->devis_d_info[$key];
        }else{
            return null;
        }

    }

    public function delete_devis()
    {
        global $db;
        $id_devis = $this->id_devis;
        $table = $this->table;
        $this->get_devis();
    	//Format where clause
        $where['id'] = MySQL::SQLValue($id_devis);
    	//check if id on where clause isset
        if($where['id'] == null)
        {
            $this->error = false;
            $this->log .='</br>L\' id est vide';
        }
    	//execute Delete Query
        if(!$db->DeleteRows($table, $where))
        {

            $this->log .= $db->Error().'  '.$db->BuildSQLDelete('devis',$where);
            $this->error = false;
            $this->log .='</br>Suppression non réussie';

        }else{

            $this->error = true;
            $this->log .='</br>Suppression réussie ';
            //log
            if(!Mlog::log_exec($table, $this->id_devis, 'Suppression devis '.$this->id_devis, 'Delete'))
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
            if($this->arr_prduit['type_produit'] == 'Abonnement'){
                $this->arr_prduit['abn']= true;
            }
            if($this->arr_prduit['prix_vente'] == null)
            {
                $this->arr_prduit = array('error' => "Prix de produit n'est pas enregitré");
            }
        }
        return true;
    }

    /**
     * [save_file For save anattached file for entrie ]
     * @param  [string] $item  [input_name of attached file we add _id]
     * @param  [string] $titre [Title stored for file on Archive DB]
     * @param  [string] $type  [Type of file (Document, PDF, Image)]
     * @return [Setting]       [Set $this->error and $this->log]
     */
    private function save_file($item, $titre, $type)
    {

        //Format all parameteres
        $temp_file     = $this->_data[$item.'_id'];
        //If nofile uploaded return kill function
        
        if($temp_file == Null){
            return true;
        }

        $new_name_file = $item.'_'.$this->last_id;
        $folder        = MPATH_UPLOAD.SLASH.'devis/mois_'.date('m_Y').SLASH.$this->last_id;
        $id_line       = $this->last_id;
        $title         = $titre;
        $table         = $this->table;
        $column        = $item;
        $type          = $type;


         
        //Call save_file_upload from initial class
        if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'devis', $table, $column, $type, $edit = null))
        {
            $this->error = false;
            $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
        }
    }

    /**
     * [check_file Check attached if required stop Insert this must be placed befor Insert commande]
     * @param  [string] $item [input_name of attached file we add _id]
     * @param  [string] $msg  [description]
     * @param  [int] $edit    [Used if is edit action must be the ID of row edited]
     * @return [Setting]      [Set $this->error and $this->log]
     */
    Private function check_file($item, $msg = null, $edit = null)
    {
        //Format temporary file
        $temp_file     = $this->_data[$item.'_id'];
        //Check if is edit action (is numeric when called from archive DB else is retrned target upload)
        if($edit != null && !is_numeric($temp_file))
        {
            if(!file_exists($temp_file))
            {
                $this->log .= '</br>Il faut choisir '.$msg.' pour la mise à jour '.$edit;
                $this->error = false;
            }
        //When is not edit do check for existing file
        }else{
            if($edit == null && $this->exige_.$item == true && ($this->_data[$item.'_id'] == null || !file_exists($this->_data[$item.'_id'])))
            {
                $this->log .= '</br>Il faut choisir '.$msg. '  '.$edit;
                $this->error = false; 
            }
        }

    }

    /**
     * [send_devis_mail Send email to client if have email adresse]
     * @return [bol] [fil log]
     */
    private function send_devis_mail()
    {
        //Get info devis
        $this->Get_detail_devis_show();
        $devis_info = $this->devis_info;
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
        $mail->Username = Msetting::get_set('mail_comercial','user');
        $mail->Password = Msetting::get_set('mail_comercial','pass');
        
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        $mail->addAddress($this->g('email'), $this->g('denomination'));
        if($this->attached != null)
        {
           $mail->addAttachment($this->attached, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment'); 
        }
        
        //Set the subject line
        $mail->Subject = 'Devis Réf: #'.$this->g('reference');
        //Read an HTML message body from an external file, convert referenced images to embedded,
        $Msg_body = file_get_contents(MPATH_MSG.'template_send_devis.php');
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

    public function save_new_client_temp()
    {
        global $db;

        if(!$reference = $db->Generate_reference('clients', 'CLT'))
        {
            $this->log .= '</br>Problème Réference';
            return false;
        }
        $values["reference"]    = MySQL::SQLValue($reference);
        $values["denomination"] = MySQL::SQLValue($this->_data['denomination']);
        $values["type_client"]  = MySQL::SQLValue('T');
        $values["adresse"]      = MySQL::SQLValue($this->_data['adresse']);
        $values["tel"]          = MySQL::SQLValue($this->_data['tel']);
        $values["email"]        = MySQL::SQLValue($this->_data['email']);
        $values["creusr"]       = MySQL::SQLValue(session::get('userid'));
        

        //Check if Insert Query been executed (False / True)
        if (!$result = $db->InsertRow("clients", $values)){
                //False => Set $this->log and $this->error = false
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .='</br>Enregistrement BD non réussie'; 

        }else{
            $this->info_temp_client  = array('mess' => 'Client '.$this->_data['denomination'].' a était ajouté','nom' => $this->_data['denomination'], 'id' => $result );
            $this->error = true;
        }

        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }



/**
 * End Class destrector
 */
    public function __destruct(){
        //
        
    }
}