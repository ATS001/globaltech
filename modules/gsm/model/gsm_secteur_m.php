<?php 
/**
* Class Gestion Secteurs
*/


class Mgsm_secteur {
	private $_data; //data receive from form
	var $table = 'gsm_secteur';
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_gsm_secteur; // gsm_secteur ID append when request
	var $token; //Service for recovery function
	var $gsm_secteur_info; //Array stock all gsm_secteurinfo
	var $id_site;	//Site ID
	var $id_technologie; // Technologie ID
	var $secteur;//Array stock  gsm_secteur to add


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
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->gsm_secteur_info[$key] != null)
      {
        echo $this->gsm_secteur_info[$key];
      }else{
        echo "";
      }
      
    }
	
	/**
	 * [Shw description]
	 * @param [type] $key     [Key of info_array]
	 * @param string $no_echo [Used for echo the value on View ]
	 */
	public function Shw($key,$no_echo = "")
	{
		if($this->gsm_secteur_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->gsm_secteur_info[$key];
			}

			echo $this->gsm_secteur_info[$key];
		}else{
			echo "";
		}
		
	}
	
	
	/**
	 * Get information for one gsm_secteur
	 * @return [Array] [fill $this->gsm_secteur_info]
	 */
	public function get_gsm_secteur()
	{
		global $db;

		$sql = "SELECT gsm_secteur.*,secteurs.libelle as libelle_secteur FROM 
		gsm_secteur , secteurs WHERE gsm_secteur.num_secteur = secteurs.id and gsm_secteur.id = ".$this->id_gsm_secteur;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->gsm_secteur_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array gsm_secteur_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}

		
	 // Get secteurs not inserted yet for the technologie gsm
	public function get_secteur($id_tech)
	{
		global $db;

		$sql = "SELECT secteurs.id as val, secteurs.libelle as txt  FROM secteurs WHERE secteurs.etat = 1 and (secteurs.id NOT IN(SELECT
                         `g`.`num_secteur`
                       FROM `gsm_secteur` `g`
                       WHERE (`g`.`id_technologie` =".$id_tech.")))"  ;
		if(!$db->Query($sql))
		{
			return array();
		}else{
			if ($db->RowCount() == 0) {
				return array();
			} else {
				$array_secteur = $db->RecordsSelectArray();
				return $array_secteur;
				//var_dump($array_secteur);
			}
			
			
		}
		
		
	}

	/**
	 * [save_new_gsm_secteur Save new Secteur after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
	public function save_new_gsm_secteur(){
		
		//Check if num_gsm_gsm_secteur exist
		$this->Check_exist('num_secteur', $this->_data['num_secteur'], 'N° Secteur','id_technologie',$this->id_technologie,$edit=null);


		if($this->error == true){

			global $db;
			$values["num_secteur"]   		  = MySQL::SQLValue($this->_data['num_secteur']);
			$values["hba"]      			  	  = MySQL::SQLValue($this->_data['hba']);
			$values["azimuth"]        		  = MySQL::SQLValue($this->_data['azimuth']);
			$values["tilt_mecanique"]      = MySQL::SQLValue($this->_data['tilt_mecanique']);
			$values["tilt_electrique"]        = MySQL::SQLValue($this->_data['tilt_electrique']);			
			$values["creusr"]    				  = MySQL::SQLValue(session::get('userid'));	
			$values["id_technologie"] 	  = MySQL::SQLValue($this->_data['id_technologie']);
			$values["id_site"] 					  = MySQL::SQLValue($this->_data['id_site']);


			if (!$result = $db->InsertRow("gsm_secteur", $values)) {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['num_secteur'] . '  '.$this->_data['hba'] .' - '.$this->last_id.' -';
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
	
	/**
	 * [check_exist Check if one entrie already exist on table]
	 * @param  [string] $column  [Column of field on main table]
	 * @param  [string] $value   [the value to check]
	 * @param  [string] $message [Returned message if exist]
	 * @return [Setting]         [Set $this->error and $this->log]
	 */	
	private function check_exist($column, $value, $message,$column_fk,$value_fk,$edit)
	{
		global $db;
		$table = $this->table;
		$sql_edit = $edit == null ? null: " AND id <> $edit";
		$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table
			WHERE $table.$column = ". MySQL::SQLValue($value) ." AND $table.$column_fk = ". MySQL::SQLValue($value_fk) ."
			$sql_edit ");

		
		if ($result != "0") {
			$this->error = false;
			$this->log .='</br>'.$message.' existe déjà';
		}
	}

	
	public function update_gsm_secteur()
	{
		$this->get_gsm_secteur();
		
		$this->last_id = $this->id_gsm_secteur;
		
		//Check if num_gsm_gsm_secteur exist
		//$this->Check_exist('num_secteur', $this->_data['num_secteur'], 'N° Secteur','id_technologie',$this->id_technologie,$this->id_gsm_secteur);
		
		if($this->error == true){

			global $db;
			//$values["num_secteur"]        = MySQL::SQLValue($this->id_gsm_secteur);
			$values["hba"]        = MySQL::SQLValue($this->_data['hba']);
			$values["azimuth"]        = MySQL::SQLValue($this->_data['azimuth']);
			$values["tilt_mecanique"]        = MySQL::SQLValue($this->_data['tilt_mecanique']);
			$values["tilt_electrique"]        = MySQL::SQLValue($this->_data['tilt_electrique']);		
			$values["updusr"]  = MySQL::SQLValue(session::get('userid'));              
			$values["upddat"]  = MySQL::SQLValue(date("Y-m-d H:i:s"));
			$wheres["id"]      = $this->id_gsm_secteur;


			if (!$result = $db->UpdateRows("gsm_secteur", $values, $wheres)) {
				
               	//$db->Kill();
				$this->log .= $db->Error()." ".$db->BuildSQLUpdate("gsm_secteur", $values, $wheres);
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{
				$this->log .= '</br>Enregistrement réussie: <b>'.$this->id_gsm_secteur;

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



	public function delete_gsm_secteur()
	{
		global $db;
		$id_gsm_secteur = $this->id_gsm_secteur;
		$where['id'] = MySQL::SQLValue($id_gsm_secteur);
		if(!$db->DeleteRows('gsm_secteur',$where))
		{
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Suppression non r궳sie';

		}else{
			$this->error = true;
			$this->log .='</br>Suppression réussie';
		}
    	//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
	}

    /**
     * [valid_gsm_secteur Validate secteur]
     * @param  integer $etat [Etat de secteur]
     * @return [bool]        [Retrn bol value read by controller]
     */
    public function valid_gsm_secteur()
    {
    	global $db;
    	$values['etat'] = ' ETAT + 1 ';
    	$wheres['id']    = MySQL::SQLValue($this->id_gsm_secteur);

    	if(!$result = $db->UpdateRows('gsm_secteur', $values, $wheres))
    	{
    		$this->log .= $db->Error();
    		$this->error = false;
    		$this->log .= 'Activation non réussie DB';

    	}else{
    		$this->log .= 'Activation réussie';
    		$this->error = true;

    	}
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}
    }
}