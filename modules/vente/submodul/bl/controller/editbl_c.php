<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 13-05-2018
//Controller EDIT Form
if(MInit::form_verif('editbl', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
        $posted_data = array(
            'id'        => Mreq::tp('id') ,
            'line_d_d'  => MReq::tp('line_d_d'),
        );

       //var_dump('C: ');
       //var_dump(MReq::tp('line_d_d'));
        $edit_bl = new  Mbl($posted_data);

        //Set ID of row to update
        $edit_bl->id_bl = $posted_data['id'];
        
        //execute Update returne false if error
        if($edit_bl->edit_bl()){
            exit("1#".$edit_bl->log);
        }else{

            exit("0#".$edit_bl->log);
        }


}

//No form posted show view
view::load_view('editbl');







    ?>