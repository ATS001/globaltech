<?php 
/**
* Info Ste 1.0
*/
class MSte_info 
{
	
		private $_data; //data receive from form

	var $table    = 'ste_info'; //Main table of module
	var $last_id  = null; //return last ID after insert command
	var $log      = null; //Log of all opération.
	var $error    = true; //Error bol changed when an error is occured
	var $id_ste   = null; // Ville ID append when request
	var $ste_info = null; //Array stock all prminfo 
	


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
	 * [get_ste_info Get all info for line]
	 * @return [type] [fill ste_info array]
	 */
	public function get_ste_info()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_ste;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->ste_info = $db->RowArray();
				$this->error = true;
			}	
		}
		//return Array ville_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}


	/**
	 * [s Echo value from ste_info array]
	 * @param  [string] $key [key ste_info array]
	 * @return [Print]      [print into output]
	 */
	public function s($key)
	{
		if($this->ste_info[$key] != null)
		{
			echo $this->ste_info[$key];
		}else{
			echo "";
		}

	}
    /**
	 * [s Get value from ste_info array]
	 * @param  [string] $key [key ste_info array]
	 * @return [string]      [use into code]
	 */
	public function g($key)
	{
		if($this->ste_info[$key] != null)
		{
			return $this->ste_info[$key];
		}else{
			return null;
		}

	}







}