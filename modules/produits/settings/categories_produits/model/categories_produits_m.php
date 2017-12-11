<?php 
/**
* Class Gestion Catégories de produits 1.0
*/


	class Mcategorie_produit {
		private $_data; //data receive from form
		var $table = 'ref_categories_produits'; //Main table of module
		var $last_id; //return last ID after insert command
		var $log = NULL; //Log of all opération.
		var $error = true; //Error bol changed when an error is occured
                var $id_categorie_produit; // categorie_produit ID append when request
                var $id_type_produit; // Type produit ID
		var $token; //user for recovery function
		var $categorie_produit_info; //Array stock all categorie_produit info
		var $app_action; //Array action for each 

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
			//Get all info categorie_produit from database for edit form

		public function get_categorie_produit()
		{
			global $db;

			$table = $this->table;

			$sql = "SELECT $table.* FROM 
			$table WHERE  $table.id = ".$this->id_categorie_produit;

			if(!$db->Query($sql))
			{
				$this->error = false;
				$this->log  .= $db->Error();
			}else{
				if ($db->RowCount() == 0) {
					$this->error = false;
					$this->log .= 'Aucun enregistrement trouvé ';
				} else {
					$this->categorie_produit_info = $db->RowArray();
					$this->error = true;
				}
				
				
			}
			//return Array categorie_produit_info
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
	   


		 //Save new categorie_produit after all check
		public function save_new_categorie_produit(){

			
	       	//Before execute do the multiple check
		// check Region
		$this->Check_exist('categorie_produit', $this->_data['categorie_produit'], 'Catégorie de produit', null);

		
			global $db;
                        $values["type_produit"]     = MySQL::SQLValue($this->_data['type_produit']);
			$values["categorie_produit"]     = MySQL::SQLValue($this->_data['categorie_produit']);
			$values["creusr"]     = MySQL::SQLValue(session::get('userid'));
	                 $values["credat"]     = MySQL::SQLValue(date("Y-m-d H:i:s"));

	        // If we have an error
			if($this->error == true){

				if (!$result = $db->InsertRow("ref_categories_produits", $values)) {
					
					$this->log .= $db->Error();
					$this->error = false;
					$this->log .='</br>Enregistrement BD non réussie'; 

				}else{

					$this->last_id = $result;
					$this->log .='</br>Enregistrement  réussie '. $this->_data['categorie_produit'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->last_id , 'Insertion catégorie produit', 'Insert'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
				}


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

	    //activer ou desactiver une categorie_produit
		public function valid_categorie_produit($etat = 0)
		{
			global $db;

			//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
			$etat = $etat == 0 ? 1 : 0;

			$values["etat"]    = MySQL::SQLValue($etat);
			$values["updusr"] = MySQL::SQLValue(session::get('userid'));
		    $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

			$wheres['id']     = $this->id_categorie_produit;

			// Execute the update and show error case error
			if(!$result = $db->UpdateRows($this->table, $values, $wheres))
			{
				$this->log   .= '</br>Impossible de changer le statut!';
				$this->log   .= '</br>'.$db->Error();
				$this->error  = false;

			}else{
				$this->log   .= '</br>Statut changé! ';
				//$this->log   .= $this->table.' '.$this->id_categorie_produit.' '.$etat;
				$this->error  = true;


                    if(!Mlog::log_exec($this->table, $this->id_categorie_produit , 'Validation catégorie produit', 'Validate'))
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



		// afficher les infos d'une categorie_produit
		public function Shw($key,$no_echo = "")
		{
			if($this->categorie_produit_info[$key] != null)
			{
				if($no_echo != null)
				{
					return $this->categorie_produit_info[$key];
				}

				echo $this->categorie_produit_info[$key];
			}else{
				echo "";
			}
			
		}
		//Edit categorie_produit after all check
		public function edit_categorie_produit(){

			

			//Get existing data for categorie_produit
			$this->get_categorie_produit();
			
			$this->last_id = $this->id_categorie_produit;

			//Before execute do the multiple check
			
			$this->Check_exist('categorie_produit', $this->_data['categorie_produit'], 'Catégorie de produit', $this->id_categorie_produit);

			
	    	global $db;
                        $values["type_produit"]     = MySQL::SQLValue($this->_data['type_produit']);
			$values["categorie_produit"]        = MySQL::SQLValue($this->_data['categorie_produit']);
	                $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
	                $values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
			$wheres["id"]            = $this->id_categorie_produit;
			

	        // If we have an error
			if($this->error == true){

				if (!$result = $db->UpdateRows("ref_categories_produits", $values, $wheres)) {
					//$db->Kill();
					$this->log .= $db->Error();
					$this->error == false;
					$this->log .='</br>Enregistrement BD non réussie'; 

				}else{

					//$this->last_id = $result;
					$this->log .='</br>Enregistrement  réussie '. $this->_data['categorie_produit'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->id_categorie_produit , 'Modification catégorie produit', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }


                //Esspionage
                if(!$db->After_update($this->table, $this->id_categorie_produit, $values, $this->categorie_produit_info)){
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false; 
                }

				}


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

		// Supprimer une catégorie de produit
		 public function delete_categorie_produit()
	    {
	    	global $db;
	    	$id_categorie_produit = $this->id_categorie_produit;
	    	$this->get_categorie_produit();
	    	//Format where clause
	    	$where['id'] = MySQL::SQLValue($id_categorie_produit);
	    	//check if id on where clause isset
	    	if($where['id'] == null)
	    	{
	    		$this->error = false;
				$this->log .='</br>L\' id est vide';
	    	}
	    	//execute Delete Query
	    	if(!$db->DeleteRows('ref_categories_produits',$where))
	    	{

	    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_categories_produits',$where);
				$this->error = false;
				$this->log .='</br>Suppression non réussie';

	    	}else{
	    		
	    		$this->error = true;
	    		$this->log .='</br>Suppression réussie ';

                    if(!Mlog::log_exec($this->table, $this->id_categorie_produit , 'Suppression catégorie produit', 'Delete'))
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

           public function get_all_categories()
           {
               global $db;

        $table = $this->table;

        $sql = "SELECT $table.categorie_produit FROM 
    		$table WHERE  $table.type_produit = " . $this->id_type_produit;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->categorie_produit_info = $db->RecordsSimplArray();
                $this->error = true;
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
           }
	    
	}