<?php

/**
* Class Gestion Installateurs V1.0
*/


class Minstal {

	private $_data; //data receive from form

	var $table = 'installateurs'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = ''; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
	var $exige_logo; //set when logo is required must be defalut true.
	var $new_logo = ''; //new logo path
	var $exige_pj; // set when pk is required must be default true.
	var $exige_pj_image; // set when pk is required must be default true.
	var $new_pj= ''; //set new pj path
	var $id_instal; // User ID append when request
	var $token; //user for recovery function
	var $instal_info; //Array stock all prminfo 
	var $app_action; //Array action for each row
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

	/**
	 * Get information for one installateur
	 * @return [Array] [fill $this->instal_info]
	 */
	public function get_instal()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.*,v.ville as villes FROM 
		$table,ref_ville v WHERE $table.ville=v.id and $table.id = ".$this->id_instal;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->instal_info = $db->RowArray();
				$this->error = true;
			}	
		}
		//return Array instal_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}

		    /**
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->instal_info[$key] != null)
      {
        echo $this->instal_info[$key];
      }else{
        echo "";
      }
      
    }

	/**
	 * [Shw description]
	 * @param [type] $key     [Key of info_array]
	 * @param string $no_echo [Used for echo the value on View ]
	 */
	public function Shw($key,$no_echo = null)
	{
		if($this->instal_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->instal_info[$key];
			}

			echo $this->instal_info[$key];
		}else{
			echo "";
		}
		
	}



	/**
	 * [save_new_instal Save new Installateur after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
	public function save_new_instal()
	{		
       //Befor execute do the multiple check
		// check Dénomination installateur
		$this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination', null);
		//Check Pièce d'identité' installateur
		$this->Check_exist('piece_identite', $this->_data['piece_identite'], 'N° de la pièce d\'identité', null);
		//Check N° d'agrément
		$this->Check_exist('num_agrement', $this->_data['num_agrement'], 'N° d\'agrément', null);			
		
		
		//Check non exist Ville
		$this->check_non_exist('ref_ville', 'id', $this->_data['ville'], 'Ville');
		
		//Check if PJ attached required
		if($this->exige_pj)
		{
			$this->check_file('pj', 'La demande d\'Agrément.');
		}

		//Check if PJ_IMAGE attached required
		if($this->exige_pj_image)
		{
			$this->check_file('pj_image', 'La photo de l\'Installateur ou le Logo de la société.');
		}
		//Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
			global $db;
			$values["type_instal"]    = MySQL::SQLValue($this->_data['type_instal']);
			$values["denomination"]   = MySQL::SQLValue($this->_data['denomination']);
			$values["piece_identite"] = MySQL::SQLValue($this->_data['piece_identite']);
			$values["num_agrement"]   = MySQL::SQLValue($this->_data['num_agrement']);
			$values["qualification"]  = MySQL::SQLValue($this->_data['qualification']);
			$values["ville"]          = MySQL::SQLValue($this->_data['ville']);
			$values["adresse"]        = MySQL::SQLValue($this->_data['adresse']);
			$values["email"]          = MySQL::SQLValue($this->_data['email']);
			$values["tel"]            = MySQL::SQLValue($this->_data['tel']);
			$values["fax"]            = MySQL::SQLValue($this->_data['fax']);
			$values["vsat"]           = MySQL::SQLValue($this->_data['vsat']);
			$values["uhf_vhf"]        = MySQL::SQLValue($this->_data['uhf_vhf']);
			$values["gsm"]            = MySQL::SQLValue($this->_data['gsm']);
			$values["blr"]            = MySQL::SQLValue($this->_data['blr']);
			$values["creusr"]         = MySQL::SQLValue(session::get('userid'));

			//Check if Insert Query been executed (False / True)
			if (!$result = $db->InsertRow($this->table, $values)){
				//False => Set $this->log and $this->error = false
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				//If Attached required Save file to Archive
				if($this->exige_pj)
				{
					$this->save_file('pj', 'Demande d\'agrément de l\'installateur '.$this->_data['denomination'], 'Document');
				}	
				if($this->exige_pj_image)
				{
					$this->save_file('pj_image', 'Photo de l\'installateur ou Logo de la société'.$this->_data['denomination'], 'Document');
				}				
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
	public function edit_instal()
	{	
	    //Get existing data for installateur
		$this->get_instal();
		
		$this->last_id = $this->id_instal;	

		//Befor execute do the multiple check
		// check Dénomination installateur
		$this->Check_exist('denomination', $this->_data['denomination'], 'Dénomination',  $this->id_instal);
		//Check Pièce d'identité' installateur
		$this->Check_exist('piece_identite', $this->_data['piece_identite'], 'N° de la pièce d\'identité', $this->id_instal);
		//Check N° d'agrément
		$this->Check_exist('num_agrement', $this->_data['num_agrement'], 'N° d\'agrément', $this->id_instal);			
		
		
		//Check non exist Ville
		$this->check_non_exist('ref_ville', 'id', $this->_data['ville'], 'Ville');
		
		//Check if PJ attached required
		if($this->exige_pj)
		{
			$this->check_file('pj', 'La demande d\'Agrément.', $this->_data['pj_id']);
		}

		//Check if PJ_IMAGE attached required
		if($this->exige_pj_image)
		{
			$this->check_file('pj_image', 'La photo de l\'Installateur ou le Logo de la société. ', $this->_data['pj_image_id']);
		}

       //Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
			global $db;
			$values["type_instal"]    = MySQL::SQLValue($this->_data['type_instal']);
			$values["denomination"]   = MySQL::SQLValue($this->_data['denomination']);
			$values["piece_identite"] = MySQL::SQLValue($this->_data['piece_identite']);
			$values["num_agrement"]   = MySQL::SQLValue($this->_data['num_agrement']);
			$values["qualification"]  = MySQL::SQLValue($this->_data['qualification']);
			$values["ville"]          = MySQL::SQLValue($this->_data['ville']);
			$values["adresse"]        = MySQL::SQLValue($this->_data['adresse']);
			$values["email"]          = MySQL::SQLValue($this->_data['email']);
			$values["tel"]            = MySQL::SQLValue($this->_data['tel']);
			$values["fax"]            = MySQL::SQLValue($this->_data['fax']);
			$values["vsat"]           = MySQL::SQLValue($this->_data['vsat']);
			$values["uhf_vhf"]        = MySQL::SQLValue($this->_data['uhf_vhf']);
			$values["gsm"]            = MySQL::SQLValue($this->_data['gsm']);
			$values["blr"]            = MySQL::SQLValue($this->_data['blr']);
			$values["updusr"]         = MySQL::SQLValue(session::get('userid'));
		    $values["upddat"]         = MySQL::SQLValue(date("Y-m-d H:i:s"));
			$wheres["id"]             = MySQL::SQLValue($this->id_instal);
            

            //Check if Update Query been executed (False / True)
			if (!$result = $db->UpdateRows($this->table, $values, $wheres)){
				//False => Set $this->log and $this->error = false
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				//$this->last_id = $result;
				//If Attached required Save file to Archive
				if($this->exige_pj)
				{
					
					$this->save_file('pj', 'Demande d\'agrément de l\'installateur '.$this->_data['denomination'], 'Document');
				}	
				if($this->exige_pj_image)
				{
					$this->save_file('pj_image', 'Photo de l\'installateur ou Logo de la société '.$this->_data['denomination'], 'Document');
				}				
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Enregistrement réussie: <b>'.$this->_data['denomination'].' ID: '.$this->id_instal;
				//Check $this->error = false return Red message and Bol false	
				}else{
					$this->log .= '</br>Enregistrement non réussie: <b>'.$this->_data['denomination'];
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
    	$new_name_file = $item.'_'.$this->last_id;
    	$folder        = MPATH_UPLOAD.'installateurs'.SLASH.$this->last_id;
    	$id_line       = $this->last_id;
    	$title         = $titre;
    	$table         = $this->table;
    	$column        = $item;
    	$type          = $type;

    	//Call save_file_upload from initial class
    	if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title,'installateurs', $table, $column, $type, $edit = null))
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

    /**
     * [valid_compte Validate  INSTALLATEUR]
     * @param  integer $etat [Etat de INSTALLATEUR]
     * @return [bool]        [Retrn bol value read by controller]
     */
    public function valid_instal($etat = 0)
	{
		global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$value["etat"] 			= MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_instal;
        // Execute the update and show error case error
		if( !$result = $db->UpdateRows($this->table, $value , $where))
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

	 public function delete_instal()
    {
    	global $db;
    	//Format where clause
    	$where['id'] = $this->id_instal;
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows($this->table,$where))
    	{
			$this->log .='</br>Suppression non réussie';
			$this->log .= '</br>'.$db->Error();
			$this->error = false;
			
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


?>