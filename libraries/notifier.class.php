<?php

/**
* Notifictor 1.0
*/
class MNotifier
{
	var $notif_array = array();
	var $sum_notif   = Null;
	var $list_notif  = Null;
	
	function __construct()
	{
		
	}


    /**
     * [count_notif description]
     * @param  [type] $table [description]
     * @param  [type] $app   [description]
     * @return [type]        [description]
     */
    private function count_notif($app, $table)
    {
    	global $db;
    	$user = session::get('userid');
    	$sql =	" 
    	    SELECT 
    	    COUNT($table.id) AS count_notif,
    	    task.dscrip,
    	    task.app AS app,
    	    task.sbclass 
    	    FROM task, $table
    	    WHERE 
    	    $table.etat IN (SELECT task_action.etat_line
    	    FROM task_action, rules_action, task
    	    WHERE 
    	    task_action.appid = task.id 
    	    AND task_action.notif = 1 
    	    AND task.app = '$app' 
    	    AND task_action.id = rules_action.action_id 
    	    AND rules_action.userid = $user
            )AND task.app = '$app' LIMIT 0,1 ";
    	

        if(!$db->Query($sql))
        {
        	return false;
        }else{
        	$notification_array = $db->RecordsArray();
        }    
        return array_values($notification_array);
    }

    public function all_notification()
    {
    	global $db;
    	$app_arr   = array();
    	$count_arr = array();
    	$req_sql   = " SELECT * FROM sys_notifier";
    	if(!$db->Query($req_sql)){
    		die($db->Error());
    	}else{
    		$app_arr = $db->RecordsArray();
    	}

    	foreach ($app_arr as $key => $column) {
    		$app   = $column['app'];
    		$table = $column['table'];
            //Get notification 
            if($count_arr = $this->count_notif($app, $table) )
            {
            	//var_dump($count_arr);
              //  exit();
            	array_push($this->notif_array, $count_arr['0']);

            }


    	}
       return true;
    }

    public function sum_notif()
    {
    	$this->sum_notif = 0;
        if(empty($this->notif_array))
        {
        	$this->all_notification();
        } 

    	foreach ($this->notif_array as $key => $arr_notif) {
    		$this->sum_notif += $arr_notif['count_notif'];
    	}
    	
    	return $this->sum_notif;
    	
    }

    public function notif_list()
    {
    	if(empty($this->notif_array))
        {
        	$this->all_notification();
        }
        $this->sum_notif();
    	$arr_notif = $this->notif_array;
    	foreach ($arr_notif as $key => $list) {
      	    $li ='<li>
					<a href="#" class="this_url" rel="'.$list['app'].'">
						<div class="clearfix">
							<span class="pull-left">
								
								<i class="btn  btn-xs no-hover fa fa-'.$list['sbclass'].'"></i>
								'.$list['dscrip'].'
							</span>
							<span id="notify_'.$list['app'].'" class="pull-right badge btn-notif">'.$list['count_notif'].'</span>
						</div>
					</a>
				</li>';
            if($list['count_notif'] > 0)    
    	    $this->list_notif .= $li;
    	}
        
        $js_arr_notif = json_encode($arr_notif);
    	$arr_result = array('sum' => $this->sum_notif, 'list' => $this->list_notif, 'arr'=> $js_arr_notif);
    	//return $this->sum_notif.'[#]'.$this->list_notif;
        return json_encode($arr_result); 

    }

}
