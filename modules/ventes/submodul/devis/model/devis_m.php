<?php 
//SYS GLOBAL TECH
// Modul: devis => Model

class Mdevis {
	private $_data; //data receive from form
	var $table = 'devis'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_devis; // Ville ID append when request
	var $token; //user for recovery function
	var $devis_info; //Array stock all ville info


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
		//Get all info devis from database for edit form

	public function get_devis()
	{
		global $db;

		$sql = "SELECT $table.* from $table where $table.id = ".$this->id_devis;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->devis_info = $db->RowArray();
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


	 //Save new devis after all check
    public function save_new_devis(){

        //Before execute do the multiple check
        $this->Check_exist('reference', $this->_data['reference'], 'Réference Devis', null);
             
    	$this->check_non_exist('clients','id',$this->_data['id_client'] ,'Client' );


        //Check $this->error (true / false)
		if($this->error == true){
			//Format values for Insert query 
    	global $db;

   		$values["reference"]  		   = MySQL::SQLValue($this->_data['reference']);
   		$values["id_client"]           = MySQL::SQLValue($this->_data['id_client']);
   		$values["id_commercial"]       = MySQL::SQLValue($this->_data['id_commercial']);
   		$values["date_devis"] 	       = MySQL::SQLValue($this->_data['date_devis']);
   		$values["type_remise"]         = MySQL::SQLValue($this->_data['type_remise']);
   		$values["remise_montant"]      = MySQL::SQLValue($this->_data['remise_montant']);
   		$values["remise_pourcentage"]  = MySQL::SQLValue($this->_data['remise_pourcentage']);
   		$values["totalht"]	           = MySQL::SQLValue($this->_data['totalht']);
   		$values["totalttc"]            = MySQL::SQLValue($this->_data['totalttc']);
   		$values["totaltva"] 		   = MySQL::SQLValue($this->_data['totaltva']);
    	$values["creusr"]      	       = MySQL::SQLValue(session::get('userid'));
    	$values["credat"]      	       = MySQL::SQLValue(date("Y-m-d H:i:s"));

    	//Check if Insert Query been executed (False / True)
			if (!$result = $db->InsertRow($this->table, $values)){
				//False => Set $this->log and $this->error = false
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
					
				//Check $this->error = true return Green message and Bol true
				if($this->error == true)
				{
					$this->log = '</br>Enregistrement réussie: <b>'.$this->_data['reference'].' ID: '.$this->last_id;
				//Check $this->error = false return Red message and Bol false	
				}else{
					$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['reference'];
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
  //Get last deivs id
    public function get_last_empolye_id()
  {
    global $db;

    $sql = "SELECT max(id) as id FROM devis";
    if(!$db->Query($sql))
    {
      $this->error = false;
      $this->log  .= $db->Error();
    }else{
      if (!$db->RowCount()) {
        $this->error = false;
        $this->log .= 'Aucun enregistrement trouvé ';
      } else {
        $this->devis_info = $db->RowArray();
               
        $this->error = true;
        
      }


    }
         //Save new client after all check
    public function save_new_details_devis($dev){

        //Before execute do the multiple check

        $this->check_non_exist('produits','ref',$this->_data['ref_produit'] ,'Réference du produit' );
     

        //Check $this->error (true / false)
        if($this->error == true){
            //Format values for Insert query 
        global $db;

        $values["id_devis"]           = MySQL::SQLValue($dev);
        $values["ref_produit"]        = MySQL::SQLValue($this->_data['ref_produit']);
        $values["designation"]        = MySQL::SQLValue($this->_data['designation']);
        $values["qte"]                = MySQL::SQLValue($this->_data['qte']);
        $values["prix_unitaire"]      = MySQL::SQLValue($this->_data['prix_unitaire']);
        $values["remise"]             = MySQL::SQLValue($this->_data['remise']);
        $values["tva"]                = MySQL::SQLValue($this->_data['tva']);
        $values["prix_ht"]            = MySQL::SQLValue($this->_data['prix_ht']);
        $values["prix_ttc"]           = MySQL::SQLValue($this->_data['prix_ttc']);
        $values["total_ht"]           = MySQL::SQLValue($this->_data['total_ht']);
        $values["total_ttc"]          = MySQL::SQLValue($this->_data['total_ttc']);
        $values["total_tva"]          = MySQL::SQLValue($this->_data['total_tva']);
        $values["creusr"]             = MySQL::SQLValue(session::get('userid'));
        $values["credat"]             = MySQL::SQLValue(date("Y-m-d H:i:s"));

        //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow('d_devis', $values)){
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

    //activer ou desactiver un devis
    public function valid_devis($etat = 0)
    {
    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 		= MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_devis;

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
    	if($this->devis_info[$key] != null)
    	{
    		echo $this->devis_info[$key];
    	}else{
    		echo "";
    	}

    }
  
    // afficher les infos d'un client
    public function Shw($key,$no_echo = "")
    {
    	if($this->devis_info[$key] != null)
    	{
    		if($no_echo != null)
    		{
    			return $this->devis_info[$key];
    		}

    		echo $this->devis_info[$key];
    	}else{
    		echo "";
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
    	$folder        = MPATH_UPLOAD.'devis'.SLASH.$this->last_id;
    	$id_line       = $this->last_id;
    	$title         = $titre;
    	$table         = $this->table;
    	$column        = $item;
    	$type          = $type;

    	//Call save_file_upload from initial class
    	if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title,'devis', $table, $column, $type, $edit = null))
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