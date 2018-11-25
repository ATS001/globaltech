<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 05-10-2018
//Controller EDIT Form
if(MInit::form_verif('edit_objectif_service', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
        'id'          =>  Mreq::tp('id') ,
        'description' =>  Mreq::tp('description') ,
        'id_service'  =>  Mreq::tp('id_service') ,
        'date_s'      =>  Mreq::tp('date_s') ,
        'date_e'      =>  Mreq::tp('date_e') ,
        'objectif'    =>  Mreq::tp('objectif') ,


    );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if($posted_data["description"] == NULL){
        $empty_list .= "<li>Description</li>";
        $checker = 1;
    }
    if($posted_data["id_service"] == NULL){
        $empty_list .= "<li>service</li>";
        $checker = 1;
    }
    if($posted_data["date_s"] == NULL){
        $empty_list .= "<li>Date Début</li>";
        $checker = 1;
    }
    if($posted_data["date_e"] == NULL){
        $empty_list .= "<li>Date Fin</li>";
        $checker = 1;
    }
    if(strtotime($posted_data["date_e"]) <= strtotime($posted_data["date_s"])){
        $empty_list .= "<li>Date Fin incorrect</li>";
        $checker = 1;
    }
    if($posted_data["objectif"] == NULL OR !is_numeric($posted_data["objectif"])){
        $empty_list .= "<li>Objectif</li>";
        $checker = 1;
    }
    

        $empty_list.= "</ul>";
        if($checker == 1)
        {
            exit("0#$empty_list");
        }



       //End check empty element
        $edit_objectifs = new  Mobjectif_service($posted_data);

        //Set ID of row to update
        $edit_objectifs->id_objectif_service = $posted_data['id'];
        
        //execute Update returne false if error
        if($edit_objectifs->edit_objectif_service()){

            exit("1#".$edit_objectifs->log);
        }else{

            exit("0#".$edit_objectifs->log);
        }


}

//No form posted show view
view::load_view('edit_objectif_service');







    ?>