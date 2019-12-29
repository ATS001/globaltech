<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: proforma
//Created : 17-11-2019
//Controller EDIT Form
if(MInit::form_verif('transformer_proforma', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
            'id'                => Mreq::tp('id') ,
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
        $transformer_proforma_proforma = new  Mproforma($posted_data);

        //Set ID of row to update
        $transformer_proforma_proforma->id_proforma = $posted_data['id'];
        
        //execute Update returne false if error
        if($transformer_proforma_proforma->transformer_proforma_proforma_to_devis()){

            exit("1#".$transformer_proforma_proforma->log);
        }else{

            exit("0#".$transformer_proforma_proforma->log);
        }


}

//No form posted show view
view::load_view('transformer_proforma');







    ?>