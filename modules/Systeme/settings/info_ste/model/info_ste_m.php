<?php 
/**
* Info Ste 1.0
*/
class MSte_info 
{
	
	private $_data; //data receive from form

	var $table       = 'ste_info'; //Main table of module
	var $table_b     = 'ste_info_banque'; //Main table of module	
	var $last_id     = null; //return last ID after insert command
	var $log         = null; //Log of all opération.
	var $error       = true; //Error bol changed when an error is occured
	var $id_ste      = null; // Ville ID append when request
	var $ste_info    = null; //Array stock all prminfo 
	var $banque_info = null; //Array stock all prminfo 
	


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

	public function edit_info_ste()
	{

		global $db;
		$values["ste_name"]       = MySQL::SQLValue($this->_data['ste_name']);
		$values["ste_bp"]         = MySQL::SQLValue($this->_data['ste_bp']);
		$values["ste_id_devise"]  = MySQL::SQLValue($this->_data['ste_id_devise']);
		$values["ste_adresse"]    = MySQL::SQLValue($this->_data['ste_adresse']);
		$values["ste_tel"]        = MySQL::SQLValue($this->_data['ste_tel']);
		$values["ste_fax"]        = MySQL::SQLValue($this->_data['ste_fax']);
		$values["ste_email"]      = MySQL::SQLValue($this->_data['ste_email']);
		$values["ste_if"]         = MySQL::SQLValue($this->_data['ste_if']);
		$values["ste_rc"]         = MySQL::SQLValue($this->_data['ste_rc']);
		$values["ste_website"]    = MySQL::SQLValue($this->_data['ste_website']);
		$values["updusr"]         = MySQL::SQLValue(session::get('userid'));
		$values["upddat"]         = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]             = $this->id_ste;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				//$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['ste_name'];
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
	 * [get_ste_banque Get all info for line]
	 * @return [type] [fill ste_info_banque array]
	 */
	public function get_ste_info_banque($banque)
	{
		global $db;
		$table = $this->table_b;
		//Format Select commande
		$sql = "SELECT $table.banque,$table.rib FROM 
		$table WHERE $table.id = ".$banque;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->banque_info = $db->RowArray();
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

    public  function get_ste_info_report_head($id_ste,$date,$object)
    {
    	$this->id_ste = $id_ste;
    	$this->get_ste_info();

    	if((date('Y-m-d', strtotime($date))) < (date('Y-m-d', strtotime('16-04-2020')))){

	    	$head = '<div style="color:#4A5375;font-size: 9pt;"><address><br>'.$this->ste_info['ste_adresse'].'<br>'.$this->ste_info['ste_ville'].' '.$this->ste_info['ste_pays'].'<br><abbr title="Phone">Tél: </abbr>'.$this->ste_info['ste_tel'].'<br>BP: </abbr>'.$this->ste_info['ste_bp'].' N\'Djamena<br>Email: '.$this->ste_info['ste_email'].'<br>Site web: '.$this->ste_info['ste_website'].'</address></div>';
	    }else{
	    	if($object == 'Facture'){
	        	$head = '<div style="color:black;font-size: 9pt;"><address><br>'.$this->ste_info['ste_adresse'].'<br>BP: </abbr>'.$this->ste_info['ste_bp'].' '.$this->ste_info['ste_ville'].'-'.$this->ste_info['ste_pays'].'<br><abbr title="Phone">Tél: </abbr>'.$this->ste_info['ste_tel_fact'].'<br><abbr title="email">Email: </abbr>admin.finance@globaltelnetworks.net<br><abbr title="siteweb">https://www.globaltelnetworks.net</abbr>  </address></div>';
	        }else{
	        	$head = '<div style="color:black;font-size: 9pt;"><address><br>'.$this->ste_info['ste_adresse'].'<br>BP: </abbr>'.$this->ste_info['ste_bp'].' '.$this->ste_info['ste_ville'].'-'.$this->ste_info['ste_pays'].'<br><abbr title="Phone">Tél: </abbr>'.$this->ste_info['ste_tel'].'<br><abbr title="email">Email: </abbr>admin.finance@globaltelnetworks.net<br><abbr title="siteweb">https://www.globaltelnetworks.net</abbr>  </address></div>';  		
    	    }
	    }

    	return $head;
    }

    public  function get_ste_info_report_footer($id_ste,$banque,$date,$object)
    {
    	$this->id_ste = $id_ste;
    	$this->get_ste_info();
    	$this->get_ste_info_banque($banque);


    	/*$footer = '<h1>'.$this->ste_info['ste_name'].'</h1><p>Télécommunications – Réseaux - Sécurité électronique - Prestation de Services<br/> Numéro d’Identification Fiscale : '.$this->ste_info['ste_if'].'<br/>Compte Orabank n°20403500201</p>';
*/	
    	if((date('Y-m-d', strtotime($date))) < (date('Y-m-d', strtotime('16-04-2020')))){
    		$footer = '</br><p>Télécommunications – Réseaux - Sécurité électronique - Prestation de Services<br/> Numéro d’Identification Fiscale : '.$this->ste_info['ste_if'].'<br/>Compte '.$this->banque_info['banque'].' N° '.$this->banque_info['rib'].'</p>';
   		
    	}else{
    		if($object == 'Facture'){
    		      //Modification du footer FZ HANOUNOU le 03/07/2021
    			 //$footer = '</br><p>NIF : '.$this->ste_info['ste_if'].'<br/>Compte '.$this->banque_info['banque'].' N° :'.$this->banque_info['rib'].'<br>Email: '.$this->ste_info['ste_email_fact'].'<br>Site web :'.$this->ste_info['ste_website'].'</p>';
    			$footer = '<style>.div-1 {background-color: #173C5A;color: #fff;}</style>
					  </br><div class="div-1"><p>Règlement à effectuer par virement à l’ordre de GLOBALTEL au compte '.$this->banque_info['banque'].' : '.$this->banque_info['rib']. '<br/>RCC : '.$this->ste_info['ste_rc'].'- NIF : '.$this->ste_info['ste_if'].'</p></div>';  

    		}else{
    			$footer = '</br><p>Email: '.$this->ste_info['ste_email'].'<br>Site web :'.$this->ste_info['ste_website'].'</p>';
    		}
    	}
    	    	
    	return $footer;
    }

   

}