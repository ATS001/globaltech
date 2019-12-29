<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: taux_change
//Created : 12-12-2019
//Controller EDIT Form
if(MInit::form_verif('refresh_taux_change', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
            'id'                => Mreq::tp('id') ,
            'id_devise'         => Mreq::tp('id_devise') ,
            'conversion'        => Mreq::tp('conversion') 
        );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";


	   if($posted_data["conversion"] == NULL){
                                    $empty_list .= "<li>Conversion</li>";
                                    $checker = 1;
                              }

        $empty_list.= "</ul>";
        if($checker == 1)
        {
            exit("0#$empty_list");
        }



       //End check empty element
        $edit_taux_change = new  Mtaux_change($posted_data);

        //Set ID of row to update
        $edit_taux_change->id_taux_change = $posted_data['id'];
        
        //execute Update returne false if error
        if($edit_taux_change->edit_taux_change()){

            exit("1#".$edit_taux_change->log);
        }else{

            exit("0#".$edit_taux_change->log);
        }


}

//No form posted show view
view::load_view('refresh_taux_change');
?>