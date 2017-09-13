<?php

class Template {


  static public $template;
  var $left_menu_arr      = array();//Menu Left returned Array
  var $moduls_setting     = array(); //List modules Setting
  var $sub_modul          = array();// sub modul;
  var $modul_have_setting = array();

  static public function load()
  {
   	//Define Theme depend to session
    define('THEME_PATH',MPATH_THEMES.Mcfg::get('theme'));
    //exit(THEME_PATH);
    $ajax  = MReq::tg('ajax') == 1 ? 1 : 0; 
    if($ajax == 1){
       //Excute app on ajax
     $execute_app = new MAjax();
     $execute_app->load();  

   }else{
      //Excute app on theme
    $theme_path = THEME_PATH;
    if(session::get('userid') == FALSE){
      $theme = $theme_path.'/mainns.php';
    }else{

      $salt = MD5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].session::get('ssid'));

      if(session::get('secur_ss') != $salt)
      {
          //exit('No way! you are been detected ');
        $new_logout = new  MLogin();
        $new_logout->token = session::get('ssid');
        if($new_logout->logout())
        {
          header('location:./');
        }else{
          MInit::msg_cor($new_logout->log, $err = "", $return = "");

        }
        //exit('No way! you are been detected ');
        //We need add loginig here

      }
      $theme = $theme_path.'/main.php';
    } 
    include ($theme);
  }
}

  public function left_menu_render()
  {
      global $db;
      
      //Get user ID 
      $user = session::get('userid'); 
      //Format Query to get modul list
      $sql_modul = "SELECT  modul.modul AS modul , modul.description AS descrip ,
       modul.app_modul AS app , task.sbclass AS class
      FROM rules_action, task, modul, users_sys
      WHERE (rules_action.userid = users_sys.id) AND (rules_action.appid = task.id)
      AND  task.app = modul.app_modul AND  users_sys.id = $user 
      AND modul.is_setting = 0
      GROUP BY  modul.app_modul ORDER BY   modul.id  "; 

      if(!$db->Query($sql_modul))
      {
        $db->kill($db->Error());
        return fals;
      }else{
        $this->left_menu_arr = $db->RecordsArray();
        return true;
      } 
  }

  public function get_sub_modul($modul, $app, $descrip, $class)
  {
    global $db;
      $render_sub_modul = NULL;
      //Get user ID 
      
      //Format Query to get modul list
      $sql_sub_modul = "SELECT  modul.modul AS modul , modul.description AS descrip ,
       modul.app_modul AS app , task.sbclass AS class
      FROM rules_action, task, modul, users_sys
      WHERE (rules_action.userid = users_sys.id) AND (rules_action.appid = task.id)
      AND  task.app = modul.app_modul AND  modul.modul_setting = '$modul' AND modul.is_setting = 2
      
      GROUP BY  modul.app_modul ORDER BY   modul.id  "; 
//exit($sql_sub_modul);
      if(!$db->Query($sql_sub_modul))
      {
        $db->kill($db->Error());
        return fals;
      }else{
        if($db->RowCount()){
          $render_sub_modul .= '<ul class="submenu">';
          $sub_modul         = $db->RecordsArray(MYSQL_ASSOC);

          $render_sub_modul .= '<li>
            <a href="#" class="this_url" rel="'.$app.'" title="'.$descrip.'">
            <i class="menu-icon fa fa-'.$class.'"></i>
            '.$descrip.'
            </a>
            <b class="arrow">
            </b></li>';
          foreach ($sub_modul as $row_s)
          {
            $render_sub_modul .= '<li>
            <a href="#" class="this_url" rel="'.$row_s['app'].'" title="'.$row_s['descrip'].'">
            <i class="menu-icon fa fa-'.$row_s['class'].'"></i>
            '.$row_s['descrip'].'
            </a>
            <b class="arrow">
            </b></li>';
          }
          $render_sub_modul .= '</ul>';

        }
        
      }
      return $render_sub_modul;

  }
  
  public function list_modul_have_setting()
  {
      global $db;
      
      //Get user ID 
      $user = session::get('userid'); 
      //Format Query to get modul list
      $sql_modul = "SELECT  modul.modul AS modul , modul.description AS descrip ,
       modul.app_modul AS app , task.sbclass AS class
      FROM rules_action, task, modul, users_sys
      WHERE (rules_action.userid = users_sys.id) AND (rules_action.appid = task.id)
      AND  task.app = modul.app_modul AND  users_sys.id = $user 
      AND modul.is_setting = 0
      AND  modul.modul IN (SELECT modul.modul_setting FROM modul)
      GROUP BY  modul.app_modul ORDER BY   modul.id  "; 
      //exit($sql_modul);
      if(!$db->Query($sql_modul))
      {
        $db->kill($db->Error());
        return false;
      }else{
        if($db->RowCount()){
          $this->modul_have_setting = $db->RecordsArray();
          return true;
        }else{
          return false;
        }
        
      } 
  }

  public function list_modul_setting($modul_base)
  {
    global $db;
      
      //Get user ID 
      $user = session::get('userid'); 
      //Format Query to get modul list
      $sql_modul = "SELECT  modul.modul AS modul , modul.description AS descrip ,
       modul.app_modul AS app , task.sbclass AS class
      FROM rules_action, task, modul, users_sys
      WHERE (rules_action.userid = users_sys.id) AND (rules_action.appid = task.id)
      AND  task.app = modul.app_modul AND  users_sys.id = $user 
      AND modul.is_setting = 1
      AND  modul.modul_setting = '$modul_base'
      GROUP BY  modul.app_modul ORDER BY   modul.id  "; 

      if(!$db->Query($sql_modul))
      {
        $db->kill($db->Error());
        return false;
      }else{
        if($db->RowCount()){
          $this->moduls_setting = $db->RecordsArray();
          return true;
        }else{
          return false;
        }
        

      }# code...
  }
}


?>
