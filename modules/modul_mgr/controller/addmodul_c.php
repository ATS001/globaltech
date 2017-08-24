<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addmodul', false))
{
  
  $posted_data = array(
   'modul'         => Mreq::tp('modul') ,
   //'rep_modul'   => Mreq::tp('rep_modul') ,
   'is_setting'    => 0 ,
   'modul_setting' => NULL ,
   'description'   => Mreq::tp('description') ,
   'tables'        => Mreq::tp('tables') ,
   'app'           => Mreq::tp('app') ,
   'sbclass'       => Mreq::tp('sbclass') ,
   'services'      => Mreq::tp('services') ,
   'etat'          => 0 ,
   'etat_desc'     => Mreq::tp('etat_desc') ,
   'message_class' => Mreq::tp('message_class') ,
   
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['modul'] == NULL){

      $empty_list .= "<li>Nom de module</li>";
      $checker = 1;
    }
    if(!MInit::is_regex($posted_data['modul'])){

      $empty_list .= "<li>Nom de module non valid (a-z 1-9)</li>";
      $checker = 1;
    }
    /*if($posted_data['rep_modul'] == NULL){

      $empty_list .= "<li>Répertoire Module</li>";
      $checker = 1;
    }*/
    if($posted_data['description'] == NULL){

      $empty_list .= "<li>Déscription</li>";
      $checker = 1;
    }
    if($posted_data['tables'] == NULL){

      $empty_list .= "<li>Tables</li>";
      $checker = 1;
    }
    if($posted_data['app'] == NULL){

      $empty_list .= "<li>Nom de l'application de base</li>";
      $checker = 1;
    }
    if($posted_data['services'] == NULL){

      $empty_list .= "<li>Choisir un ou plusieur services</li>";
      $checker = 1;
    }
    if(!MInit::is_regex($posted_data['app'])){

      $empty_list .= "<li>Nom de l'application non valid (a-z 1-9)</li>";
      $checker = 1;
    }
    if($posted_data['etat_desc'] == NULL){

      $empty_list .= "<li>Choisir Message à Afficher </li>";
      $checker = 1;
    }
    if($posted_data['message_class'] == NULL){

      $empty_list .= "<li>Choisir La couleur du message </li>";
      $checker = 1;
    }
    
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  //End check empty element


  $new_modul = new  Mmodul($posted_data);
  //$new_modul->exige_pkg = true;
  

  //execute Insert returne false if error
  if($new_modul->save_new_modul()){

    echo("1#".$new_modul->log);
  }else{

    echo("0#".$new_modul->log);
  }


} else {
  view::load('modul_mgr','addmodul');
}






?>