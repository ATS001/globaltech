<?php 
//SYS MRN ERP
// Modul: gsm_technologie => Model


class Mgsm_technologie {
	private $_data; //data receive from form
	var $table = 'gsm_technologie'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
  var $id_technologie; // Technologie ID append when request
	var $token; //user for recovery function
	var $gsm_technologie_info; //Array stock all technologie info
  var $id_gsm;//return the gsm site selected
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
		//Get all info technologie from database for edit form

	public function get_technologie()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.*,technologies_gsm.libelle as libelle  FROM 
		$table,technologies_gsm WHERE technologies_gsm.id=$table.technologie and $table.id = ".$this->id_technologie;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->gsm_technologie_info= $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array technologie_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
		}
		
	}
  
  // Get technologies not inserted yet for the station gsm
  public function get_technologies($id_gsm)
  {
    global $db;

    $sql = "SELECT t.id as val,t.libelle as txt  FROM technologies_gsm t,gsm_stations g WHERE t.etat=1 and 
         (((`t`.`libelle` = IF((`g`.`tech_2g` = 1),'2G',NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_3g` = 1),'3G',NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_4g` = 1),'4G',NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_cdma` = 1),'CDMA',NULL)))
         AND (NOT(`t`.`id` IN(SELECT
                              `tech`.`technologie`
                            FROM `gsm_technologie` `tech`
                            WHERE (`tech`.`id_site_gsm` = `g`.`id`)))))
         AND g.id=".$id_gsm ;

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
 

  // indicates if we dont reach the number of secteur in Technologie GSM
  public function check_nbr_secteur()
  {
    global $db;

    $table = "gsm_secteur";
    $nbr_secteur=$this->gsm_technologie_info['nbr_secteur'];

    $sql = "SELECT $table.* FROM  $table WHERE $table.id_technologie = ".$this->id_technologie;
    if(!$db->Query($sql))
    {
      $this->error = false;
      $this->log  .= $db->Error();
    }else{
      if ($db->RowCount() >= $nbr_secteur) {
        $this->error = FALSE;
        return FALSE;
      } else {
        $this->error = TRUE;
        return TRUE;
      } 
    }

  }

  // indicates  if we dont reach the technologies of the GSM site
  public function check_technologie($id_site_gsm)
  {    

    global $db;
    //$table = "count_technologie_by_station";
    $result = $db->QuerySingleValue0("SELECT
  COUNT(`t`.`libelle`) AS `COUNT`,
  `g`.`id` AS `id`
FROM (`technologies_gsm` `t`
   JOIN `gsm_stations` `g`)
WHERE (((`t`.`libelle` = IF((`g`.`tech_2g` = 1),'2G',NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_3g` = 1),'3G',NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_4g` = 1),'4G',NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_cdma` = 1),'CDMA',NULL)))
       AND (NOT(`t`.`id` IN(SELECT
                              `tech`.`technologie`
                            FROM `gsm_technologie` `tech`
                            WHERE (`tech`.`id_site_gsm` = `g`.`id`)))))
       AND `g`.`id` =". $id_site_gsm
 );
    if ($result > 0) {

      $this->error = true;
      $this->log .='</br> test';
      return TRUE;

    }else{

      $this->error = false;
      $this->log .='</br> Les technologies de la station GSM sont déja insérées';
      return FALSE;
    }

  }

	    /**
     * [check_exist Check if one entrie already exist on table]
     * @param  [string] $column  [Column of field on main table]
     * @param  [string] $column2 [Column2 of field2 on main table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $value2   [the value2 to check]
     * @param  [string] $message [Returned message if exist]
     * @param  [int] $edit       [Used if is edit action must be the ID of row edited]
     * @return [Setting]         [Set $this->error and $this->log]
     */
	    private function check_exist_technologie($column, $column2, $value, $value2, $message, $edit = null)
	    {
	    	global $db;
	    	$table = $this->table;
	    	$sql_edit = $edit == null ? null: " AND id <> $edit";
	    	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
	    		WHERE $table.$column = ". MySQL::SQLValue($value) ." AND $table.$column2 =". MySQL::SQLValue($value2)." $sql_edit ");
	    	
	    	if ($result != "0") {
	    		$this->error = false;
	    		$this->log .='</br>'.$message.' existe déjà pour ce site GSM';
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

	 //Save new user after all check
    public function save_new_gsm_technologie(){

        //Check if the technologie doesn't  exist for the same site_gsm
    	$this->check_exist_technologie('technologie','id_site_gsm', $this->_data['technologie'], $this->_data['id_site_gsm'], 'Technologie', null);
    	
        //Check non exist site_gsm
      $this->check_non_exist('gsm_stations', 'id', $this->_data['id_site_gsm'], 'Site GSM');


      global $db;

      $values["id_site_gsm"]      = MySQL::SQLValue($this->_data['id_site_gsm']);
      $values["technologie"]      = MySQL::SQLValue($this->_data['technologie']);
      $values["marque_bts"]       = MySQL::SQLValue($this->_data['marque_bts']);
      $values["num_serie"]        = MySQL::SQLValue($this->_data['num_serie']);
      $values["modele_antenne"]   = MySQL::SQLValue($this->_data['modele_antenne']);
      $values["nbr_radio"]        = MySQL::SQLValue($this->_data['nbr_radio']);
      $values["nbr_secteur"]      = MySQL::SQLValue($this->_data['nbr_secteur']);
      $values["creusr"]           = MySQL::SQLValue(session::get('userid'));
      $values["credat"]           = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
      if($this->error == true){

        if (!$result = $db->InsertRow("gsm_technologie", $values)) {

         $this->log .= $db->Error();
         $this->error = false;
         $this->log .='</br>Enregistrement BD non réussie'; 

       }else{

         $this->last_id = $result;
         $this->log .='</br>Enregistrement  réussie '. $this->_data['marque_bts'] .' - '.$this->last_id.' -';
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
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->gsm_technologie_info[$key] != null)
      {
        echo $this->gsm_technologie_info[$key];
      }else{
        echo "";
      }
      
    }

	// afficher les infos d'une technologie
  public function Shw($key,$no_echo = "")
  {
   if($this->gsm_technologie_info[$key] != null)
   {
    if($no_echo != null)
    {
     return $this->gsm_technologie_info[$key];
   }

   echo $this->gsm_technologie_info[$key];
 }else{
  echo "";
}

}
	//Edit region after all check
public function edit_gsm_technologie(){

		//Get existing data for technologie
 $this->get_technologie();

 $this->last_id = $this->id_technologie;

        //Check if the technologie doesn't  exist for the same site_gsm
 //$this->check_exist_technologie('technologie','id_site_gsm', $this->_data['technologie'], $this->_data['id_site_gsm'], 'Technologie', $this->id_technologie);

        //Check non exist site_gsm
 $this->check_non_exist('gsm_stations', 'id', $this->_data['id_site_gsm'], 'Site GSM');


        //Check $this->error (true / false)
 if($this->error == true){

   global $db;

   $values["id_site_gsm"]      = MySQL::SQLValue($this->_data['id_site_gsm']);
  // $values["technologie"]      = MySQL::SQLValue($this->_data['technologie']);
   $values["marque_bts"]       = MySQL::SQLValue($this->_data['marque_bts']);
   $values["num_serie"]        = MySQL::SQLValue($this->_data['num_serie']);
   $values["modele_antenne"]   = MySQL::SQLValue($this->_data['modele_antenne']);
   $values["nbr_radio"]        = MySQL::SQLValue($this->_data['nbr_radio']);
   $values["nbr_secteur"]      = MySQL::SQLValue($this->_data['nbr_secteur']);
   $values["updusr"]           = MySQL::SQLValue(session::get('userid'));
   $values["upddat"]           = MySQL::SQLValue(date("Y-m-d H:i:s"));
   $wheres["id"]               = $this->id_technologie;

            //Check if Update Query been executed (False / True)    
   if (!$result = $db->UpdateRows("gsm_technologie", $values, $wheres)) {
				//$db->Kill();
     $this->log .= $db->Error();
     $this->error == false;
     $this->log .='</br>Enregistrement BD non réussie'; 

   }
   else
   {
    $this->last_id = $result;
    $this->log .='</br>Enregistrement  réussie '. $this->_data['marque_bts'] .' - '.$this->last_id.' -';
  }


}else{

  $this->log .='</br>Enregistrement non réussie';

}

        //check if last error is true then return true else rturn false.
if($this->error == false)
{
  return false;
}else
{
  return true;
}


}

public function delete_technologie()
{
 global $db;
 $id_technologie = $this->id_technologie;
 $this->get_technologie();
    	//Format where clause
 $where['id'] = MySQL::SQLValue($id_technologie);
    	//check if id on where clause isset
 if($where['id'] == null)
 {
  $this->error = false;
  $this->log .='</br>L\' id est vide';
}
    	//execute Delete Query
if(!$db->DeleteRows('gsm_technologie',$where))
{

  $this->log .= $db->Error().'  '.$db->BuildSQLDelete('gsm_technologie',$where);
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