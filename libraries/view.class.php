<?php
class view {

	public $view;
	var $output = null;
//recuperer repertoir et nom de fichier à charger 

	static public function load($view_rep,$view_file)
	{
		$view_file_include = MPATH_MODULES.$view_rep.SLASH.'view/'.$view_file.'_v.php';
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			/*exit ('<div class="space-16"></div><div class="space-16"></div><div class="alert alert-block alert-danger"><i class="ace-icon fa fa-exclamation-circle red fa-2x icon-animated-vertical"></i> <strong class="red"> STOP: </strong>Le fichier View n\'exist pas ! contacter l\'administrateur sysème<br><a href="./" class="btn btn-danger btn-sm"><i class="ace-icon fa fa-reply icon-only"> Accueil</i></a></div>');*/
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}
	static public function load_view($view_file)
	{
		$view_file_include = APP_VIEW.$view_file.'_v.php';
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}

	/**
	 * [tab Index used on view profile]
	 * @param  [string] $app     [App for ste _tsk]
	 * @param  [text] $text    [Text of Button]
	 * @param  [url setting] $add_set [Parameteres to be add to url]
	 * @param  [int] $exec    [set to 1 is we want use ]
	 * @param  [string] $icon    [icon]
	 * @return [Html]          [render html or null]
	 */
	static public function tab_render($app, $text=NULL, $add_set=NULL, $icon = NULL, $active = false, $id)
	{
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
		WHERE (users_sys.id = $userid OR $userid = 1)  

		AND task.app =  ".MySQL::SQLValue($app)." ";

		$permission = $db->QuerySingleValue0($sql);

		$active = $active == true ? 'active' : null;
		$icon_class = $icon == NULL ? 'plus' : $icon;
		$tab_index = $permission == "0" ? NULL : $tab_index = '<li class="'.$active.'">
						<a data-toggle="tab" href="#'.$id.'">
							<i class="green ace-icon fa fa-'.$icon.' bigger-120"></i>
							'.$text.'
						</a>
					</li>';
        $tab_content_s = $permission == "0" ? NULL :'<div id="'.$id.'" class="tab-pane in '.$active.'">
						<div class="col-xs-12 col-sm-4"></div>
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<!-- #section:pages/invoice -->';
								

		$tab_content_e = $permission == "0" ? NULL :'<!-- /section:pages/invoice -->
							</div>
						</div><!-- /.row -->						
					</div>';
        $tab_true = $permission == "0" ? false : true;
		
        $output = array('tab_index' => $tab_index, 'tcs' => $tab_content_s, 'tce' => $tab_content_e, 'tb_rl' => $tab_true);
		return $output ;

	}

	static public function view_get_content($view_file)
	{
		$view_file_include = APP_VIEW.$view_file.'_v.php';
		ob_start();
		if(file_exists($view_file_include))
		{
			include($view_file_include);
			$contents = ob_get_contents();
		}else{
			$contents = ('Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
		       
        ob_end_clean();
        return $contents;


	}


}


?>
