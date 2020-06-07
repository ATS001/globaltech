<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_commercial
//Created : 01-11-2018
//Controller ADD Form
if(MInit::form_verif('addobjectifgroupe', false))
{

  $posted_data = array(

    'description'   => Mreq::tp('description') ,
    'id_commercial' => Mreq::tp('id_commercial') ,
    'annee'         => Mreq::tp('annee') ,

  );


                  //Check if array have empty element return list
                  //for acceptable empty field do not put here
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

 if($posted_data["description"] == NULL){
    $empty_list .= "<li>Description</li>";
    $checker = 1;
  }

  if($posted_data["id_commercial"] == NULL){
    $empty_list .= "<li>Commercial</li>";
    $checker = 1;
  }

  if($posted_data["annee"] == NULL){
    $empty_list .= "<li>Année</li>";
    $checker = 1;
  }

  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

                  //End check empty element
  $new_objectif_groupe = new  Mobjectif_mensuel_groupe($posted_data);
  //execute Insert returne false if error
  if($new_objectif_groupe->save_new_objectif_mensuel_groupe()){

    exit("1#".$new_objectif_groupe->log);
  }else{

    exit("0#".$new_objectif_groupe->log);
  }

}

//No form posted show view
view::load_view('addobjectifgroupe');

  ?>
