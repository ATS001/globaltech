<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_service
//Created : 01-11-2018
//Model
/**
* M%modul% 
* Version 1.0
* 
*/

class Mobjectif_service {
	
	private $_data;                      //data receive from form
	var $table            = 'objectif_service';   //Main table of module
	var $last_id          = null;        //return last ID after insert command
	var $log              = null;        //Log of all opération.
	var $error            = true;        //Error bol changed when an error is occured
    var $id_objectif_service       = null;        // objectif_service ID append when request
	var $token            = null;        //user for recovery function
	var $objectif_service_info     = array();     //Array stock all objectif_service info
	

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
		//Get all info user fro database for edit form

	public function get_objectif_service()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.*, services.service FROM 
		$table, services WHERE services.id = $table.id_service AND  $table.id = ".$this->id_objectif_service;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->objectif_service_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array user_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
		}		
	}

    /**
     * [Get_detail_objectif_service_show description]
     */
    public function Get_detail_objectif_service_show()
    {
        global $db;
        $table = $this->table;
        $req_sql ="SELECT $table.*, $table.id AS id_objectif,
                   DATE_FORMAT($table.date_s,'%d-%m-%Y') AS date_s,
                   DATE_FORMAT($table.date_e,'%d-%m-%Y') AS date_e,
                   REPLACE(FORMAT($table.objectif,0),',',' ') AS objectif,
                   REPLACE(FORMAT($table.realise,0),',',' ') AS realise,
                   REPLACE(FORMAT($table.objectif - $table.realise,0),',',' ') AS reste,
                   $table.objectif AS objectif_int,
                   $table.realise AS realise_int,
                   $table.objectif - $table.realise AS reste_int,
                   services.service FROM 
                   $table, services WHERE services.id = $table.id_service AND  $table.id = ".$this->id_objectif_service;
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error();
        }else{
            if ($db->RowCount() == 0)
            {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->objectif_service_info = $db->RowArray();
                $this->error = true;
            }
        }
        if($this->error == false)
        {
            return false;
        }
        return true;

    }

	/**
	 * Save new row to main table
	 * @return [bol] [bol value send to controller]
	 */
	public function save_new_objectif_service(){
    

        $this->check_objectif_in_interval($this->_data['date_s'], $this->_data['date_e'], $this->_data['id_service']);

    // If we have an error
        if($this->error == true){
            global $db;
    //Add all fields for the table
            $values["description"]  = MySQL::SQLValue($this->_data["description"]);
            $values["objectif"]     = MySQL::SQLValue($this->_data["objectif"]);
            $values["realise"]      = MySQL::SQLValue($this->_data["realise"]);
            $values["id_service"]   = MySQL::SQLValue($this->_data["id_service"]);
            $values["date_s"]       = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_s'])));
            $values["date_e"]       = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_e'])));


            $values["creusr"]       = MySQL::SQLValue(session::get('userid'));

            if(!$result = $db->InsertRow($this->table, $values)) 
            {

                $this->log  .= $db->Error();
                $this->error = false;
                $this->log  .='</br>Enregistrement BD non réussie'; 

            }else{

                $this->last_id = $result;
                $this->log .='</br>Enregistrement  réussie Objectif :('. $this->_data['description'] .') - '.$this->last_id.' -';
                if(!Mlog::log_exec($this->table, $this->last_id, 'Création objectif_service', 'Insert'))
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

	/**
	 * Edit selected Row
	 * @return Bol [send to controller]
	 */
    public function edit_objectif_service(){
                    //$this->check_exist($column, $value, $message, $edit = 1);
                    //$this->check_non_exist($table, $column, $value, $message)
                    //Get existing data for row
        $this->get_objectif_service();
        $this->check_objectif_in_interval($this->_data['date_s'], $this->_data['date_e'], $this->_data['id_service'], true);

        $this->last_id = $this->id_objectif_service;
                    // If we have an error
        if($this->error == true)
        {
            global $db;
                    //ADD field row here
            $values["description"] = MySQL::SQLValue($this->_data["description"]);
            $values["objectif"]    = MySQL::SQLValue($this->_data["objectif"]);
            $values["id_service"]  = MySQL::SQLValue($this->_data["id_service"]);
            $values["date_s"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_s'])));
            $values["date_e"]      = MySQL::SQLValue(date('Y-m-d',strtotime($this->_data['date_e'])));
            
            
            $values["updusr"]      = MySQL::SQLValue(session::get('userid'));
            $values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));
            $wheres["id"]          = $this->id_objectif_service;
            
            if (!$result = $db->UpdateRows($this->table, $values, $wheres))
            {
                
                $this->log .= $db->Error();
                $this->error == false;
                $this->log .='</br>Enregistrement BD non réussie'; 

            }else{

                
                $this->log .='</br>Modification  réussie Objectif :('. $this->_data['description'] .') - '.$this->last_id.' -';
                if(!Mlog::log_exec($this->table, $this->last_id, 'Modification objectif_service', 'Update'))
                {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                    //Esspionage
                if(!$db->After_update($this->table, $this->id_objectif_service, $values, $this->objectif_service_info)){
                    $this->log .= '</br>Problème Espionnage';
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

    /**
     * Valide objectif_service
     * @return bol send to controller
     */
    public function valid_objectif_service()
    {
    	//Get existing data for row
		$this->get_objectif_service();
		
		$this->last_id = $this->id_objectif_service;
    	global $db;
        //Get Etat Objectif to validate
        $etat_validation =  Msetting::get_set('etat_objectifs', 'valid_obj');
        if($etat_validation == null)
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>manque de paramètre';
            $this->error = false;
            return false;
        }

        //Check if line have same value of setting
        if($etat_validation == $this->objectif_service_info['etat'])
        {
            $this->log   .= '</br>Cette ligne est dèja validée';
            $this->error = false;
            return false;
        }
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
    	

    	$values["etat"]        = MySQL::SQLValue($etat_validation);
    	$values["updusr"]      = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));

    	$wheres['id']     = $this->id_objectif_service;

		// Execute the update and show error case error
    	if(!$result = $db->UpdateRows($this->table, $values, $wheres))
    	{
    		$this->log   .= '</br>Impossible de changer le statut!';
    		$this->log   .= '</br>'.$db->Error();
    		$this->error  = false;

    	}else{
    		$this->log   .= '</br>Statut changé! ';
    		$this->error  = true;
    		if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  objectif_service', 'Update'))
    		{
    			$this->log .= '</br>Un problème de log ';
    			$this->error = false;
    		}
               //Esspionage
    		if(!$db->After_update($this->table, $this->id_objectif_service, $values, $this->objectif_service_info)){
    			$this->log .= '</br>Problème Espionnage';
    			$this->error = false;	
    		}

    	}
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}


    }

    /**
     * [debloque_objectif_service description]
     * @return [type] [description]
     */
    public function debloque_objectif_service()
    {
        //Get existing data for row
        $this->get_objectif_service();
        
        $this->last_id = $this->id_objectif_service;
        global $db;
        //Get Etat Objectif to validate
        $etat_creat =  Msetting::get_set('etat_objectifs', 'creat_obj');
        if($etat_creat == null)
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>manque de paramètre';
            $this->error = false;
            return false;
        }

        //Check if line have same value of setting
        if($etat_creat == $this->objectif_service_info['etat'])
        {
            $this->log   .= '</br>Cette ligne est dèja débloquée';
            $this->error = false;
            return false;
        }
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        

        $values["etat"]        = MySQL::SQLValue($etat_creat);
        $values["updusr"]      = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));

        $wheres['id']     = $this->id_objectif_service;

        // Execute the update and show error case error
        if(!$result = $db->UpdateRows($this->table, $values, $wheres))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>'.$db->Error();
            $this->error  = false;

        }else{
            $this->log   .= '</br>Statut changé! ';
            $this->error  = true;
            if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  objectif_service', 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
               //Esspionage
            if(!$db->After_update($this->table, $this->id_objectif_service, $values, $this->objectif_service_info)){
                $this->log .= '</br>Problème Espionnage';
                $this->error = false;   
            }

        }
        if($this->error == false){
            return false;
        }else{
            return true;
        }


    }

	/**
	 *  [check_non_exist Check if one entrie not exist on referential table]
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
    	$sql_edit = $edit == null ? null: " AND  <> $edit";
    	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
    		WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

    	if ($result != "0") {
    		$this->error = false;
    		$this->log .='</br>'.$message.' existe déjà';
    	}
    }



    /**
     * Delet selectd Row
     * @return bol [Send to controller]
     */
    public function delete_objectif_service()
    {
    	global $db;
    	$id_objectif_service = $this->id_objectif_service;
    	$this->get_objectif_service();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_objectif_service);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows($this->table, $where))
    	{
    		
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
     * [s Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function s($key)
    {
        if($this->objectif_service_info[$key] != null)
        {
            echo $this->objectif_service_info[$key];
        }else{
            echo "";
        }

    }
    /**
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key)
    {
        if($this->objectif_service_info[$key] != null)
        {
            return $this->objectif_service_info[$key];
        }else{
            return null;
        }

    }

    private function check_objectif_in_interval($date_s, $date_e, $servise_id,  $edit = false)
    {
       
        $date_s = MySQL::SQLValue(date('Y-m-d',strtotime($date_s)));
        $date_e = MySQL::SQLValue(date('Y-m-d',strtotime($date_e)));
        $is_edit = null;
        if($edit == true)
        {
          $this_id = $this->objectifs_info['id'];
          $is_edit = " AND id <> $this_id";
        }
        
        $table  = $this->table;
        global $db;
        $sql = " SELECT COUNT(id) FROM $table WHERE (date_s BETWEEN $date_s AND $date_e OR date_e BETWEEN $date_s AND $date_e) AND id_service = $servise_id $is_edit";
        $nbr_obj = $db->QuerySingleValue0($sql);
        
        if($nbr_obj > 0)
        {
            $this->log .= "</br>Il existe $nbr_obj des Objectifs dans la même période";
            $this->error = false;

        }

    }
    
    /**
     * [get_list_factures description]
     * @return [type] [description]
     */
    public function get_list_factures_for_objectif()
    {
        global $db;
        
        $add_set = array('return' => '<a href="#" class="report_tplt" rel="'.MInit::crypt_tp('tplt', 'facture').'" data="%crypt%"> <i class="ace-icon fa fa-print"></i></a>', 'data' => 'id');
        $id_objectif = $this->id_objectif_service;
        $date_s     = date('Y-m-d', strtotime($this->g('date_s')));
        $date_e     = date('Y-m-d', strtotime($this->g('date_e')));
        $id_service = $this->g('id_service');

        
        $req_sql = " SELECT  factures.id, factures.reference, 
        DATE_FORMAT(factures.date_facture,'%d-%m-%Y') AS datfact,
        REPLACE(FORMAT(factures.total_ht,0),',',' ') total_ht,
        REPLACE(FORMAT(factures.total_ttc,0),',',' ') total_ttc,
        REPLACE(FORMAT(factures.total_tva,0),',',' ') AS total_tva, 
        REPLACE(FORMAT(factures.total_paye,0),',',' ') AS total_paye, 
        REPLACE(FORMAT(factures.reste,0),',',' ') AS total_reste, '#'
        FROM `factures`
        INNER JOIN `devis` 
        ON (`devis`.`id` = IF(factures.`base_fact`='C',(SELECT ctr.iddevis FROM contrats ctr WHERE ctr.id=factures.`idcontrat`),`factures`.`iddevis` )) 
        INNER JOIN `encaissements` 
        ON (`encaissements`.`idfacture` = `factures`.`id`)
        INNER JOIN `commerciaux` 
        ON (`commerciaux`.`id` = `devis`.`id_commercial`)
        INNER JOIN `services` 
        ON (`commerciaux`.`id_service` = `services`.`id`)
        WHERE encaissements.etat IN(1, 0) AND encaissements.`date_encaissement` BETWEEN '$date_s' AND '$date_e' AND factures.etat <> 200
        AND services.id = $id_service GROUP BY factures.id;";
        

        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            
        }
        if(!$db->RowCount())
        {
             $output = '<div class="alert alert-danger">Pas de Facture enregistrés pour cet Objectif</div>'; 
             return $output;
        }
        
        
        $headers = array(
            'ID'            => '5[#]center',
            'Référence'     => '10[#]center',
            'Date création' => '10[#]center', 
            'Total HT'      => '10[#]alignRight', 
            'Total TTC'     => '10[#]alignRight', 
            'Total TVA'     => '10[#]alignRight', 
            'Payé'          => '10[#]alignRight', 
            'Rest'          => '10[#]alignRight', 
            '#'             => '5[#]center[#]crypt', 
            
            
            
        );
                 
                
        $tableau = $db->GetMTable($headers, $add_set);
        return $tableau;
    }

    public function get_list_encaissemen_for_objectif()
    {
        global $db;
        
        $add_set = array('return' => '<a href="#" class="this_url" rel="detailsencaissement" data="%crypt%"> <i class="ace-icon fa fa-eye blue bigger-100"></i></a>', 'data' => 'id');
        $id_objectif = $this->id_objectif_service;
        $date_s     = date('Y-m-d', strtotime($this->g('date_s')));
        $date_e     = date('Y-m-d', strtotime($this->g('date_e')));
        $id_service = $this->g('id_service');
        
        $req_sql = "SELECT
        `encaissements`.`id`
        , `encaissements`.`reference`
        , `encaissements`.`designation`
        , REPLACE(FORMAT(`encaissements`.`montant`,0),',',' ') montant
        , DATE_FORMAT(`encaissements`.`date_encaissement`,'%d-%m-%Y') AS date_ncaissement, '#'    
        FROM
        `encaissements`
        INNER JOIN `factures` 
        ON (`encaissements`.`idfacture` = `factures`.`id`)
        INNER JOIN `devis` 
        ON (`devis`.`id` = IF(factures.`base_fact`='C',(SELECT ctr.iddevis FROM contrats ctr WHERE ctr.id=factures.`idcontrat`),`factures`.`iddevis` )) 
        INNER JOIN `commerciaux` 
        ON (`devis`.`id_commercial` = `commerciaux`.`id`)
        INNER JOIN `services` 
        ON (`commerciaux`.`id_service` = `services`.`id`)
        WHERE encaissements.etat IN(1, 0) 
        AND encaissements.`date_encaissement` BETWEEN '$date_s' AND '$date_e' 
        AND encaissements.etat <> 200 AND services.id = $id_service";
        
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            
        }
        if(!$db->RowCount())
        {
             $output = '<div class="alert alert-danger">Pas d\'Encaissement enregistrés pour cet Objectif</div>'; 
             return $output;
        }
        
        
        $headers = array(
            'ID'           => '5[#]center',
            'Référence'    => '10[#]center',
            'Designation'  => '15[#]center',
            'Montant'      => '10[#]center',
            'Date'         => '5[#]',     
            '#'            => '3[#]center[#]crypt', 
            
            
            
        );
                 
                
        $tableau = $db->GetMTable($headers, $add_set);
        return $tableau;
    }


    public function get_list_devis_for_objectif()
    {
        global $db;
        
        $etat_devis = Msetting::get_set('etat_devis', 'valid_client').', '.Msetting::get_set('etat_devis', 'devis_livr').', '.Msetting::get_set('etat_devis', 'devis_archive') ;
        $add_set       = array('return' => '<a href="#" class="this_url" rel="viewdevis" data="%crypt%"> <i class="ace-icon fa fa-eye blue bigger-100"></i></a>', 'data' => 'id');
        $id_objectif   = $this->id_objectif_mensuel;
        $date_s        = date('Y-m-d', strtotime($this->g('date_s')));
        $date_e        = date('Y-m-d', strtotime($this->g('date_e')));
        //$id_commercial = $this->g('id_commercial');
        
        $req_sql = "SELECT devis.`id` id,devis.`reference`, clients.denomination,        
        REPLACE(FORMAT(devis.`totalttc`,0),',',' ') total_ttc,
        DATE_FORMAT(devis.`date_valid_client`,'%d-%m-%Y') AS date_valid_client, '#' 
        FROM `devis`  
        INNER JOIN `clients` 
        ON (`devis`.`id_client` = `clients`.`id`)
        WHERE devis.`date_valid_client` BETWEEN '$date_s' AND '$date_e' AND devis.etat IN( $etat_devis )";
        //exit($req_sql);
        
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            
        }
        if(!$db->RowCount())
        {
             $output = '<div class="alert alert-danger">Pas de Devis enregistrés pour cet Objectif</div>'; 
             return $output;
        }
        
        
        $headers = array(
            'ID'           => '5[#]center',
            'Référence'    => '10[#]center',
            'Client'       => '25[#]',
            'Montant'      => '10[#]center',
            'Date'         => '5[#]',     
            '#'            => '3[#]center[#]crypt',          
            
            
        );
                 
                
        $tableau = $db->GetMTable($headers, $add_set);
        return $tableau;
    }

    static public function indicator_objectif_service()
    {
        
        global $db;
        $etat_objectif = Msetting::get_set('etat_objectifs', 'valid_obj');
        $table = 'objectif_service';
        $id_service = session::get('service');
        $where = null;
        if(in_array($id_service, array(1, 3))){
            $where = null; 
        }elseif(in_array($id_service, array(2, 7))){
            $where = " AND $table.id_service IN (2, 7) ";
        }
        $req_sql ="SELECT $table.id, REPLACE(FORMAT($table.objectif,0),',',' ') AS objectif,
                   REPLACE(FORMAT($table.realise,0),',',' ') AS realise,
                   ROUND($table.realise * 100 / $table.objectif, 2) AS percent,
                   $table.objectif - $table.realise AS reste_int FROM  $table 
                   WHERE $table.etat = $etat_objectif  $where ";
        
        if(!$db->Query($req_sql))
        {
            return false;
        }else{
            if (!$db->RowCount())
            {
                return false;
            } else {
                $arr_result = $db->RowArray();                
            }
        }
        $id_service = session::get('service');
        if(in_array($id_service, array(1, 3, 2))){
            $ca_global = '<p>CA Global: '.$arr_result['realise'].'</p>';
        }elseif(in_array($id_service, array(2))){
            $ca_global = '<p>.</p>';
        }else{
            $ca_global = '<p>.</p>';
        }
        $idc = MInit::crypt_tp('id', $arr_result['id']);
        $output =  '<div class="col-lg-3 col-6">
                        <div class="small-box btn-purple">
                            <div class="inner">
                                <h3>'.$arr_result['percent'].'<sup style="font-size: 20px">%</sup></h3>
                                '.$ca_global.'
                            </div>
                        <div class="icon">
                            <i class="ace-icon fa fa-line-chart home-icon"></i>
                        </div>
                        <a href="#" rel="detail_objectif_service" data="'.$idc.'"  class="small-box-footer this_url">Voir détails <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>';
        return print($output);            
    }


}