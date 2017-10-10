<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 09-10-2017
//Controller EDIT Form
if(MInit::form_verif('validdevisclient', false))
    {
        if(!MInit::crypt_tp('id', null, 'D'))
            {  
    // returne message error red to client 
                exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
            }
            $posted_data = array(
                'id'      => Mreq::tp('id') ,
                'reponse' => Mreq::tp('reponse') ,
                'modcom'  => Mreq::tp('modcom') ,
                'scan_id' => Mreq::tp('scan-id') ,


            );



        //Check if array have empty element return list
            if($posted_data['reponse'] == NULL OR !in_array($posted_data['reponse'] , array('valid', 'modif', 'refus'))){

                $empty_list .= "<li>Nom</li>";
                $checker = 1;
            }
            if($posted_data['modcom'] == NULL OR !in_array($posted_data['modcom'] , array('ar', 'email', 'tel'))){

                $empty_list .= "<li>Nom</li>";
                $checker = 1;
            }

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
            $validdevisclient_devis = new  Mdevis($posted_data);

        //Set ID of row to update
            $validdevisclient_devis->id_devis = $posted_data['id'];

        //execute Update returne false if error
            if($validdevisclient_devis->validdevisclient_devis()){

                exit("1#".$validdevisclient_devis->log);
            }else{

                exit("0#".$validdevisclient_devis->log);
            }


        }

//No form posted show view
        view::load_view('validdevisclient');







        ?>