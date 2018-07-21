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
  var $devis_info; //Array stock all devis info
  var $abn_info; //Array stock all abonnement info
  var $bl_info; //Array stock all bl info
  var $factures_info; //Array stock all factures info
  var $enc_info; //Array stock all encaissement info
  var $tot_devis_info; //Array stock total devis info
  var $tot_factures_info; //Array stock total factures info
  var $tot_enc_info; //Array stock total encaissement info
  


	public function __construct($properties = array()){
		$this->_data = $properties;
	}

    // magic methods!
	public function __set($property, $value){
		return $this->_data[$property] = $value;
	}

	public function __get($property){
		return array_key_exists($property, $this->_data)
		? $this->_data[$property]
		: null
		;
	}

	//Get all info categorie_client from database for edit form

	public function get_client()
	{
		global $db;

		$sql = "SELECT  c.*,cat.categorie_client as categorie_client, p.pays as pays,v.ville as ville, d.devise as devise,d.abreviation as dev, IF(c.tva='O','Oui','Non') AS tva,c.tva as tva_brut, m.motif as motif,DATE_FORMAT(c.`date_blocage`,'%d-%m-%Y') AS date_blocage FROM  clients c
             LEFT JOIN categorie_client cat on c.id_categorie=cat.id 
             LEFT JOIN ref_pays p on c.id_pays=p.id 
             LEFT JOIN ref_ville v on c.id_ville=v.id
             LEFT JOIN ref_devise d on c.id_devise=d.id
             LEFT JOIN ref_motif_blocage m on c.id_motif_blocage=m.id and m.type='C'
             WHERE c.id = ".$this->id_client;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->client_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array client_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
		}
		
	}
   //Return the list of a client devis

    public function get_list_devis()
    {
      
        $table = "devis";
      global $db;

      $sql =//"SELECT $table.*, DATE_FORMAT($table.date_devis,'%d-%m-%Y') AS date_devis from $table where $table.id_client = ".$this->id_client;
      "SELECT d.`id`,d.`reference`,DATE_FORMAT(d.`date_devis`,'%d-%m-%Y') AS date_devis,CONCAT(c.`nom`,' ',c.`prenom`) as commercial,REPLACE(FORMAT(d.`totalht`,0),',',' '),REPLACE(FORMAT(d.`totaltva`,0),',',' '),REPLACE(FORMAT(d.`total_remise`,0),',',' '),REPLACE(FORMAT(d.`totalttc`,0),',',' ') FROM devis d, commerciaux c WHERE c.`id`=d.`id_commercial` and d.`id_client` = ".$this->id_client." order by d.date_devis desc";

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
          $this->devis_info = $db->RecordsSimplArray();
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

//Return the list of a Total devis

    public function get_total_devis()
    {
      
        $table = "devis";
      global $db;

      $sql ="SELECT IFNULL(REPLACE(FORMAT(SUM(d.`totalht`),0),',',' '),0) as totalht,IFNULL(REPLACE(FORMAT(SUM(d.`totalttc`),0),',',' '),0)as totalttc FROM devis d WHERE d.`id_client` = ".$this->id_client;

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
          $this->tot_devis_info = $db->RowArray();
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

    //Return the list of a client bl

    public function get_list_bls()
    {
      
      global $db;

      $sql ="SELECT bl.`id`,bl.`reference`,DATE_FORMAT(bl.`date_bl`,'%d-%m-%Y') AS date_bl,bl.`projet`FROM devis d, bl WHERE bl.`iddevis`=d.`id` AND d.`id_client` = ".$this->id_client." order by bl.date_bl desc";

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
          $this->bl_info = $db->RecordsSimplArray();
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


//Return the list of a Total factures

    public function get_total_fact()
    {
      
        $table = "factures";
      global $db;

      $sql ="SELECT IFNULL(REPLACE(FORMAT(SUM(factures.`total_ht`),0),',',' '),0) as totalht,IFNULL(REPLACE(FORMAT(SUM(factures.`total_ttc`),0),',',' '),0)as totalttc,IFNULL(REPLACE(FORMAT(SUM(factures.`total_paye`),0),',',' '),0) as paye,IFNULL(REPLACE(FORMAT(SUM(factures.`reste`),0),',',' '),0)as reste FROM factures,devis d  WHERE  IF(factures.`base_fact`='C',(factures.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE factures.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client) ),(factures.iddevis=d.id )) AND d.`id_client` = ".$this->id_client;

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
          $this->tot_factures_info = $db->RowArray();
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

    //Return the list of a client abonnement

    public function get_list_abn()
    {
      
        $table = "contrats";
      global $db;

      $sql ="SELECT c.`id`,c.`reference`,DATE_FORMAT(c.`credat`,'%d-%m-%Y') AS date_abn,e.`type_echeance`, DATE_FORMAT(c.`date_effet`,'%d-%m-%Y')AS date_effet, DATE_FORMAT(c.`date_fin`,'%d-%m-%Y') AS date_fin,c.`pj` FROM contrats c,ref_type_echeance e WHERE e.`id`=c.`idtype_echeance` AND c.`iddevis`IN(SELECT id FROM devis d WHERE d.`id_client`= ".$this->id_client.") ORDER BY c.credat DESC";

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
          $this->abn_info = $db->RecordsSimplArray();
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

    //Return the list of a client factures

    public function get_list_factures()
    {
      
        $table = "factures";
      global $db;

      $sql ="SELECT factures.id,factures.reference,DATE_FORMAT(factures.date_facture,'%d-%m-%Y'),IF(factures.`base_fact`='C','Contrat',IF(factures.`base_fact`='D','Devis','BL')) AS base_fact,REPLACE(FORMAT(factures.total_ht,0),',',' '),REPLACE(FORMAT(factures.total_ttc,0),',',' '),REPLACE(FORMAT(factures.total_tva,0),',',' '),REPLACE(FORMAT(factures.total_paye,0),',',' '),REPLACE(FORMAT(factures.reste,0),',',' ') FROM factures,clients c,devis d WHERE   IF(factures.`base_fact`='C',( factures.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE factures.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client=c.id ) ), (factures.iddevis=d.id AND d.id_client=c.id )) and c.id=" .$this->id_client." ORDER BY factures.credat DESC";

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
          $this->factures_info = $db->RecordsSimplArray();
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
    //Return the list of a client encaissements

    public function get_list_encaissements()
    {
      
        $table = "encaissements";
      global $db;

      $sql ="SELECT e.id,e.`reference`,designation ,depositaire ,e.`date_encaissement`,ref_payement, e.`mode_payement`,IFNULL(REPLACE(FORMAT((e.`montant`),0),',',' '),0) AS montant ,pj FROM encaissements e,factures f,devis d  WHERE  f.`id`=e.`idfacture` AND IF(f.`base_fact`='C',( f.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE f.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client) ), (f.iddevis=d.id )) AND d.`id_client`=" .$this->id_client." ORDER BY e.date_encaissement DESC";

      if(!$db->Query($sql))
      {
        $this->error = false;
        $this->log  .= $db->Error();
        //var_dump($this->log );
      }else{
        if ($db->RowCount() == 0)
        {
          $this->error = false;
          $this->log .= 'Aucun enregistrement trouvé ';
        } else {
          $this->enc_info = $db->RecordsSimplArray();
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
   //Return the list of a Total encaissements

    public function get_total_enc()
    {
      
      global $db;

      $sql ="SELECT IFNULL(REPLACE(FORMAT(SUM(e.`montant`),0),',',' '),0) AS total_enc FROM encaissements e,factures f,devis d  WHERE  f.`id`=e.`idfacture` AND IF(f.`base_fact`='C',(f.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE f.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client) ),(f.iddevis=d.id )) AND d.`id_client` = ".$this->id_client;

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
          $this->tot_enc_info = $db->RowArray();
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
		 	$sql_edit = $edit == null ? null: " AND id <> $edit";
		 	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
		 		WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

		 	if ($result != "0") {
		 		$this->error = false;
		 		$this->log .='</br>'.$message.' existe déjà';
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
    private function check_non_exist($table, $column, $value, $message)
    {
    	global $db;
    	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
    		WHERE $table.$column = ". MySQL::SQLValue($value));
    	if ($result == "0") {
        var_dump('here');
    		$this->error = false;
    		$this->log .='</br>'.$message.' n\'exist pas';
    		//exit('0#'.$this->log);
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
    public function save_new_client(){

      /*  //Generate reference
        $this->Generate_client_reference();*/
        global $db;

        //Generate reference
        if(!$reference = $db->Generate_reference($this->table, 'CLT'))
        {
                $this->log .= '</br>Problème Réference';
                return false;
        }  

        //Before execute do the multiple check

        $this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', null);

        $this->Check_exist('reference', $this->reference, 'Réference Client', null);

        $this->Check_exist('r_social', $this->_data['r_social'], 'Raison Sociale', null);
             
        $this->Check_exist('r_commerce', $this->_data['r_commerce'], 'N° de registre', null);           
       
        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', null);

		
    	$this->check_non_exist('categorie_client','id',$this->_data['id_categorie'] ,'Catégorie' );

    	$this->check_non_exist('ref_pays','id', $this->_data['id_pays'], 'Pays');

        if($this->_data['id_ville'] != NULL)
        {
              $this->check_non_exist('ref_ville','id', $this->_data['id_ville'], 'Ville');
        }

        $this->check_non_exist('ref_devise','id', $this->_data['id_devise'], 'Devise');

    	  //Check if PJ attached required
        if($this->exige_pj)
        {
            $this->check_file('pj', 'Justifications du client.');
        }
          //Check if PJ attached required
        if($this->exige_pj_photo)
        {
            $this->check_file('pj_photo', 'La photo du client.');
        }

        //Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
    	global $db;

   		$values["reference"]  	 = MySQL::SQLValue($reference);
   		$values["denomination"]  = MySQL::SQLValue($this->_data['denomination']);
   		$values["id_categorie"]  = MySQL::SQLValue($this->_data['id_categorie']);
   		$values["r_social"] 	 = MySQL::SQLValue($this->_data['r_social']);
   		$values["r_commerce"]    = MySQL::SQLValue($this->_data['r_commerce']);
   		$values["nif"]   		 = MySQL::SQLValue($this->_data['nif']);
   		$values["nom"]  		 = MySQL::SQLValue($this->_data['nom']);
   		$values["prenom"]	     = MySQL::SQLValue($this->_data['prenom']);
   		$values["civilite"]      = MySQL::SQLValue($this->_data['civilite']);
   		$values["adresse"] 		 = MySQL::SQLValue($this->_data['adresse']);
    	$values["id_pays"]  	 = MySQL::SQLValue($this->_data['id_pays']);
        $values["id_ville"]  = MySQL::SQLValue($this->_data['id_ville']);
        /*
        if($this->_data['id_ville'] == '------')
        {
            NULL;
        }    
        else{
            $values["id_ville"]  = MySQL::SQLValue($this->_data['id_ville']);
        }
         * 
         */
    	$values["tel"] 		 	 = MySQL::SQLValue($this->_data['tel']);
    	$values["fax"] 			 = MySQL::SQLValue($this->_data['fax']);
   		$values["bp"] 			 = MySQL::SQLValue($this->_data['bp']);
   		$values["email"]	     = MySQL::SQLValue($this->_data['email']);
   		$values["rib"]  		 = MySQL::SQLValue($this->_data['rib']);
    	$values["id_devise"]  	 = MySQL::SQLValue($this->_data['id_devise']);
      if($this->_data['tva']=='Oui'){
      
      $values["tva"]           = MySQL::SQLValue('O');
      
      }else{

      $values["tva"]           = MySQL::SQLValue('N');

      }

    	$values["creusr"]      	 = MySQL::SQLValue(session::get('userid'));
    	$values["credat"]      	 = MySQL::SQLValue(date("Y-m-d H:i:s"));
/*       var_dump($this->_data);
       var_dump($values["tva"]);*/

    	//Check if Insert Query been executed (False / True)
			if (!$result = $db->InsertRow($this->table, $values)){
				//False => Set $this->log and $this->error = false
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				//If Attached required Save file to Archive
				
                $this->save_file('pj', 'Justifications du clients'.$this->_data['denomination'], 'Document');
				
				$this->save_file('pj_photo', 'Photo du client'.$this->_data['denomination'], 'Image');
								
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Enregistrement réussie: <b>'.$this->_data['denomination'].' ID: '.$this->last_id;
                    
                    if(!Mlog::log_exec($this->table, $this->last_id, 'Création client', 'Insert'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
				//Check $this->error = false return Red message and Bol false	
				}else{
					$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['denomination'];
                    
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
	

    //activer ou desactiver un client
    public function valid_client($etat = 0)
    {
    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 		= MySQL::SQLValue($etat);
    $values["type_client"]     = MySQL::SQLValue('D');
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_client;

        // Execute the update and show error case error
		if( !$result = $db->UpdateRows($this->table, $values , $where))
		{
			$this->log .= '</br>Impossible de changer le statut!';
			$this->log .= '</br>'.$db->Error();
			$this->error = false;
		}else{
			$this->log .= '</br>Statut changé! ';
			$this->error = true;
            if(!Mlog::log_exec($this->table, $this->id_client, 'Validation client', 'Validate'))
            {
                $this->log .= '</br>Un problème de log ';
            }

		} 
		if($this->error == false){
			return false;
		}else{
			return true;
		}



    }

  // afficher les infos d'un client
    public function g($key)
    {
        if($this->client_info[$key] != null)
        {
            return $this->client_info[$key];
        }else{
            return null;
        }

    }

	// afficher les infos d'un client
    public function s($key)
    {
    	if($this->client_info[$key] != null)
    	{
    		echo $this->client_info[$key];
    	}else{
    		echo "";
    	}

    }
  
    // afficher les infos d'un client
    public function Shw($key,$no_echo = "")
    {
    	if($this->client_info[$key] != null)
    	{
    		if($no_echo != null)
    		{
    			return $this->client_info[$key];
    		}

    		echo $this->client_info[$key];
    	}else{
    		echo "";
    	}

    }

	//Edit categorie_client after all check
    public function edit_client(){

		//Get existing data for categorie_client
    	$this->get_client();

    	$this->last_id = $this->id_client;
        
        $this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', $this->id_client);

        //$this->Check_exist('code', $this->_data['code'], 'Code Fournisseur', null);

        $this->Check_exist('r_social', $this->_data['r_social'], 'Raison Sociale', $this->id_client);
             
        $this->Check_exist('r_commerce', $this->_data['r_commerce'], 'N° de registre', $this->id_client);           
       
        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', $this->id_client);


        $this->check_non_exist('categorie_client','id',$this->_data['id_categorie'] ,'Catégorie' );

        $this->check_non_exist('ref_pays','id', $this->_data['id_pays'], 'Pays');

      

        if($this->_data['id_ville'] != NULL)
        {
              $this->check_non_exist('ref_ville','id', $this->_data['id_ville'], 'Ville');
        }


        $this->check_non_exist('ref_devise','id', $this->_data['id_devise'], 'Devise');

    	  //Check if PJ attached required
        if($this->exige_pj)
        {
            $this->check_file('pj', 'Justifications du client.', $this->_data['pj_id']);
        }
          //Check if PJ attached required
        if($this->exige_pj_photo)
        {
            $this->check_file('pj_photo', 'La photo du client.', $this->_data['pj_photo_id']);
        }

        //Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
    	global $db;

    	//$values["code"]  		 = MySQL::SQLValue($this->_data['code']);
   		$values["denomination"]  = MySQL::SQLValue($this->_data['denomination']);
   		$values["id_categorie"]  = MySQL::SQLValue($this->_data['id_categorie']);
   		$values["r_social"] 	 = MySQL::SQLValue($this->_data['r_social']);
   		$values["r_commerce"]    = MySQL::SQLValue($this->_data['r_commerce']);
   		$values["nom"]  		 = MySQL::SQLValue($this->_data['nom']);
   		$values["prenom"]	     = MySQL::SQLValue($this->_data['prenom']);
   		$values["civilite"]      = MySQL::SQLValue($this->_data['civilite']);
   		$values["adresse"] 		 = MySQL::SQLValue($this->_data['adresse']);
    	$values["id_pays"]  	 = MySQL::SQLValue($this->_data['id_pays']);
      $values["id_ville"]  = MySQL::SQLValue($this->_data['id_ville']);

    	$values["tel"] 		 	 = MySQL::SQLValue($this->_data['tel']);
    	$values["fax"] 			 = MySQL::SQLValue($this->_data['fax']);
   		$values["bp"] 			 = MySQL::SQLValue($this->_data['bp']);
   		$values["email"]	     = MySQL::SQLValue($this->_data['email']);
   		$values["rib"]  		 = MySQL::SQLValue($this->_data['rib']);
    	$values["id_devise"]     = MySQL::SQLValue($this->_data['id_devise']);
      if($this->_data['tva']=='Oui'){
      
      $values["tva"]           = MySQL::SQLValue('O');
      
      }else{

      $values["tva"]           = MySQL::SQLValue('N');

      }
    	$values["updusr"]        = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
    	$wheres["id"]            = $this->id_client;

    	//Check if Insert Query been executed (False / True)
			if (!$result = $db->UpdateRows($this->table, $values,$wheres)){
				//False => Set $this->log and $this->error = false
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $this->id_client;
				//If Attached required Save file to Archive
				$this->save_file('pj', 'Justifications du clients'.$this->_data['denomination'], 'Document');
					
				
				$this->save_file('pj_photo', 'Photo du client'.$this->_data['denomination'], 'image');

                //Esspionage
                if(!$db->After_update($this->table, $this->id_client, $values, $this->client_info)){
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false; 
                }
								
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Modification réussie: <b>'.$this->_data['denomination'].' ID: '.$this->last_id;

                    if(!Mlog::log_exec($this->table, $this->id_client, 'Modification client', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
				//Check $this->error = false return Red message and Bol false	
				}else{
					$this->log .= '</br>Modification réussie: <b>'.$this->_data['denomination'];
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

    //Bloquer client after all check
    public function bloquer_client(){
      global $db;
    
    //Get existing data for categorie_client
    $this->get_client();
 

    $this->last_id = $this->id_client;

    $this->check_non_exist('ref_motif_blocage','id', $this->_data['id_motif_blocage'], 'Motif de Blocage');

    //Check $this->error (true / false)
    if($this->error == true){
      //Format values for Insert query 
      global $db;

      $values["id_motif_blocage"] = MySQL::SQLValue($this->_data['id_motif_blocage']);
      $values["commentaire"]      = MySQL::SQLValue($this->_data['commentaire']);
      $values["date_blocage"]     = MySQL::SQLValue(date("Y-m-d H:i:s"));
      $values["etat"]             = Msetting::get_set('etat_client', 'client_bloque') ;
      $values["updusr"]           = MySQL::SQLValue(session::get('userid'));
      $values["upddat"]           = MySQL::SQLValue(date("Y-m-d H:i:s"));
      $wheres["id"]               = $this->id_client;

      //Check if Insert Query been executed (False / True)
      if (!$result = $db->UpdateRows($this->table, $values,$wheres)){
        //False => Set $this->log and $this->error = false
        $this->log .= $db->Error();
        $this->error = false;
        $this->log .='</br>Enregistrement BD non réussie'; 

      }else{

        $this->last_id = $this->id_client;
        //If Attached required Save file to Archive
                      //Esspionage
                if(!$db->After_update($this->table, $this->id_client, $values, $this->client_info)){
                    $this->log .= '</br>Problème Espionage';
                    $this->error = false; 
                }
                
        //Check $this->error = true return Green message and Bol true
        if($this->error == true)
        {
          $this->log = '</br>Client bloqué: <b> ID: '.$this->last_id;

                    if(!Mlog::log_exec($this->table, $this->id_client, 'Blocage client', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
        //Check $this->error = false return Red message and Bol false 
        }else{
          $this->log .= '</br>Client bloqué: <b>'.$this->last_id;;
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




    public function delete_client()
    {
    	global $db;
    	$id_client = $this->id_client;
    	$this->get_client();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_client);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('clients',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('clients',$where);
    		$this->error = false;
    		$this->log .='</br>Suppression non réussie';

    	}else{
    		
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';
            if(!Mlog::log_exec($this->table, $this->id_client, 'Suppression client', 'Delete'))
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
      $folder        = MPATH_UPLOAD.'clients'.SLASH.$this->last_id;
      $id_line       = $this->last_id;
      $title         = $titre;
      $table         = $this->table;
      $column        = $item;
      $type          = $type;



        //Call save_file_upload from initial class
      if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'clients', $table, $column, $type, $edit = null))
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


}