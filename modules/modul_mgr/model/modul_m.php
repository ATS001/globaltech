<?php

/**
* Class Gestion Modules V1.0
*/


class Mmodul {

	private $_data; //data receive from form

	var $last_id; //return last ID after insert command
	var $log = ''; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
	var $exige_pkg; // set when form is required must be default true.
	var $default_app;
	var $new_form = ''; //set new form path
	var $id_modul; // Modul ID append when request
	var $token; //user for recovery function
	var $modul_info; //Array stock all userinfo 
	var $task_info; //Array stock all userinfo 
	var $app_action; //Array action for each row
	var $new_modul = false;
	var $EndOfSeek;
	var $rows;
	var $lines_action       = null;//All field for form action
	var $lines_action_check = null;//All field for form action checker
	var $lines_form_add     = null;//All field for form ADD
	var $lines_form_edit    = null;//All field for form EDIT
    var $lines_modul  = null;//All field for modul query
    var $lines_select = null;//All field for Datatable
    var $lines_profil = null;//All field for Profile view
    
	

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
	 * Function Get modul info 
	 * fit $this->modul_info (Array) 
	 * @return true
	 */

	public function get_modul()
	{
		global $db;

		$sql = "SELECT modul.*, task.id as id_app, task.app, task.rep as modul_rep, task.sbclass, task_action.etat_desc, task_action.message_class
		FROM 
		modul, task, task_action
		WHERE  task_action.etat_line = 0 AND task_action.app = task.app  AND modul.app_modul = task.app AND modul.id = ".$this->id_modul;
		//exit($sql);
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= 'Get_modul '.$db->Error().' '.$sql;
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->modul_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		
		//return Array user_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}

	/**
	 * Get modul for permission user
	 * @param string modul
	 * @return Array array id - modul description
	 */
	public function Get_list_modul()
	{
		global $db;
		$modul_array = array();
		$sql_modul = "SELECT modul.id, modul.modul, modul.services, modul.description  FROM modul group by modul ";
		if(!$db->Query($sql_modul))
		{
			$db->kill($db->Error());
		}else{
			$modul_array = $db->RecordsArray();
			return $modul_array;
		}
	}

	/**
	 * Get Actions for each modul 
	 * used for permission user
	 * @param int $[userid] [Id user]
	 * @param int $[id] [id modul]
	 * @return String SQL query used on each modul.
	 */
	public function Get_action_modul($id, $user)
	{
		global $db;


		$sql = "SELECT
		`task_action`.`descrip` AS app_name
		, `task_action`.`appid` AS app_id
		, `task_action`.`idf` AS idf
		, `task_action`.`type`  AS type
		, `task_action`.`code`  AS code
		, `task_action`.`etat_line`  AS etat_line
		, `task_action`.`id`    AS action_id
		, `task_action`.`service`    AS service
		,(
		CASE
		WHEN (SELECT 1 FROM rules_action WHERE rules_action.`action_id` =  task_action.id AND rules_action.userid = $user GROUP BY userid) = 1 THEN 1
		ELSE 0
		END
		) AS exist_rule
		FROM
		`task`
		INNER JOIN `modul` 
		ON (`task`.`modul` = `modul`.`id`)
		INNER JOIN `task_action` 
		ON (`task_action`.`appid` = `task`.`id`)
		WHERE `modul`.`id` = ".$id;
		return $sql; 
	}




	/**
	 * [Shw Show element of Array modul_info]
	 * @param [type] $key     [Key of element]
	 * @param [type] $no_echo [set null return variable into code else Echo variabl into html]
	 */
	public function Shw($key, $no_echo = null)
	{
		if($this->modul_info[$key] != null)
		{
			if($no_echo == null)
			{
				return $this->modul_info[$key];
			}

			echo $this->modul_info[$key];
		}else{
			echo "";
		}
		
	}

	

	private function creat_modul_path($modul_rep)
	{
		$modul_path = MPATH_MODULES.$modul_rep;
		//exit($modul_path);

		if(!file_exists($modul_path))
		{
			if(!mkdir($modul_path, 0777, true))
			{
				$this->error = false;
				$this->log .='</br>Unable Create modul folder'; 
				return false;
			}
		}

		if(!file_exists($modul_path.'/model'))
		{
			if(!mkdir($modul_path.'/model'))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Model folder'; 
				return false;
			}
		}
		if(!file_exists($modul_path.'/controller'))
		{
			if(!mkdir($modul_path.'/controller'))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Controller folder'; 
				return false;
			}
		}
		if(!file_exists($modul_path.'/view'))
		{
			if(!mkdir($modul_path.'/view'))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View folder'; 
				return false;
			}
		}
		return true;
	}

	private function edit_modul_path($new_modul_rep, $old_modul_rep)
	{
		$new_modul_path = MPATH_MODULES.$new_modul_rep;
		$old_modul_path = MPATH_MODULES.$old_modul_rep;

		if(!file_exists($new_modul_path) and file_exists($old_modul_path))
		{
			if(!rename($old_modul_path, $new_modul_path))
			{
				$this->error = false;
				$this->log .='</br>Unable to rename modul folder'; 
				return false;
			}
		}
	}



	/**
	 * [creat_task_files create All file for new task]
	 * @param  [string regex] $modul_name [description]
	 * @param  [string regex] $task_name  [description]
	 * @return [bol]             [description]
	 */
	private function creat_task_files($modul_rep, $task_name, $modul_name, $type_view, $app_base, $table)
	{
		

		$modul_path = MPATH_MODULES.$modul_rep;
		//exit("2#".$modul_path);

		if($modul_rep == null)
		{
			$this->error = false;
			$this->log .='</br>Unable get Module Path'; 
			return false;

		}

		
		$file_c = $modul_path.'/controller/'.$task_name.'_c.php';
		$file_list_c = $modul_path.'/controller/list'.$task_name.'_c.php';
		$file_action_c = $modul_path.'/controller/action'.$task_name.'_c.php';
		$file_m = $modul_path.'/model/'.$modul_name.'_m.php';
		$file_v = $modul_path.'/view/'.$task_name.'_v.php';

		$templat_folder = MPATH_LIBRARIES.'templates_script/';
		$template = null;
		$content   = '<?php '. PHP_EOL .'//First check target no Hack'.PHP_EOL."if(!defined('_MEXEC'))die();".PHP_EOL.'//'.MCfg::get('sys_titre'). PHP_EOL .'// Modul: '.$modul_name.PHP_EOL.'//Created : '.date('d-m-Y'). PHP_EOL.'//';

		

		//Main task of modul (list)
        if($app_base == true && $type_view == 'list')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control.php')){
				$template_c = file_get_contents($templat_folder.'template_control.php');
				$template = str_replace('%task%', $task_name, $template_c);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//List
			$template = null;
			if(file_exists($templat_folder.'template_list.php')){
				$template_c = file_get_contents($templat_folder.'template_list.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_list_c) && !file_put_contents($file_list_c, $content.'Controller Liste'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Controller Liste'; 
				return false;
			}
			//Action
			$template = null;
			if(file_exists($templat_folder.'template_action.php')){
				$template_c = file_get_contents($templat_folder.'template_action.php');
				$template = str_replace('%model%', $modul_name, $template_c);
				$template = str_replace('%table%', $table, $template);
			}
			if(!file_exists($file_action_c) && !file_put_contents($file_action_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view.php')){
				$template_c = file_get_contents($templat_folder.'template_view.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
			//Model
			$template = null;
			if(file_exists($templat_folder.'template_model.php')){
				$template_c = file_get_contents($templat_folder.'template_model.php');
				$template = str_replace('%model%', $modul_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_modul%', $this->lines_modul, $template);
				
			}
			if(!file_exists($file_m) && !file_put_contents($file_m, $content.'Model'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Model'; 
				return false;
			}
		}
		//Task list no main
        if($app_base == false && $type_view == 'list')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control.php')){
				$template_c = file_get_contents($templat_folder.'template_control.php');
				$template = str_replace('%task%', $task_name, $template_c);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//List
			$template = null;
			if(file_exists($templat_folder.'template_list.php')){
				$template_c = file_get_contents($templat_folder.'template_list.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_list_c) && !file_put_contents($file_list_c, $content.'Controller Liste'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Controller Liste'; 
				return false;
			}
			//Action
			$template = null;
			if(file_exists($templat_folder.'template_action.php')){
				$template_c = file_get_contents($templat_folder.'template_action.php');
				$template = str_replace('%model%', $modul_name, $template_c);
				$template = str_replace('%table%', $table, $template);
			}
			if(!file_exists($file_action_c) && !file_put_contents($file_action_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view.php')){
				$template_c = file_get_contents($templat_folder.'template_view.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}
		//Task form add
		if($app_base == false && $type_view == 'formadd')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_formadd.php')){
				$template_c = file_get_contents($templat_folder.'template_control_formadd.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				$template = str_replace('%lines_action%', $this->lines_action, $template);
				$template = str_replace('%lines_action_check%', $this->lines_action_check, $template);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller ADD Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_formadd.php')){
				$template_c = file_get_contents($templat_folder.'template_view_formadd.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				$template = str_replace('%list_input_add%', $this->lines_form_add, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}

		//Task form Edit
		if($app_base == false && $type_view == 'formedit')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_formedit.php')){
				$template_c = file_get_contents($templat_folder.'template_control_formedit.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				$template = str_replace('%lines_action%', $this->lines_action, $template);
				$template = str_replace('%lines_action_check%', $this->lines_action_check, $template);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller EDIT Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_formedit.php')){
				$template_c = file_get_contents($templat_folder.'template_view_formedit.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%list_input_edit%', $this->lines_form_edit, $template);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}

		//Task form Costumized
		if($app_base == false && $type_view == 'formpers')
		{
			
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_formpers.php')){
				$template_c = file_get_contents($templat_folder.'template_control_formpers.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller EDIT Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_formpers.php')){
				$template_c = file_get_contents($templat_folder.'template_view_formpers.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}

		//Task Exec
		if($app_base == false && $type_view == 'exec')
		{
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_exec.php')){
				$template_c = file_get_contents($templat_folder.'template_exec.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller EXEC Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			
		}

		//Task Profile
		if($app_base == false && $type_view == 'profil')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_profil.php')){
				$template_c = file_get_contents($templat_folder.'template_control_profil.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);

				
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller PROFILE Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_profil.php')){
				$template_c = file_get_contents($templat_folder.'template_view_profil.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%list_profil%', $this->lines_profil, $template);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}
		return true;



	}


	

    //Save new user after all check
	public function save_new_modul(){

		
       //Befor execute do the multiple check
		$this->check_exist_modul();
		$this->check_exist_task();
		//Format multipl services
		$services = json_encode($this->_data['services']);
		$services = str_replace('"', '-', $services);
		$services = str_replace('-,-', '-', $services);

        //Format modulfolder : 0 =>main 1 => setting 2 => submodul 
		switch ($this->_data['is_setting']) {
			case 0:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
			case 1:
			$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
			break;
			case 2:
			$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
			break;
			default:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
		}
        //$folder = $this->_data['is_setting'] == 0 ? $this->_data['modul'] : $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
        //exit($folder);

		global $db;
		$values["modul"]         = MySQL::SQLValue($this->_data['modul']);
		$values["rep_modul"]     = MySQL::SQLValue($folder);
		$values["is_setting"]    = MySQL::SQLValue($this->_data['is_setting']);
		$values["modul_setting"] = MySQL::SQLValue($this->_data['modul_setting']);
		$values["description"]   = MySQL::SQLValue($this->_data['description']);
		$values["tables"]        = MySQL::SQLValue($this->_data['tables']);
		$values["app_modul"]     = MySQL::SQLValue($this->_data['app']);
		$values["services"]      = MySQL::SQLValue($services);
		$values["etat"]          = MySQL::SQLValue($this->_data['etat']);
		
		//check if package required stop Insert
		//$this->check_file('pkg', 'Le Package de module.');

		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow("modul", $values))
			{
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				
				//Save first default APP for modul into Task
				$this->creat_modul_path($folder);
				$this->new_modul = true;
				$this->save_new_task($this->last_id);
				
				if($this->error == true)
				{
					$this->log = '</br>Enregistrement réussie: <b>'.$this->_data['description'];
					
				}else{
					$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['description'];
					$this->log .= '</br>Un problème d\'Enregistrement ';
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


	//Edit existing modul after all check
	public function edit_exist_modul(){

		
       //Befor execute do the multiple check
       //$services_last = NULL
		$this->id_modul = $this->_data['id'];
		$this->get_modul();
		$this->last_id     = $this->_data['id'];
		$this->default_app = $this->_data['id_app'];
		$this->check_exist_modul(1);
		$this->check_exist_task(1);
		//Format multipl services
		if($this->_data['services'] != NULL)
		{
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);
			$services_last = $services != $this->modul_info['services'] ? $services : $this->modul_info['services'];

		}else{
			$services_last = $this->modul_info['services'];
		}

		//Format modulfolder : 0 =>main 1 => setting 2 => submodul 
		switch ($this->_data['is_setting']) {
			case 0:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
			case 1:
			$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
			break;
			case 2:
			$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
			break;
			default:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
		}
        //$folder = $this->_data['is_setting'] == 0 ? $this->_data['modul'] : $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];

		global $db;
		$values["modul"]         = MySQL::SQLValue($this->_data['modul']);
		$values["rep_modul"]     = MySQL::SQLValue($folder);
		$values["is_setting"]    = MySQL::SQLValue($this->_data['is_setting']);
		$values["modul_setting"] = MySQL::SQLValue($this->_data['modul_setting']);
		$values["description"]   = MySQL::SQLValue($this->_data['description']);
		$values["tables"]        = MySQL::SQLValue($this->_data['tables']);
		$values["app_modul"]     = MySQL::SQLValue($this->_data['app']);
		$values["services"]      = MySQL::SQLValue($services_last);
		$wheres["id"]            = MySQL::SQLValue($this->_data['id']);
		//var_dump($this->_data);
		//exit();
		
		
		//check if package required stop Insert
		//$this->check_file('pkg', 'Le Package de module.');

		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("modul", $values, $wheres)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Modification BD non réussie'; 

			}else{

				$this->last_id = $result;
				//Rename the Modul folder
				if($this->_data['modul'] != $this->modul_info['rep_modul'])
				{
					$this->edit_modul_path($folder, $this->modul_info['rep_modul']);
				}

				
				//Edit first default APP for modul into Task
				$this->edit_task_from_edit_modul($this->_data['id_app'], $this->_data['id'], $services_last);
				
				if($this->error == true)
				{
					$this->log = '</br>Modification réussie: <b>';
					
				}else{
					$this->log .= '</br>Modification non réussie: <b>';
					$this->log .= '</br>Un problème de Modification ';
				}


			}


		}else{

			$this->log .='</br>Modification non réussie';

		}

        //check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}

	}

	private function edit_task_from_edit_modul($id_task, $id_modul, $services)
	{
		
        //Format modulfolder : 0 =>main 1 => setting 2 => submodul 
		switch ($this->_data['is_setting']) {
			case 0:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
			case 1:
			$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
			break;
			case 2:
			$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
			break;
			default:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
		}


		global $db;
		$values["app"]         = MySQL::SQLValue($this->_data['app']);
		$values["file"]        = MySQL::SQLValue($this->_data['app']);
		$values["rep"]         = MySQL::SQLValue($folder);
		$values["modul"]       = MySQL::SQLValue($id_modul);
		$values["dscrip"]      = MySQL::SQLValue($this->_data['description']);
		$values["session"]     = MySQL::SQLValue(1);
		$values["services"]    = MySQL::SQLValue($services);
		$values["sbclass"]     = MySQL::SQLValue($this->_data['sbclass']);
		$values["ajax"]        = MySQL::SQLValue(1);
		$values["app_sys"]     = MySQL::SQLValue(0);
		$values["etat"]        = MySQL::SQLValue(0);
		$wheres["id"]          = MySQL::SQLValue($id_task);



		
		if(!$result = $db->UpdateRows("task", $values, $wheres)) 
		{

			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Modification Task BD non réussie'; 


		}else{
			$this->error = true;
			$this->log .='</br>Modification Task réussie';
			//Rename files if app modified
			if($this->_data['app'] != $this->modul_info['app_modul'])
			{
				
				$this->rename_app_files($folder, $this->modul_info['app_modul'], $this->_data['app']);
			}
			$this->edit_default_task_action($id_task, $this->_data['description'], $this->_data['message_class'], $this->_data['etat_desc']);

		}
		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}


	}

	private function rename_app_files($modul_rep, $old_task_name, $new_task_name )
	{
		$modul_path = MPATH_MODULES.$modul_rep;
		//exit("2#".$modul_path);

		if($modul_rep == null)
		{
			$this->error = false;
			$this->log .='</br>Unable get Module Path'; 
			return false;

		}

		//Old names
		$old_file_c = $modul_path.'/controller/'.$old_task_name.'_c.php';
		$old_file_list_c = $modul_path.'/controller/list'.$old_task_name.'_c.php';
		$old_file_action_c = $modul_path.'/controller/action'.$old_task_name.'_c.php';
		$old_file_m = $modul_path.'/model/'.$old_task_name.'_m.php';
		$old_file_v = $modul_path.'/view/'.$old_task_name.'_v.php';
		//New Names
		$new_file_c = $modul_path.'/controller/'.$new_task_name.'_c.php';
		$new_file_list_c = $modul_path.'/controller/list'.$new_task_name.'_c.php';
		$new_file_action_c = $modul_path.'/controller/action'.$new_task_name.'_c.php';
		$new_file_m = $modul_path.'/model/'.$new_task_name.'_m.php';
		$new_file_v = $modul_path.'/view/'.$new_task_name.'_v.php';

		if(!file_exists($old_file_c) or !rename($old_file_c, $new_file_c))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_c  '.$old_file_c.'  '.$new_file_c; 
			//return false;
		}
		if(!file_exists($old_file_list_c) or !rename($old_file_list_c, $new_file_list_c))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_list_c'; 
			//return false;
		}
		if(!file_exists($old_file_action_c) or !rename($old_file_action_c, $new_file_action_c))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_action_c'; 
			//return false;
		}
		if(!file_exists($old_file_m) or !rename($old_file_m, $new_file_m))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_m'; 
			//return false;
		}
		if(!file_exists($old_file_v) or !rename($old_file_v, $new_file_v))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_v'; 
			//return false;
		}
	}



	

	



	//Check exist modul
	private function check_exist_modul($edit = null)
	{
		global $db;

		$sql_edit = $edit == null ? null: " AND id <> ".$this->last_id;
		$result = $db->QuerySingleValue0("SELECT modul FROM modul 
			where modul = ". MySQL::SQLValue($this->_data['modul']) ." $sql_edit ");
		if ($result != "0") {

			$this->error = false;
			$this->log .='</br>Exist Module '.$this->_data['modul'];


		}

	}

	//Check exist task
	private function check_exist_task($edit = null)
	{
		global $db;

		$sql_edit = $edit == null ? null: " AND id <> ".$this->default_app;
		$sql      = "SELECT app FROM task 
		where app = ". MySQL::SQLValue($this->_data['app']) ." $sql_edit ";
		$result = $db->QuerySingleValue0($sql);
		
		if ($result != "0") {

			$this->error = false;
			$this->log .='</br>Exist App '.$this->_data['app'];


		}

	}

	/*////Check exist task_action
	private function check_exist_task($id_task_action)
	{
		global $db;
		$this->get_task_action();

		$sql_edit = $edit == null ? null: " AND id <> ".$this->id_task_action;
		$sql      = "SELECT id FROM task_action 
		where id = ". MySQL::SQLValue($this->_data['app']) ." $sql_edit ";
		$result = $db->QuerySingleValue0($sql);
		
		if ($result != "0") {

			$this->error = false;
			$this->log .='</br>Exist App '.$this->_data['app'];


		}

	}*/
	
	
	
	


	//Check photo if required stop Insert this must be placed befor Insert commande
	Private function check_file($item, $msg = null, $edit = null)
	{
		if($edit != null && !file_exists($edit)){
			$this->log .= '</br>Il faut choisir '.$msg.' pour la mise à jour';
			$this->error = false;
		}else{
			if($edit == null && $this->exige_.$item == true && ($this->_data[$item.'_id'] == null || !file_exists($this->_data[$item.'_id'])))
			{
				$this->log .= '</br>Il faut choisir '.$msg. '  '.$edit;
				$this->error = false; 
			}
		}

	}






/**
 * ZONE TASK FOR MODULE
 * Function get Task
 * Function Add Task
 * Function Edit Task
 * Function delete Task
 * Function get task_action_liste
 */
 var $id_task; // Task ID append when request


//Get all info task fro database for edit form

 public function get_task()
 {
 	global $db;

 	$sql = "SELECT task.*, task_action.message_class, task_action.etat_desc
 	FROM task, task_action
 	WHERE task_action.appid = task.id AND  task.id = ".$this->id_task;
 	if(!$db->Query($sql))
 	{
 		$this->error = false;
 		$this->log  .= $db->Error();
 	}else{
 		if ($db->RowCount() == 0) {
 			$this->error = false;
 			$this->log .= 'Aucun enregistrement trouvé ';
 		} else {
 			$this->task_info = $db->RowArray();
 			$this->error = true;
 		}


 	}
		//return Array user_info
 	if($this->error == false)
 	{
 		return false;
 	}else{
 		return true;
 	}

 }

    /**
	 * [save_new_task Save Task into table Task]
	 * @param  [int] $modul_id [id of mdule]
	 * @return [fit error]           [fit Error variable]
	 */
    public function save_new_task($modul_id)
    {
    	global $db;
		//determine modul if new or existing
		if($this->new_modul == false) //Exist
		{
			//Get modul Name
			$this->id_modul = $modul_id;
			$this->get_modul();
			$modul_name = $this->modul_info['modul'];
		    //Format Variabl for DB

			$app           = $this->_data['app'];
			$file          = $this->_data['app'];
			$rep           = $this->modul_info['rep_modul'];
			$modul         = $this->modul_info['modul'];
			$table         = $this->modul_info['tables'];
			$dscrip        = $this->_data['description'];
			$sbclass       = $this->_data['sbclass'];
			$session       = 1;
			$ajax          = 1;
			//$app_sys     = $this->_data['app_sys'];
			$etat          = 0;
			$type_view     = $this->_data['type_view'];
			$app_base      = false;
			$services      = $this->modul_info['services'];
			$message_class = $this->_data['message_class'];
			$etat_desc     = $this->_data['etat_desc'];
			




		}else{ // no exist

			$modul_name = $this->_data['modul'];
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);
            //Format modulfolder
            //Format modulfolder : 0 =>main 1 => setting 2 => submodul 
			switch ($this->_data['is_setting']) {
				case 0:
				$folder = $this->_data['modul'].SLASH.'main'; 
				break;
				case 1:
				$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
				break;
				case 2:
				$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
				break;
				default:
				$folder = $this->_data['modul'].SLASH.'main'; 
				break;
			}

			

		    //Format Variabl for DB

			$app           = $this->_data['app'];
			$file          = $this->_data['app'];
			$rep           = $folder;
			$modul         = $modul_name;
			$table         = $this->_data['tables'];
			$dscrip        = $this->_data['description'];
			$sbclass       = $this->_data['sbclass'];
			$session       = 1;
			$ajax          = 1;
			//$app_sys     = 0;
			$etat          = 0;
			$type_view     = 'list';
			$app_base      = true;
			$services      = $services;
			$message_class = $this->_data['message_class'];
			$etat_desc     = $this->_data['etat_desc'];

		}
		
		
		//Befor execute do the multiple check
		$this->check_exist_task();

		


		$values["app"]         = MySQL::SQLValue($app);
		$values["file"]        = MySQL::SQLValue($file);
		$values["rep"]         = MySQL::SQLValue($rep);
		$values["modul"]       = MySQL::SQLValue($modul_id);
		$values["dscrip"]      = MySQL::SQLValue($dscrip);
		$values["session"]     = MySQL::SQLValue($session);
		$values["sbclass"]     = MySQL::SQLValue($sbclass);
		$values["ajax"]        = MySQL::SQLValue($ajax);
		//$values["app_sys"]     = MySQL::SQLValue($app_sys);
		$values["etat"]        = MySQL::SQLValue($etat);
		$values["type_view"]   = MySQL::SQLValue($type_view);
		$values["services"]    = MySQL::SQLValue($services);

		


		// If we have an error
		if($this->error == true)
		{
			
			if(!$result = $db->InsertRow("task", $values)) 
			{

				$db->Kill('2#'.$db->Error());
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement Task BD non réussie'; 

			}else{
		    	//Creat files of task
		    	//creat_task_files($modul_rep, $task_name, $modul_name, $type_view, $app_base, $table)
				$this->creat_task_files($rep, $app, $modul_name, $type_view, $app_base, $table);
		    	//Creat default task Action
				if($this->error == true && $this->add_default_task_action($result, $dscrip, $message_class, $etat_desc))
				{
					$this->log .='</br>Enregistrement réussie';
				}else{
					$this->error = false;
					$this->log .='</br>Enregistrement non réussie';

				}

			}
		}else{
			$this->error = false;
			$this->log .='</br>Enregistrement non réussie';

		}
		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}

		


	}



	public function edit_exist_task($modul_id)
	{
		global $db;
		$this->id_modul = $modul_id;
		$this->get_modul();


		$modul_name = $this->modul_info['modul'];

		//Befor execute do the multiple check
		$this->default_app = $this->_data['id_app'];
		//Get Task info
		$this->id_task =$this->default_app;
		$this->get_task();
		$this->check_exist_task(1);

		//var_dump($this->_data);
		if($this->_data['services'] != null){
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);

		}else{
			$services = $this->task_info['services'];

		}
		



		    //Format Variabl for DB

		$app       = $this->_data['app'];
		$file      = $this->_data['app'];
		$rep       = $modul_name;
		$modul     = $modul_id;
		$dscrip    = $this->_data['description'];
		$sbclass   = $this->_data['sbclass'];
		$type_view = $this->_data['type_view'];
		$session   = 1;
		$ajax      = 1;
		//$app_sys   = 0;
		$etat      = 0;
		$services  = $services;

		
		
		
		$values["app"]         = MySQL::SQLValue($app);
		$values["file"]        = MySQL::SQLValue($file);
		$values["rep"]         = MySQL::SQLValue($this->modul_info['rep_modul']);
		$values["modul"]       = MySQL::SQLValue($modul_id);
		$values["dscrip"]      = MySQL::SQLValue($dscrip);
		$values["session"]     = MySQL::SQLValue($session);
		$values["sbclass"]     = MySQL::SQLValue($sbclass);
		$values["ajax"]        = MySQL::SQLValue($ajax);
		//$values["app_sys"]     = MySQL::SQLValue($app_sys);
		$values["etat"]        = MySQL::SQLValue($etat);
		$values["type_view"]   = MySQL::SQLValue($type_view);
		$values["services"]    = MySQL::SQLValue($services);
		

		$wheres["id"]         = MySQL::SQLValue($this->_data['id_app']);

		// If we have an error
		if($this->error == true)
		{
			if(!$result = $db->UpdateRows("task", $values, $wheres)) 
			{
		    	//exit($db->BuildSQLUpdate("task", $values, $wheres));

				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement Task BD non réussie'; 

			}else{
				//Rename files if app modified
				if($this->_data['app'] != $this->task_info['app'])
				{
				    //rename_app_files($modul_rep, $old_task_name, $new_task_name )
					$this->rename_app_files($rep, $this->task_info['app'], $this->_data['app']);
				}
				$this->edit_default_task_action($this->_data['id_app'], $this->_data['description'], $this->_data['message_class'], $this->_data['etat_desc']);
				$this->error = true;
				$this->log .='</br>Modification réussie';
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

	static public function check_exist_service_etat($services, $etat, $appid, $edit)
	{
		global $db;
		$return = true;
		foreach ($services as $service) {
			$edit_check = $edit == null ? 0 : 1;
			$result = $db->QuerySingleValue0("SELECT COUNT(id) FROM task_action WHERE etat_line = $etat AND service LIKE '%-$service-%' AND appid = $appid AND TYPE = 1  ");

			if ($result != $edit_check) {

				$return = false;
			}
		}

		return $return;


	}

	/**
	 * [add_default_task_action Save default Task action for new added task]
	 * @param [int] $app_id [id of task]
	 * @param [string] $description [Description of task action]
	 * @return [fit error]           [fit Error variable]
	 */

	public function add_default_task_action($app_id, $description, $message_class, $etat_desc)
	{


		
       //Befor execute do the multiple check
		$services = json_encode($this->_data['services']);
		$services = str_replace('"', '-', $services);
		$services = str_replace('-,-', '-', $services);
		$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
        $idf = MD5($description.'0def');
        $this->check_exist_idf($idf); 
		global $db;
		//$service               = '-'.session::get('service').'-';
		$values["appid"]         = MySQL::SQLValue($app_id);
		$values["app"]           = MySQL::SQLValue($this->_data['app']);
		//to difference of next task action we use 0def
		$values["idf"]           = MySQL::SQLValue($idf);
		$values["descrip"]       = MySQL::SQLValue($description);
		$values["type"]          = MySQL::SQLValue(1);
		$values["service"]       = MySQL::SQLValue($services);
		$values["etat_line"]     = MySQL::SQLValue(0);
		$values["notif"]         = MySQL::SQLValue(0);
		$values["message_class"] = MySQL::SQLValue($message_class);
		$values["etat_desc"]     = MySQL::SQLValue($etat_desc);
		$values["message_etat"]  = MySQL::SQLValue($message);
		$values["class"]         = MySQL::SQLValue($app_id);//used to edit from edit task
		
		
		//check if package required stop Insert
		//$this->check_file('pkg', 'Le Package de module.');

		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow("task_action", $values))
			{
				//$db->Kill();
				$this->log .= $db->Error().' '.$db->BuildSQLinsert("task_action", $values);
				$this->error = false;
				$this->log .='</br>Enregistrement Task Actions BD non réussie'; 

			}else{
				$this->error = true;
				$this->log = '</br>Enregistrement réussie: <b>';

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
	 * [edit_default_task_action Edit default Task action for edited task]
	 * @param [int] $app_id [id of task]
	 * @param [string] $description [Description of task action]
	 * @return [fit error]           [fit Error variable]
	 */

	public function edit_default_task_action($app_id, $description, $message_class, $etat_desc)
	{


		
       //Befor execute do the multiple check
		$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
		if($this->_data['services'] !=Null){
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);
			$values["service"]   = MySQL::SQLValue($services);
		}
        $idf = MD5($description.'0def');
        $this->check_exist_idf($idf); 
		//$service               = '-'.session::get('service').'-';
		$values["appid"]         = MySQL::SQLValue($app_id);
		$values["app"]           = MySQL::SQLValue($this->_data['app']);
		$values["idf"]           = MySQL::SQLValue($idf);
		$values["descrip"]       = MySQL::SQLValue($description);
		$values["type"]          = MySQL::SQLValue(1);
		$values["etat_line"]     = MySQL::SQLValue(0);
		$values["notif"]         = MySQL::SQLValue(0);
		$values["etat_desc"]     = MySQL::SQLValue($etat_desc);
		$values["message_class"] = MySQL::SQLValue($message_class);
		$values["message_etat"]  = MySQL::SQLValue($etat_desc);
		$values["class"]         = MySQL::SQLValue($app_id);
		$wheres['class']         = MySQL::SQLValue($app_id);
		
		
		
		global $db;
        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("task_action", $values, $wheres))
			{
				//$db->Kill();
				$this->log .= $db->Error().' '.$db->BuildSQLUpdate("task_action", $values, $wheres);
				$this->error = false;
				$this->log .='</br>Enregistrement Task Actions BD non réussie'; 

			}else{
				$this->error == true;
				$this->log = '</br>Enregistrement TA réussie ';

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







	public function delete_modul()
	{
		global $db;
		$id_modul = $this->modul_id;
		$where['id'] = MySQL::SQLValue($id_modul);
		if(!$db->DeleteRows('modul',$where))
		{
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

		}else{
			$this->error = true;
			exit('deleted');
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
     * @return Bool true or false
     */
    public function delete_task()
    {
    	global $db;
    	$id_task = $this->id_task;
    	$this->get_task();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_task);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>Le id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('task',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('task',$where);
    		$this->error = false;
    		$this->log .='</br>Suppression non réussie';

    	}else{
    		//remove files
    		$modul_rep = $this->task_info['rep'];
    		$task_name = $this->task_info['app'];
    		$modul_path = MPATH_MODULES.$modul_rep;

    		$file_c = $modul_path.'/controller/'.$task_name.'_c.php';
    		$file_list_c = $modul_path.'/controller/list'.$task_name.'_c.php';
    		$file_action_c = $modul_path.'/controller/action'.$task_name.'_c.php';
    		$file_m = $modul_path.'/model/'.$task_name.'_m.php';
    		$file_v = $modul_path.'/view/'.$task_name.'_v.php';
    		if(file_exists($file_c)) unlink($file_c);
    		if(file_exists($file_list_c)) unlink($file_list_c);
    		if(file_exists($file_action_c)) unlink($file_action_c);
    		if(file_exists($file_m)) unlink($file_m);
    		if(file_exists($file_v)) unlink($file_v);

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
 * ZONE TASK_ACTION FOR MODULE
 * Function get Task_action
 * Function Add Task_action
 * Function Edit Task_action
 * Function delete Task_action
 * Function get task_action_action_liste
 */
    var $id_task_action; // Task_action ID append when request
    var $task_action_info = Array(); //Array for all info taskaction


    //Get all info task_action from database for edit form

    public function get_task_action()
    {
    	global $db;

    	$sql = "SELECT task_action.*
    	FROM task_action
    	WHERE  task_action.id = ".$this->id_task_action;

    	if(!$db->Query($sql))
    	{
    		$this->error = false;
    		$this->log  .= $db->Error();
    	}else{
    		if ($db->RowCount() == 0) {
    			$this->error = false;
    			$this->log .= 'Aucun enregistrement trouvé ';
    		} else {
    			$this->task_action_info = $db->RowArray();
    			$this->error = true;
    		}


    	}
		//return Array user_info
    	if($this->error == false)
    	{
    		return false;
    	}else{
    		return true;
    	}

    }
    Private function update_message_task_action($app, $etat_line, $message, $etat_desc, $msg_class, $notif)
    {
    	//Get array old services
    	/*$old_service      = str_replace('[-','', $old_service);
    	$old_service      = str_replace('-]','', $old_service);
    	$old_service      = str_replace('-',',', $old_service);
    	$arr_old_services = explode(',', $old_service);
        //Get array new services
    	$new_service      = str_replace('[-','', $new_service);
    	$new_service      = str_replace('-]','', $new_service);
    	$new_service      = str_replace('-',',', $new_service);
    	$arr_new_services = explode(',', $new_service); 

    	var_dump($arr_new_services);
    	var_dump($arr_old_services);*/

    	global $db;

    	$values["etat_desc"]     = MySQL::SQLValue($etat_desc);
    	$values["message_class"] = MySQL::SQLValue($msg_class);
    	$values["message_etat"]  = MySQL::SQLValue($message);
    	//$values["notif"]         = MySQL::SQLValue($notif);
    	$wheres["appid"]         = MySQL::SQLValue($app); 
    	$wheres["etat_line"]     = MySQL::SQLValue($etat_line);
    	$wheres["type"]          = MySQL::SQLValue(0);

        
    	if (!$result             = $db->UpdateRows("task_action", $values, $wheres))
    	{
				//$db->Kill();
    		$this->log .= $db->Error().' '.$db->BuildSQLUpdate("task_action", $values, $wheres);
    		$this->error = false;
    		$this->log .='</br>Update all Task action non réussie'; 

    	}else{
    		$this->error == true;
    		$this->log = '</br>MAJ all Task action réussie: <b>';

    	}

    }
    Private function check_exist_idf($idf, $is_edit = false)
    {   
    	global $db;
    	$sql_req = "SELECT COUNT(id) FROM task_action WHERE idf = '$idf'";
    	$alpha = !$is_edit ? 0 : 1;
    	if($db->QuerySingleValue0($sql_req) > $alpha){
    		$this->error = false;
    		$this->log .= '</br>La descripttion existe déjà dans la table Task Action';
    		$this->log .= '<br/>Chercher le IDF : '.$idf;
    	}
    }

    /**
     * [add_task_action Add Task Action (Autorisation_Lien_WF)]
     */
    public function add_task_action()
    {
    	
        


    	$services = json_encode($this->_data['services']);
    	$services = str_replace('"', '-', $services);
    	$services = str_replace('-,-', '-', $services);

    	$code    = '<li><a href="#" class="'.$this->_data['mode_exec'].'" data="%id%" rel="'.$this->_data['app'].'"  ><i class="ace-icon fa fa-'.$this->_data['class'].' bigger-100"></i> '.$this->_data['description'].'</a></li>';
    	$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
    	$idf     = MD5($this->_data['description'].$this->_data['etat_line'].$services);
        $this->check_exist_idf($idf);
    	global $db;
    	$values["appid"]         = MySQL::SQLValue($this->_data['id_task']);
    	$values["descrip"]       = MySQL::SQLValue($this->_data['description']);
    	$values["type"]          = MySQL::SQLValue(0);
    	$values["service"]       = MySQL::SQLValue($services);
    	$values["mode_exec"]     = MySQL::SQLValue($this->_data['mode_exec']);
    	$values["app"]           = MySQL::SQLValue($this->_data['app']);
    	$values["idf"]           = MySQL::SQLValue($idf);
    	$values["class"]         = MySQL::SQLValue($this->_data['class']);
    	$values["code"]          = MySQL::SQLValue($code);
    	$values["etat_line"]     = MySQL::SQLValue($this->_data['etat_line']);
    	$values["etat_desc"]     = MySQL::SQLValue($this->_data['etat_desc']);
    	$values["message_class"] = MySQL::SQLValue($this->_data['message_class']);
    	$values["message_etat"]  = MySQL::SQLValue($message);
    	$values["notif"]         = MySQL::SQLValue($this->_data['notif']);


        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("task_action", $values))
    		{
				//$db->Kill();
    			$this->log .= $db->Error().' '.$db->BuildSQLinsert("task_action", $values);
    			$this->error = false;
    			$this->log .='</br>Enregistrement Task Actions BD non réussie'; 

    		}else{
    			$this->error == true;
    			$this->log = '</br>Enregistrement réussie: <b>';
    			$this->update_message_task_action($this->_data['id_task'], $this->_data['etat_line'], $message, $this->_data['etat_desc'], $this->_data['message_class'], $this->_data['notif']);

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
     * [edit_task_action Edit Task Action]
     * @return [bol] [Bol send to controller]
     */
    public function edit_task_action()
    {
    	
    	$this->get_task_action();
    	if($this->_data['services'] != null){

    		$services = json_encode($this->_data['services']);
    		$services = str_replace('"', '-', $services);
    		$services = str_replace('-,-', '-', $services);

    	}else{
    		$services = $this->task_action_info['service'];

    	}


    	$code    = '<li><a href="#" class="'.$this->_data['mode_exec'].'" data="%id%" rel="'.$this->_data['app'].'"  ><i class="ace-icon fa fa-'.$this->_data['class'].' bigger-100"></i> '.$this->_data['description'].'</a></li>';
    	$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
    	$idf     = MD5($this->_data['description'].$this->_data['etat_line'].$services);
        $this->check_exist_idf($idf, true);
    	global $db;
    	$values["appid"]         = MySQL::SQLValue($this->_data['id_task']);
    	$values["descrip"]       = MySQL::SQLValue($this->_data['description']);
    	$values["type"]          = MySQL::SQLValue(0);
    	$values["service"]       = MySQL::SQLValue($services);
    	$values["mode_exec"]     = MySQL::SQLValue($this->_data['mode_exec']);
    	$values["app"]           = MySQL::SQLValue($this->_data['app']);
    	$values["idf"]           = MySQL::SQLValue($idf);
    	$values["class"]         = MySQL::SQLValue($this->_data['class']);
    	$values["code"]          = MySQL::SQLValue($code);
    	$values["etat_line"]     = MySQL::SQLValue($this->_data['etat_line']);
    	$values["etat_desc"]     = MySQL::SQLValue($this->_data['etat_desc']);
    	$values["message_class"] = MySQL::SQLValue($this->_data['message_class']);
    	$values["message_etat"]  = MySQL::SQLValue($message);
    	$values["notif"]         = MySQL::SQLValue($this->_data['notif']);
    	$wheres["id"]            = MySQL::SQLValue($this->id_task_action);


        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->UpdateRows("task_action", $values, $wheres))
    		{
				//$db->Kill();
    			$this->log .= $db->Error().' '.$db->BuildSQLUpdate("task_action", $values);
    			$this->error = false;
    			$this->log .='</br>Modification Task Actions BD non réussie'; 

    		}else{
    			$this->error == true;
    			$this->log = '</br>Modification réussie: <b>';
                $this->update_message_task_action($this->_data['id_task'], $this->_data['etat_line'], $message, $this->_data['etat_desc'], $this->_data['message_class'], $this->_data['notif']);
    		}


    	}else{

    		$this->log .='</br>Modification non réussie';

    	}
		//check if last error is true then return true else rturn false.
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}


    }
    /**
     * [add_rule_wf Add Task_action for WF (view line)]
     */
    public function add_rule_wf()
    {

    	$services = json_encode($this->_data['services']);
    	$services = str_replace('"', '-', $services);
    	$services = str_replace('-,-', '-', $services);
    	$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
    	global $db;
    	$values["appid"]         = MySQL::SQLValue($this->_data['id_task']);
    	$values["descrip"]       = MySQL::SQLValue($this->_data['description']);
    	$values["type"]          = MySQL::SQLValue(1);
    	$values["service"]       = MySQL::SQLValue($services);
    	$values["etat_line"]     = MySQL::SQLValue($this->_data['etat_line']);
    	$values["idf"]           = MySQL::SQLValue(MD5($this->_data['description'].$this->_data['etat_line']));
    	$values["etat_desc"]     = MySQL::SQLValue($this->_data['etat_desc']);
    	$values["message_class"] = MySQL::SQLValue($this->_data['message_class']);
    	$values["message_etat"]  = MySQL::SQLValue($message);



        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("task_action", $values))
    		{
				//$db->Kill();
    			$this->log .= $db->Error().' '.$db->BuildSQLinsert("task_action", $values);
    			$this->error = false;
    			$this->log .='</br>Enregistrement Autorisation WF BD non réussie'; 
    			$this->update_message_task_action($this->_data['id_task'], $this->_data['etat_line'], $message, $this->_data['etat_desc'], $this->_data['message_class'], 0);

    		}else{
    			$this->error == true;
    			$this->log = '</br>Enregistrement réussie: <b>';

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

    public function delete_task_action()
    {
    	global $db;
    	$id_task_action = $this->id_task_action;
    	$this->get_task_action();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_task_action);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>Le id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('task_action',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('task_action',$where);
    		$this->error = false;
    		$this->log .='</br>Suppression non réussie';

    	}else{
    		//remove files
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
     * [get_table_fields generate lines for new files]
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function get_table_fields($table)
    {
    	$table = $table;
    	$sql = "SHOW FULL COLUMNS FROM $table";
    	global $db;
    	$arr_fields = array();
    	if(!$db->Query($sql))
    	{
    		return false;
    	}else{
    		$arr_fields = $db->RecordsSimplArray(MYSQL_BOTH);		

    	}
    	
    	$line_action = "'fields'         => Mreq::tp('fields') ,";
    	$line_action_check = "\t".'if($posted_data["fields"] == NULL){
                                    $empty_list .= "<li>title</li>";
                                    $checker = 1;
                              }';

        $line_form_add = '//title ==> '.PHP_EOL."\t".'$array_fields[]= array("required", "true", "Insérer title ...");'.PHP_EOL."\t".'$form->input("title", "fields", "text" ,"class", null, $array_fields, null, $readonly = null);';

        $line_form_edit = '//title ==> '.PHP_EOL."\t".'$array_fields[]= array("required", "true", "Insérer title ...");'.PHP_EOL.
        "\t".'$form->input("title", "fields", "text" ,"class", $info_%modul%->g("fields"), $array_fields , null, $readonly = null);';


    	$line_modul  = '$values["fields"]       = MySQL::SQLValue($this->_data["fields"]);';

    	$line_select = "\tarray(
                            'column' => '$table.fields',
                            'type'   => 'field_type',
                            'alias'  => 'fields',
                            'width'  => '15',
                            'header' => 'title',
                            'align'  => 'field_align'
                        ),";

        $line_profil = "\t".'<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">title</div>
 									<div class="profile-info-value">
 										<span><?php  $%modul%->s("fields")  ?></span>
 									</div>
 								</div>
 							</div>';


    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value[0], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$field_type = null;
    			if(strpos($value['Field'], 'int'))
    			{
    				$field_type = 'int';
    				$align = 'R';
    			}elseif(strpos($value['Field'], 'date')){
    				$field_type = 'date';
    				$align = 'C';
    			}else{
    				$field_type = '';
    				$align = 'L';
    			}
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $line = str_replace('fields', $value['Field'], $line_select);
                $line = str_replace('title', $header, $line);
                $line = str_replace('field_type', $field_type, $line);
                $line = str_replace('field_align', $align, $line);
               	$this->lines_select .= $line.PHP_EOL;
    		}
    		
    	}

    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $action = str_replace('fields', $value['Field'], $line_action);
                $action_c = str_replace('fields', $value['Field'], $line_action_check);
                $action_c = str_replace('title', $header, $action_c);
                
               	$this->lines_action .= $action.PHP_EOL;
                $this->lines_action_check .= $action_c.PHP_EOL;
    		}
    		
    	}

    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $profil = str_replace('fields', $value['Field'], $line_profil);
                $profil = str_replace('title', $header, $profil);
                
               	$this->lines_profil .= $profil.PHP_EOL;
                
    		}
    		
    	}

    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$field_type = null;
    			if(strpos($value['Field'], 'int'))
    			{
    				$field_type = 'int';
    				$class = '4 is-number';
    			}elseif(strpos($value['Field'], 'date')){
    				$field_type = 'date';
    				$class = '4';
    			}else{
    				$field_type = '';
    				$class = '9';
    			}
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $form_add = str_replace('fields', $value['Field'], $line_form_add);
                $form_add = str_replace('title', $header, $form_add);
                $form_add = str_replace('class', $class, $form_add);
                $form_edit = str_replace('fields', $value['Field'], $line_form_edit);
                $form_edit = str_replace('title', $header, $form_edit);
                $form_edit = str_replace('class', $class, $form_edit);
               	$this->lines_form_add.= $form_add.PHP_EOL;
               	$this->lines_form_edit.= $form_edit.PHP_EOL;
                
    		}
    		
    	}
    	
    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$this->lines_modul .= str_replace('fields', $value['Field'], $line_modul).PHP_EOL;
    		}
    	}
    	
    }
    /**
     * [show_work_flow description]
     * @param  [type] $task [description]
     * @return [type]       [description]
     */
    public function show_work_flow($task)
    {
    	
    	global $db;
    	$sql = "SELECT 
    	task_action.code,
    	task_action.id,
    	task_action.etat_line,
    	task_action.etat_desc,
    	task_action.message_class,
    	task_action.descrip,
    	task_action.notif,
    	modul.services,
    	task_action.service AS service_task_action
    	FROM
    	task_action,
    	task,
    	modul
    	WHERE task.app = '$task' 
    	AND task.id = task_action.appid 
    	AND task.modul = modul.id
    	AND task_action.type = 0 
    	ORDER BY  task_action.etat_line";

    	if(!$db->Query($sql)) $db->kill($db->Error());
    	if (!$db->RowCount())
    	{
            exit('0#Pas de work flow trouvé');
    	} 
    	$main_arr    = $db->RecordsArray();

    	$etat_arr    = array_column($main_arr, 'etat_line');
    	$etat__desc_arr    = array_column($main_arr, 'etat_desc');
    	$descrip_arr = array_column($main_arr, 'descrip');
    	$service_maine = $main_arr[0]['services'];
    	$service_maine = str_replace('[-','', $service_maine);
    	$service_maine = str_replace('-]','', $service_maine);
    	$service_maine = str_replace('-',',', $service_maine);
    	$arr_main_services = explode(',', $service_maine); 

    	foreach ($arr_main_services as $key => $service) {
	    //get service name

    		$sql_req = "SELECT service FROM services WHERE id = $service ";
    		$service_name = $db->QuerySingleValue0($sql_req);
    		$etat_a = array();
    		$html = '<div class="col-sm-12">
    		<div class="widget-box">
    		<div class="widget-header widget-header-flat widget-header-small">
    		<h5 class="widget-title">
    		<i class="ace-icon fa fa-setting"></i>
    		'.$service_name.' - '.$service.' -
    		</h5>
    		</div>

    		<div class="widget-body">
    		<div class="widget-main">';

    		$html .= '<ul class="steps">'; 
    		foreach ($etat_arr as $keye => $etat) {
    			if(!in_array($etat, $etat_a))
    			{
    				array_push($etat_a, $etat);
    				$html .= '<li data-step="1" class="">';
    				$html .= '<span class="step">'.$etat.'</span>';
    				$html .= '<div class="alert alert-'.$main_arr[$keye]['message_class'].'"><strong>'.$main_arr[$keye]['etat_desc'].'</strong></div>';

    				foreach ($main_arr as $key => $descrip) {
    					if($etat == $descrip['etat_line'] && strpos($descrip['service_task_action'],$service)){
    						$notif = $descrip['notif'] == 1 ? 'btn-danger' : 'btn-info';
    						$html .= '<span style="color:#FFFFFF; margin: 2px;" class="title '.$notif.' ">'.$descrip['descrip'].' - '.$descrip['id'].' - </span>';
    					}    


    				}

    				$html .= '</li>';
    			}

    		}

    		$html .= '</ul>';
    		$html .= '			</div><!-- /.widget-main -->
    		</div><!-- /.widget-body -->
    		</div><!-- /.widget-box -->
    		</div>
    		';
    		print $html;
    	}
    	return true;
    }

    static public function get_statut_etat_line($task, $etat_line)
    {
    	global $db;
    	$sql = "SELECT 
    	task_action.message_class, task_action.etat_desc
    	FROM
    	task_action,
    	task
    	WHERE task.app = '$task' 
    	AND task.id = task_action.appid 
    	AND task_action.type = 0 
    	AND task_action.etat_line = $etat_line ";
        if(!$db->Query($sql))
        {
             $result = null;
        }else{
        	$arr_result = $db->RowArray();
        	$result = '<div class="alert alert-'.$arr_result['message_class'].'"><strong>'.$arr_result['etat_desc'].'</strong></div>';
        }
       
        return print($result);

    }

    static public function get_etat_line($task, $etat_line)
    {
    	global $db;
    	$sql = "SELECT 
    	task_action.message_etat
    	FROM
    	task_action,
    	task
    	WHERE task.app = '$task' 
    	AND task.id = task_action.appid 
    	AND task_action.type = 0 
    	AND task_action.etat_line = $etat_line ";
        if(!$db->Query($sql))
        {
             $result = null;
        }else{
        	$arr_result = $db->RowArray();
        	$result = '<strong>'.$arr_result['message_etat'].'</strong>';
        }
       
        return print($result);

    }



}

