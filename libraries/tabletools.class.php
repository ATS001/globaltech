<?php

/**
* Class generate tools for a table
*/
class TableTools 
{
	var $app_action; //Array action for each row
	var $line_data; //Array info for each row
	var $log = null; //Sitring log errors
	/*function __construct(argument)
	{
		# code...
	}*/

	/**
	 * [btn_add Add Btn to an table ]
	 * @param  [string] $app     [App for ste _tsk]
	 * @param  [text] $text    [Text of Button]
	 * @param  [url setting] $add_set [Parameteres to be add to url]
	 * @param  [int] $exec    [set to 1 is we want use ]
	 * @param  [string] $icon    [icon]
	 * @return [Html]          [render html or null]
	 */
	static public function btn_add($app, $text=NULL, $add_set=NULL, $exec = NULL, $icon = NULL){
		global $db;
		$userid = session::get('userid');
		$sql = "SELECT
		1
		FROM
		`rules_action`
		INNER JOIN `task` 
		ON (`rules_action`.`appid` = `task`.`id`)
		INNER JOIN `task_action` 
		ON (`task_action`.`appid` = `task`.`id`) AND (`rules_action`.`action_id` = `task_action`.`id`)
		INNER JOIN `users_sys` 
		ON (`rules_action`.`userid` = `users_sys`.`id`)
		WHERE users_sys.id = $userid 

		AND task.app =  ".MySQL::SQLValue($app)." ";

		$permission = $db->QuerySingleValue0($sql);

		$exec_class = $exec == NULL ? 'this_url' : 'this_exec';
		$icon_class = $icon == NULL ? 'plus' : $icon;

		$output = $permission == "0"?"":'<a href="#" rel="'.$app.'&'.$add_set.'" class=" btn btn-white btn-info btn-bold '.$exec_class.' spaced"><span><i class="fa fa-'.$icon_class.'"></i> '.$text.'</span></a>';

		$render = print ($output);


		return $render ;

	}
    /**
     * [btn_back description]
     * @param  [type] $app     [description]
     * @param  [type] $text    [description]
     * @param  [type] $id      [description]
     * @param  [type] $add_set [description]
     * @return [type]          [description]
     */
	static public function btn_back($app, $text=NULL, $id, $add_set=NULL){
		global $db;
		$userid = session::get('userid');
		$sql = "SELECT
		1
		FROM
		`rules_action`
		INNER JOIN `task` 
		ON (`rules_action`.`appid` = `task`.`id`)
		INNER JOIN `task_action` 
		ON (`task_action`.`appid` = `task`.`id`) AND (`rules_action`.`action_id` = `task_action`.`id`)
		INNER JOIN `users_sys` 
		ON (`rules_action`.`userid` = `users_sys`.`id`)
		WHERE users_sys.id = $userid 

		AND task.app =  ".MySQL::SQLValue($app)." ";

		$id_format = '&id='.$id.'&idc='.md5(Minit::cryptage($id,1));

		$permission = $db->QuerySingleValue0($sql);
		$output = $permission == "0"?"":'<a href="#" rel="'.$app.$id_format.$add_set.'" class="ColVis_Button ColVis_MasterButton btn btn-red btn-info btn-bold this_url"><span><i class="fa fa-reply"></i> '.$text.'</span></a>';

		$render = print ($output);


		return $render ;

	}
    /**
     * [btn_csv description]
     * @param  [type] $app  [description]
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
	static public function btn_csv($app, $text)
	{
		$output = '<a title="Export XLS" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_csv"><span><i class="fa fa-file-excel-o fa-lg" style="color:green"></i></span></a>';


		$render = print ($output);


		return $render ;
	}


	/**
	 * [btn_pdf description]
	 * @param  [type] $app  [description]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	static public function btn_pdf($app, $text)
	{
		$output = '<a title="Export PDF" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_pdf"><span><i class="fa fa-file-pdf-o fa-lg" style="color:red"></i></span></a>';


		$render = print ($output);


		return $render ;
	}
	static public function btn_map($app, $text)
	{
		$output = '<a title="Export MAP" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_map"><span><i class="fa fa-map-o fa-lg" style="color:red"></i></span></a>';


		$render = print ($output);


		return $render ;
	}

	//Publique Function get action for user modul
    //depent of user connected
	public function action_line_table($app, $table_modul, $cre_usr = null, $task_exec = null)
	{
		//Etat line is null return false
		if($this->line_data['id'] == null)
		{
			$this->app_action .= 'Pas d\'Enregistrement trouvé';
			print($this->app_action);
			return false;
		}
		//Etat line is not 

		global $db;
		$user = session::get('userid');


		$table_from = $table_modul == 'task' ? NULL : ', '.$table_modul;
		$and_task = $table_modul == 'task' ? NULL : ' AND task.app ="'.$app.'"';


		$etat          = $this->line_data['etat'];
		$id_this_modul = APP_ID;
		$id            = $this->line_data['id'];
		$service       = session::get('service');
		$service_f     = '%-'.$service.'-%';

		$sql = "SELECT task_action.code FROM 
		task_action, task, rules_action $table_from 
		WHERE rules_action.action_id = task_action.id 
		AND $table_modul.etat = task_action.etat_line 
		$and_task 
		AND task.id = task_action.appid 
		AND rules_action.userid = $user
		AND rules_action.service = $service
		AND $table_modul.etat = $etat 
		AND $table_modul.id = $id
		AND task_action.type = 0 ";
        //for more secure add this AND task_action.service LIKE  '$service_f' 
        //exit($sql);


		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
			//return false;
		}else{
			
			if ($db->RowCount() == false) {
				$this->error = false;
				$this->app_action .= 'Pas d\'action trouvée!';
				return print($this->app_action);
			} else {
				//$this->log = $sql;
				//$this->app_action = $db->RowArray();
				
				$this->error = true;
				while (!$db->EndOfSeek())
				{
					$row = $db->Row();
					$this->app_action .= $row->code;

				}
				$stanadr_delete = null;
				if($etat == 0 AND ($cre_usr == session::get('userid') OR session::get('service') == 1))
				{
						$stanadr_delete = '<li class="divider"></li><li><a href="#" class="this_exec" data="'.MInit::crypt_tp('id',$id).'" rel="'.$task_exec.'"  ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer ligne</a></li>';
				}

					$retour =  str_replace('%id%', MInit::crypt_tp('id',$id), $this->app_action);
					return print($retour.$stanadr_delete);

			}


		}
		//return true;
	}

	//Publique Function get action for user modul
    //depent of user connected
	public function action_profil_view($app, $table_modul, $add_set, $cre_usr = null, $task_exec = null)
	{
		//Etat line is null return false
		if($this->line_data['id'] == null)
		{
			$this->app_action .= 'Pas d\'Enregistrement trouvé';
			print($this->app_action);
			return false;
		}
		//Etat line is not 

		global $db;
		$user = session::get('userid');


		$table_from = $table_modul == 'task' ? NULL : ', '.$table_modul;
		$and_task = $table_modul == 'task' ? NULL : ' AND task.app ="'.$app.'"';


		$etat          = $this->line_data['etat'];
		$id_this_modul = APP_ID;
		$id            = $this->line_data['id'];
		$service       = session::get('service');
		$service_f     = '%-'.$service.'-%';

		$sql = "SELECT task_action.app, task_action.descrip,
		 task_action.mode_exec, task_action.etat_desc ,
		 task_action.message_class, task_action.class
		  FROM 
		task_action, task, rules_action $table_from 
		WHERE rules_action.action_id = task_action.id 
		AND $table_modul.etat = task_action.etat_line 
		$and_task 
		AND task.id = task_action.appid 
		AND rules_action.userid = $user 
		AND task_action.service LIKE  '$service_f'
		AND rules_action.service = $service
		AND $table_modul.etat = $etat 
		AND $table_modul.id = $id
		AND task_action.type = 0 ";

//exit($sql);


		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
			//return false;
		}else{
			
			if ($db->RowCount() == false) {
				$this->error = false;
				$this->app_action .= 'Pas d\'action trouvée!';
				return print($this->app_action);
			} else {
				//$this->log = $sql;
				//$this->app_action = $db->RowArray();
				
				$this->error = true;
				while (!$db->EndOfSeek())
				{
					$row = $db->Row();
					$this->app_action .= '<a href="#" rel="'.$row->app.'&'.$add_set.'" class=" btn btn-white '.$row->mode_exec.' btn-info btn-bold '.$row->message_class.' spaced"><span><i class="fa fa-'.$row->class.'"></i> '.$row->descrip.'</span></a>';

				}
				$stanadr_delete = null;
				if($etat == 0 AND ($cre_usr == session::get('userid') OR session::get('service') == 1))
				{
						$stanadr_delete = '<a href="#" rel="'.$task_exec.'&'.MInit::crypt_tp('id',$id).'" class=" btn btn-white this_exec btn-info btn-bold '.$row->message_class.' spaced"><span><i class="fa fa-trash red bigger-100"></i> Supprimer ligne</span></a>';
				}

					$retour =  str_replace('%id%', MInit::crypt_tp('id',$id), $this->app_action);
					return ($retour.$stanadr_delete);

			}


		}
		//return true;
	}

    /**
     * Line notification for notify user that line have an action should be execute
     * @param  [string] $table     [man table for this liste]
     * @param  [String] $task_name [task name calling this liste]
     * @return [String] [return Sql code to concate with any culomn (input hidden or null)]
     */
    static public function line_notif($table, $task_name)
    {
    	$get_notif = "CASE 1
    	WHEN (SELECT 
    		COUNT(task_action.notif) 
    		FROM
    		task_action, rules_action , task
    		WHERE task_action.`etat_line` = `$table`.etat
    		AND task_action.appid = task.id 
    		AND task_action.`notif` = 1 
    		AND task.`app` = '$task_name'  
    		AND task_action.id = rules_action.`action_id`
    		AND rules_action.`userid` = ".session::get('userid')."  
    		AND task_action.`type` = 0) > 0
    		THEN '<input type=hidden value=isnotif>'
    		ELSE ' ' END";
    	return $get_notif;
    }
    /**
     * [line_notif_new description]
     * @param  [type] $table     [description]
     * @param  [type] $task_name [description]
     * @return [type]            [description]
     */
    static public function line_notif_new($table, $task_name)
    {

        //Check if for export result then select colonne adequat
    	if(Mreq::tp('export') == 1)
    	{
    			$message_etat = "CASE
    			WHEN SUM(task_action.`notif`) > 0 
    			THEN 
    			task_action.`etat_desc`                            
    			ELSE CONCAT(task_action.`etat_desc`,' ') 
    			END ";
    	}else{
    			$message_etat = " CASE
    			WHEN SUM(task_action.`notif`) > 0 
    			THEN CONCAT(
    				task_action.`message_etat`,
    				'<input type=hidden value=isnotif>'
    			) 
    			WHEN task_action.`message_etat` IS NULL THEN 'No ETAT'
    			ELSE CONCAT(task_action.`message_etat`) 
    			END ";
    	}
    		$get_notif = "(SELECT 
    			$message_etat 
    			FROM
    			task_action,
    			rules_action,
    			task 
    			WHERE task_action.`etat_line` = `$table`.etat 
    			AND task_action.appid = task.id 
    			AND task_action.etat_desc IS NOT NULL
    			AND task.`app` = '$task_name' 
    			AND task_action.id = rules_action.`action_id` 
    			AND rules_action.`userid` = ".session::get('userid')."  
    			AND task_action.`type` = 0) AS statut";
    			return $get_notif;
    }
    /**
     * [order_bloc description]
     * @param  [type] $order_column [description]
     * @return [type]               [description]
     */
    static public function order_bloc($order_column)
    {



    	if(Mreq::tp('export') == 1)
    	{
    		$order_notif = " CASE WHEN LOCATE('*', statut) = 0  THEN 0 ELSE 1 END DESC, ";
    	}else{
    		$order_notif = " CASE WHEN LOCATE('notif', statut) = 0  THEN 0 ELSE 1 END DESC, ";
    	}
    	return $order_notif;

    }   
    /**
     * [where_etat_line description]
     * @param  [type] $table     [description]
     * @param  [type] $task_name [description]
     * @return [type]            [description]
     */
    static public function where_etat_line($table, $task_name)
    {
    	$where_etat_line = " WHERE   (SELECT 
    		COUNT(task_action.id) 
    		FROM
    		task_action, rules_action , task
    		WHERE task_action.`etat_line` = `$table`.etat
    		AND task_action.appid = task.id 
    		AND task.`app` = '$task_name'  
    		AND task_action.id = rules_action.`action_id`
    		AND rules_action.`userid` = ".session::get('userid').") > 0 " ;
    	return $where_etat_line; 
    }
    /**
     * [where_search_etat description]
     * @param  [type] $table     [description]
     * @param  [type] $task_name [description]
     * @param  [type] $search    [description]
     * @return [type]            [description]
     */
    static public function where_search_etat($table, $task_name, $search)
    {
    	$where_search_etat = " OR (SELECT 
    		COUNT(task_action.id) 
    		FROM
    		task_action,
    		rules_action,
    		task 
    		WHERE task_action.`etat_line` = `$table`.etat 
    		AND task_action.appid = task.id 
    		AND task_action.etat_desc IS NOT NULL 
    		AND task.`app` = '$task_name' 
    		AND task_action.id = rules_action.`action_id` 
    		AND rules_action.`userid` = ".session::get('userid')." 
    		AND task_action.`type` = 0
    		AND task_action.`message_etat` LIKE '%$search%')
    	)";
    	return $where_search_etat;                        
    }




}

?>