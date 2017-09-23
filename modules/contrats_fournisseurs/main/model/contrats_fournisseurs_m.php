<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Model


class Mcontrats_fournisseurs {
	private $_data; //data receive from form
	var $table = 'contrats_frn'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
  var $id_contrats_frn; // Ville ID append when request
	var $token; //user for recovery function
	var $contrats_frn_info; //Array stock all ville info
  var $reference = null; // Reference contrat


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
		//Get all info categorie_contrats_frn from database for edit form

	public function get_contrats_frn()
	{
		global $db;
    $table = $this->table;

		$sql = "SELECT  c.*, f.code ,f.denomination ,f.r_social ,f.tel, f.fax, f.email,p.pays FROM $table c, fournisseurs f, ref_pays p  WHERE c.id_fournisseur=f.id and f.id_pays=p.id and  c.id = ".$this->id_contrats_frn;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->contrats_frn_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array contrats_frn_info
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

    //Generate contrat reference
    private function Generate_contrat_reference() {
        if ($this->error == false) {
            return false;
        }
        global $db;
        $max_id = $db->QuerySingleValue0('SELECT IFNULL(( MAX(SUBSTR(reference, 9, LENGTH(SUBSTR(reference,9))-5))),0)+1  AS reference  FROM contrats_frn WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))');
        $this->reference = 'CTR-FRN-' . $max_id . '/' . date('Y');
    }

	 //Save new contrats_frn after all check
    public function save_new_contrats_frn(){

      //Generate reference
      $this->Generate_contrat_reference();

      //Before execute do the multiple check
      $this->Check_exist('reference', $this->reference, 'Référence contrat', null);

		  $this->check_non_exist('fournisseurs','id', $this->_data['id_fournisseur'], 'Fournisseur');

       

    	  //Check if PJ attached required
        if($this->exige_pj)
        {
            $this->check_file('pj', 'Justifications du contrats_frn.');
        }
     

        //Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
    	global $db;

   		$values["reference"]  	 = MySQL::SQLValue($this->reference);
   		$values["id_fournisseur"]= MySQL::SQLValue($this->_data['id_fournisseur']);
      $values["date_effet"]    = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_effet'])));
      $values["date_fin"]      = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_fin'])));
   		$values["commentaire"]   = MySQL::SQLValue($this->_data['commentaire']);
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
				
        $this->save_file('pj', 'Copie Contrat fournisseur.'.$this->reference, 'Document');
					
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Enregistrement réussie: <b>'.$this->reference.' ID: '.$this->last_id;
				//Check $this->error = false return Red message and Bol false	
				}else{
					$this->log .= '</br>Enregistrement réussie: <b>'.$this->reference;
                    
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
	

    //activer ou desactiver un contrats_frn
    public function valid_contrats_frn($etat = 0)
    {
    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 		    = MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
	  $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_contrats_frn;

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



	// afficher les infos d'un contrats_frn
    public function s($key)
    {
    	if($this->contrats_frn_info[$key] != null)
    	{
    		echo $this->contrats_frn_info[$key];
    	}else{
    		echo "";
    	}

    }
  
    // afficher les infos d'un contrats_frn
    public function Shw($key,$no_echo = "")
    {
    	if($this->contrats_frn_info[$key] != null)
    	{
    		if($no_echo != null)
    		{
    			return $this->contrats_frn_info[$key];
    		}

    		echo $this->contrats_frn_info[$key];
    	}else{
    		echo "";
    	}

    }

	//Edit categorie_contrats_frn after all check
    public function edit_contrats_frn(){

		//Get existing data for categorie_contrats_frn
    	$this->get_contrats_frn();

    	$this->last_id = $this->id_contrats_frn;

      
      $this->check_non_exist('fournisseurs','id', $this->_data['id_fournisseur'], 'Fournisseur');
      
    	  //Check if PJ attached required
        if($this->exige_pj)
        {
            $this->check_file('pj', 'Copie Contrat fournisseur.', $this->_data['pj_id']);
        }
    

        //Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
    	global $db;

    	global $db;
   		$values["id_fournisseur"]= MySQL::SQLValue($this->_data['id_fournisseur']);
      $values["date_effet"]    = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_effet'])));
      $values["date_fin"]      = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_fin'])));
      $values["commentaire"]   = MySQL::SQLValue($this->_data['commentaire']);
    	$values["updusr"]        = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
    	$wheres["id"]            = $this->id_contrats_frn;

    	//Check if Insert Query been executed (False / True)
			if (!$result = $db->UpdateRows($this->table, $values,$wheres)){
				//False => Set $this->log and $this->error = false
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $this->id_contrats_frn;
				//If Attached required Save file to Archive
				$this->save_file('pj', 'Copie Contrat fournisseur.'.$this->_data['reference'], 'Document');
					
								
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Modification réussie: <b>'.$this->_data['reference'].' ID: '.$this->last_id;
				//Check $this->error = false return Red message and Bol false	
				}else{
					$this->log .= '</br>Modification réussie: <b>'.$this->_data['reference'];
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


    public function delete_contrats_frn()
    {
    	global $db;
    	$id_contrats_frn = $this->id_contrats_frn;
    	$this->get_contrats_frn();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_contrats_frn);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('contrats_frn',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('contrats_frn',$where);
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
      $folder        = MPATH_UPLOAD.'contrats_fournisseurs'.SLASH.$this->last_id;
      $id_line       = $this->last_id;
      $title         = $titre;
      $table         = $this->table;
      $column        = $item;
      $type          = $type;



        //Call save_file_upload from initial class
      if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'contrats_fournisseurs', $table, $column, $type, $edit = null))
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