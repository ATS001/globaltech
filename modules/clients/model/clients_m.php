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
	var $client_info; //Array stock all ville info


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

		$sql = "SELECT  c.*,cat.categorie_client as categorie_client, p.pays as pays,v.ville as ville, d.devise as devise, (IF(c.tva='O','OUI','NON')) AS tva,c.tva as tva_brut FROM  clients c
             LEFT JOIN categorie_client cat on c.id_categorie=cat.id 
             LEFT JOIN ref_pays p on c.id_pays=p.id 
             LEFT JOIN ref_ville v on c.id_ville=v.id
             LEFT JOIN ref_devise d on c.id_devise=d.id
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
    		$this->error = false;
    		$this->log .='</br>'.$message.' n\'exist pas';
    		//exit('0#'.$this->log);
    	}
    }


	 //Save new client after all check
    public function save_new_client(){


        //Before execute do the multiple check

        $this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', null);

        $this->Check_exist('code', $this->_data['code'], 'Code Fournisseur', null);

        $this->Check_exist('r_social', $this->_data['r_social'], 'Raison Sociale', null);
             
        $this->Check_exist('r_commerce', $this->_data['r_commerce'], 'N° de registre', null);           
       
        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', null);

		
    	$this->check_non_exist('categorie_client','id',$this->_data['id_categorie'] ,'Catégorie' );

    	$this->check_non_exist('ref_pays','id', $this->_data['id_pays'], 'Pays');

    	$this->check_non_exist('ref_ville','id', $this->_data['id_ville'], 'Ville');

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

   		$values["code"]  		 = MySQL::SQLValue($this->_data['code']);
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
   		$values["id_ville"] 	 = MySQL::SQLValue($this->_data['id_ville']);
    	$values["tel"] 		 	 = MySQL::SQLValue($this->_data['tel']);
    	$values["fax"] 			 = MySQL::SQLValue($this->_data['fax']);
   		$values["bp"] 			 = MySQL::SQLValue($this->_data['bp']);
   		$values["email"]	     = MySQL::SQLValue($this->_data['email']);
   		$values["rib"]  		 = MySQL::SQLValue($this->_data['rib']);
    	$values["id_devise"]  	 = MySQL::SQLValue($this->_data['id_devise']);
        $values["tva"]           = MySQL::SQLValue($this->_data['tva']);
    	$values["creusr"]      	 = MySQL::SQLValue(session::get('userid'));
    	$values["credat"]      	 = MySQL::SQLValue(date("Y-m-d H:i:s"));

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

		} 
		if($this->error == false){
			return false;
		}else{
			return true;
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
        
        $this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', null);

        $this->Check_exist('code', $this->_data['code'], 'Code Fournisseur', null);

        $this->Check_exist('r_social', $this->_data['r_social'], 'Raison Sociale', $this->id_client);
             
        $this->Check_exist('r_commerce', $this->_data['r_commerce'], 'N° de registre', $this->id_client);           
       
        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', $this->id_client);


        $this->check_non_exist('clients','id_categorie',$this->_data['id_categorie'] ,'Catégorie' );

    	$this->check_non_exist('clients','id_pays', $this->_data['id_pays'], 'Pays');

    	$this->check_non_exist('clients','id_ville', $this->_data['id_ville'], 'Ville');

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
   		$values["id_ville"] 	 = MySQL::SQLValue($this->_data['id_ville']);
    	$values["tel"] 		 	 = MySQL::SQLValue($this->_data['tel']);
    	$values["fax"] 			 = MySQL::SQLValue($this->_data['fax']);
   		$values["bp"] 			 = MySQL::SQLValue($this->_data['bp']);
   		$values["email"]	     = MySQL::SQLValue($this->_data['email']);
   		$values["rib"]  		 = MySQL::SQLValue($this->_data['rib']);
    	$values["id_devise"]     = MySQL::SQLValue($this->_data['id_devise']);
        $values["tva"]           = MySQL::SQLValue($this->_data['tva']);
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
								
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Modification réussie: <b>'.$this->_data['denomination'].' ID: '.$this->last_id;
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