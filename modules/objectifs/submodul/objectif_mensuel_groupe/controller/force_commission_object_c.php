<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel
//Created : 28-12-2019
//Controller EDIT Form
if(MInit::form_verif('force_commission_object', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
            'id'            => Mreq::tp('id') ,
            'montant_benif' => Mreq::tp('montant_benif') ,
            //Add posted data fields  here
            

        );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

        //Add posted data fields verificator here


        $empty_list.= "</ul>";
        if($checker == 1)
        {
            exit("0#$empty_list");
        }



       //End check empty element
        $objectif_mensuel = new  Mobjectif_mensuel($posted_data);

        //Set ID of row to update
        $objectif_mensuel->id_objectif_mensuel = $posted_data['id'];
        
        //execute Update returne false if error
        if($objectif_mensuel->force_commission_objectif_mensuel()){

            exit("1#".$objectif_mensuel->log);
        }else{

            exit("0#".$objectif_mensuel->log);
        }


}

//No form posted show view
view::load_view('force_commission_object');







    ?>