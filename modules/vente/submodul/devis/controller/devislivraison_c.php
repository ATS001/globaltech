<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 14-05-2018
//Controller EDIT Form
if(MInit::form_verif('devislivraison', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
            'id'                => Mreq::tp('id') ,
            'line_d_d'=> MReq::tp('line_d_d'),
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
        $devislivraison_devis = new  Mdevis($posted_data);

        //Set ID of row to update
        $devislivraison_devis->id_devis = $posted_data['id'];
        
        //execute Update returne false if error
        if($devislivraison_devis->devislivraison_devis()){

            exit("1#".$devislivraison_devis->log);
        }else{

            exit("0#".$devislivraison_devis->log);
        }


}

//No form posted show view
view::load_view('devislivraison');







    ?>