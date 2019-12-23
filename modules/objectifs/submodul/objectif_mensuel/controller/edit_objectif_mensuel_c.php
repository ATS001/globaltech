<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 05-10-2018
//Controller EDIT Form
if(MInit::form_verif('edit_objectif_mensuel', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
        'id'            =>  Mreq::tp('id') ,
        'objectif'      => Mreq::tp('objectif') ,
        'realise'       => Mreq::tp('realise') ,
        'id_commercial' => Mreq::tp('id_commercial') ,
        'mois'          => Mreq::tp('mois') ,
        'seuil'         => Mreq::tp('seuil') ,
        'annee'         => Mreq::tp('annee') ,


    );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if($posted_data["objectif"] == NULL OR !is_numeric($posted_data["objectif"])){
    $empty_list .= "<li>Objectif</li>";
    $checker = 1;
  }
  
  if($posted_data["id_commercial"] == NULL){
    $empty_list .= "<li>Commercial</li>";
    $checker = 1;
  }
  if($posted_data["mois"] == NULL){
    $empty_list .= "<li>Mois</li>";
    $checker = 1;
  }
  if($posted_data["annee"] == NULL){
    $empty_list .= "<li>Année</li>";
    $checker = 1;
  }
  if($posted_data["seuil"] == NULL){
    $empty_list .= "<li>Seuil</li>";
    $checker = 1;
  }
    

        $empty_list.= "</ul>";
        if($checker == 1)
        {
            exit("0#$empty_list");
        }



       //End check empty element
        $edit_objectifs = new  Mobjectif_mensuel($posted_data);

        //Set ID of row to update
        $edit_objectifs->id_objectif_mensuel = $posted_data['id'];
        
        //execute Update returne false if error
        if($edit_objectifs->edit_objectif_mensuel()){

            exit("1#".$edit_objectifs->log);
        }else{

            exit("0#".$edit_objectifs->log);
        }


}

//No form posted show view
view::load_view('edit_objectif_mensuel');







    ?>