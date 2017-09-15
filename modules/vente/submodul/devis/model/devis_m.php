<?php

/**
* MDevis Gestion Devis et Détail 
* V1.0
*/
class MDevis
{
	//Declared Private
	private $_data; //data receive from form
    //Declared Variable
    var $table         = 'devis'; //Main table of module
    var $table_details = 'd_devis'; //Tables détails devis
    var $last_id      = null; //return last ID after insert command
	var $log          = null; //Log of all opération.
    var $id_devis     = null; // Devis ID append when request
	var $token        = null; //user for recovery function
	var $devis_info   = null; //Array stock all ville info
	var $devis_d_info = null;
    var $reference    = null; // Reference Devis
    var $error        = true; //Error bol changed when an error is occured
    var $total_ht_d   = null; 
    var $total_tva_d  = null;
    var $total_ttc_d  = null;
    var $total_ht_t   = null; 
    var $total_tva_t  = null;
    var $order_detail = null; 
    var $sum_total_ht = null;

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

    	$sql = "SELECT $table_details.* FROM $table_details WHERE $table_details.id = ".$this->id_devis_d;

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
        $colms .= " REPLACE(FORMAT($table.prix_unitaire,0),',',' '), ";
        //$colms .= " $table.type_remise, ";
        $colms .= " REPLACE(FORMAT($table.remise_valeur,0),',',' '), ";
        
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
        
        $style   = array('3#center', '7#', '40#', '5#center', '10#alignRight', '5#alignRight', '10#alignRight', '10#alignRight','10#alignRight');
        $headers = array('Item', 'Réf', 'Description', 'Qte', 'P.U', 'Remise', 'Total HT', 'TVA', 'Total TTC');

        $tableau = $db->GetMTable($headers,  null, $style);
        
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
        private function Make_devis_reference()
        {
        	if($this->error == false)
        	{
        		return false;
        	}
        	global $db;
        	$max_id = $db->QuerySingleValue0('SELECT MAX(id)+1 FROM devis');
        	$this->reference = 'DEV_'.$max_id.'_'.date('Y');
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
    /////////////////////////////////////////////////////////////////////////////////
        public function save_new_devis()
        {
      //Check if devis exist
        	$this->Check_devis_exist($this->_data['tkn_frm'], null);
      //Check if devis have détails
        	$this->Check_devis_have_details($this->_data['tkn_frm']);
      //Make reference
        	$this->Make_devis_reference();
        //Before execute do the multiple check
        	$this->Check_exist('reference', $this->reference, 'Réference Devis', null);

        	$this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );
      //Get sum of details
        	$this->Get_sum_detail($this->_data['tkn_frm']); 
      //calcul values devis
        	$this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva']);


      //Check $this->error (true / false)
        	if($this->error == false)
        	{
        		$this->log .='</br>Enregistrement non réussie';
        		return false;
        	}

		//Format values for Insert query 
        	global $db;
        	$totalht   = $this->total_ht_t;
        	$totaltva  = $this->total_tva_t;
        	$totalttc = $this->total_ttc_t;


        	$values["reference"]       = MySQL::SQLValue($this->reference);
        	$values["tkn_frm"]         = MySQL::SQLValue($this->_data['tkn_frm']);
        	$values["id_client"]       = MySQL::SQLValue($this->_data['id_client']);
            $values["tva"]             = MySQL::SQLValue($this->_data['tva']);
            $values["id_commercial"]   = MySQL::SQLValue(session::get('userid'));
            $values["date_devis"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_devis'])));
            $values["type_remise"]     = MySQL::SQLValue($this->_data['type_remise']);
            $values["valeur_remise"]   = MySQL::SQLValue($this->_data['valeur_remise']);
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
               $this->log = '</br>Enregistrement réussie: <b>Réference: '.$this->reference;
               $this->save_temp_detail($this->_data['tkn_frm'], $this->last_id);
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

    public function edit_exist_devis()
    {
    	$this->reference = $this->_data['reference'];
      //Check if devis exist
    	$this->Check_devis_exist($this->_data['tkn_frm'], 1);
      //Check if devis have détails
    	$this->Check_devis_have_details($this->_data['tkn_frm']);
      //Make reference
      //$this->Make_devis_reference();
        //Before execute do the multiple check
    	$this->Check_exist('reference', $this->reference, 'Réference Devis', 1);

    	$this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );
      //Get sum of details
    	$this->Get_sum_detail($this->_data['tkn_frm']); 
      //calcul values devis
    	$this->Calculate_devis_t($this->sum_total_ht, $this->_data['type_remise'], $this->_data['valeur_remise'], $this->_data['tva']);


      //Check $this->error (true / false)
    	if($this->error == false)
    	{
    		$this->log .='</br>Enregistrement non réussie';
    		return false;
    	}

    //Format values for Insert query 
    	global $db;
    	$totalht   = $this->total_ht_t;
    	$totaltva  = $this->total_tva_t;
    	$totalttc = $this->total_ttc_t;
    	$this->reference = $this->_data['reference'];


    	$values["reference"]       = MySQL::SQLValue($this->reference);
    	$values["tkn_frm"]         = MySQL::SQLValue($this->_data['tkn_frm']);
    	$values["id_client"]       = MySQL::SQLValue($this->_data['id_client']);
        $values["tva"]             = MySQL::SQLValue($this->_data['tva']);
        $values["id_commercial"]   = MySQL::SQLValue(session::get('userid'));
        $values["date_devis"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_devis'])));
        $values["type_remise"]     = MySQL::SQLValue($this->_data['type_remise']);
        $values["valeur_remise"]   = MySQL::SQLValue($this->_data['valeur_remise']);
        $values["claus_comercial"] = MySQL::SQLValue($this->_data['claus_comercial']);
        $values["totalht"]         = MySQL::SQLValue($totalht);
        $values["totalttc"]        = MySQL::SQLValue($totalttc);
        $values["totaltva"]        = MySQL::SQLValue($totaltva);
        $values["updusr"]  = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = ' CURRENT_TIMESTAMP ';
        $wheres["id"]     = MySQL::SQLValue($this->id_devis);
        //Check if Insert Query been executed (False / True)
        if (!$result = $db->UpdateRows($this->table, $values, $wheres)) 
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
             $this->save_temp_detail($this->_data['tkn_frm'], $this->id_devis);
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

    private function Calculate_devis_d($prix_u, $qte, $type_remise, $value_remise, $tva)
    {
    	if($type_remise == 'P')
    	{
    		$prix_u_remised = $prix_u - ($prix_u * $value_remise) / 100;

    	}else if($type_remise == 'M'){
    		$prix_u_remised = $prix_u - $value_remise;
    	}else{
    		$prix_u_remised = $prix_u;
    	}

      //Total HT 
    	$this->total_ht_d = $prix_u_remised * $qte;
      //Calculate TVA
    	if($tva == 'N')
    	{
    		$this->total_tva_d = 0;
    	}else{
            $this->total_tva_d = ($this->total_ht_d * 20) / 100; //TVA value get from app setting
        }
        $this->total_ttc = $this->total_ht_d + $this->total_tva_d;
        return true;

    }

    private function Calculate_devis_t($totalht, $type_remise, $value_remise, $tva)
    {
    	if($type_remise == 'P')
    	{
    		$totalht_remised = $totalht - ($totalht * $value_remise) / 100;

    	}else if($type_remise == 'M'){
    		$totalht_remised = $totalht - $value_remise;
    	}else{
    		$totalht_remised = $totalht;
    	}

      //Total HT 
    	$this->total_ht_t = $totalht_remised;
      //Calculate TVA
    	if($tva == 'N')
    	{
    		$this->total_tva_t = 0;
    	}else{
            $this->total_tva_t = ($this->total_ht_t * 20) / 100; //TVA value get from app setting
        }
        $this->total_ttc_t = $this->total_ht_t + $this->total_tva_t;

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


        //Check $this->error (true / false)
        if($this->error == true){
        //Calcul Montant
          $this->Calculate_devis_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva']);
          //Get produit info
            $produit             = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();

            $ref_produit         = $produit->produit_info['ref'];
            $designation         = $produit->produit_info['designation'];
          //Valeu finance
            $total_ht            = $this->total_ht_d;
            $total_tva           = $this->total_tva_d;
            $total_ttc           = $this->total_ttc_d;
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
            $values["prix_unitaire"] = MySQL::SQLValue($this->_data['prix_unitaire']);
            $values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["remise_valeur"] = MySQL::SQLValue($this->_data['remise_valeur_d']);
            $values["tva"]           = MySQL::SQLValue($this->_data['tva']);
            $values["total_ht"]      = MySQL::SQLValue($this->total_ht);
            $values["total_ttc"]     = MySQL::SQLValue($this->total_ttc);
            $values["total_tva"]     = MySQL::SQLValue($this->total_tva);
            $values["creusr"]        = MySQL::SQLValue(session::get('userid'));


        //Check if Insert Query been executed (False / True)
            if(!$result = $db->InsertRow($table_details, $values)){
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

    public function edit_exist_details_devis($tkn_frm)
    {
        $table_details = $this->table_details;
        $this->get_devis_d();
        if($this->h('id_produit') != $this->_data['id_produit'])
        {
            $this->check_detail_exist_in_devis($tkn_frm, $this->_data['id_produit'], 1); 
        }

        $this->check_non_exist('produits','id',$this->_data['id_produit'] ,'Réference du produit' );


        //Check $this->error (true / false)
        if($this->error == true)
        {
        //Calcul Montant
            $this->Calculate_devis_d($this->_data['prix_unitaire'], $this->_data['qte'], $this->_data['type_remise_d'], $this->_data['remise_valeur_d'], $this->_data['tva']);
        //Get produit info
            $produit             = new Mproduit();
            $produit->id_produit = MySQL::SQLValue($this->_data['id_produit']);
            $produit->get_produit();

            $ref_produit         = $produit->produit_info['ref'];
            $designation         = $produit->produit_info['designation'];
        //Valeu finance
            $total_ht            = $this->total_ht_d;
            $total_tva           = $this->total_tva_d;
            $total_ttc           = $this->total_ttc_d;
        //Format values for Insert query 
            global $db;

            $values["id_produit"]    = MySQL::SQLValue($this->_data['id_produit']);
            $values["ref_produit"]   = MySQL::SQLValue($ref_produit);
            $values["designation"]   = MySQL::SQLValue($designation);
            $values["qte"]           = MySQL::SQLValue($this->_data['qte']);
            $values["prix_unitaire"] = MySQL::SQLValue($this->_data['prix_unitaire']);
            $values["type_remise"]   = MySQL::SQLValue($this->_data['type_remise_d']);
            $values["remise_valeur"] = MySQL::SQLValue($this->_data['remise_valeur_d']);
            $values["tva"]           = MySQL::SQLValue($this->_data['tva']);
            $values["total_ht"]      = MySQL::SQLValue($this->total_ht);
            $values["total_ttc"]     = MySQL::SQLValue($this->total_ttc);
            $values["total_tva"]     = MySQL::SQLValue($this->total_tva);
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

    public function Valid_Devis($etat)
    {
        /*global $db;
        $table = $this->table;
        $id_devis = $this->id_devis;
        $req_sql = " UPDATE $table SET etat = $etat+1 WHERE id = $id_devis ";
        if (!$db->Query($req_sql)) {
            $this->error = false;
            $this->log .= "Erreur Validation";
            return false;
        }*/
        $id_devis = $this->id_devis;
        $Devis_pdf = new MDevis_PDF();
        $Devis_pdf->id_devis = $id_devis;
        if(!$Devis_pdf->generate_devis_pdf())
        {
            $this->log .= $Devis_pdf->log;
            return false;

        }else{
            $this->log .= "Validation réussie";
            return true;
        }

        
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
  if(!$db->DeleteRows('devis',$where))
  {

      $this->log .= $db->Error().'  '.$db->BuildSQLDelete('devis',$where);
      $this->error = false;
      $this->log .='</br>Suppression non réussie';

  }else{

      $this->error = true;
      $this->log .='</br>Suppression réussie ';
  }
    	//check if last error is true then return true else rturn false.
  if($this->error == false){
      return false;
  }else{
      return true;
  }
}



}