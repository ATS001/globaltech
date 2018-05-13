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
            'id'                => Mreq::tp('id') ,
            //
            'reference'         => Mreq::tp('reference') ,
'client'         => Mreq::tp('client') ,
'projet'         => Mreq::tp('projet') ,
'ref_bc'         => Mreq::tp('ref_bc') ,
'iddevis'         => Mreq::tp('iddevis') ,
'date_bl'         => Mreq::tp('date_bl') ,


        );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

        	if($posted_data["reference"] == NULL){
                                    $empty_list .= "<li>reference</li>";
                                    $checker = 1;
                              }
	if($posted_data["client"] == NULL){
                                    $empty_list .= "<li>client</li>";
                                    $checker = 1;
                              }
	if($posted_data["projet"] == NULL){
                                    $empty_list .= "<li>designation projet</li>";
                                    $checker = 1;
                              }
	if($posted_data["ref_bc"] == NULL){
                                    $empty_list .= "<li>ref bon commande client</li>";
                                    $checker = 1;
                              }
	if($posted_data["iddevis"] == NULL){
                                    $empty_list .= "<li>Devis</li>";
                                    $checker = 1;
                              }
	if($posted_data["date_bl"] == NULL){
                                    $empty_list .= "<li>date_bl</li>";
                                    $checker = 1;
                              }



        $empty_list.= "</ul>";
        if($checker == 1)
        {
            exit("0#$empty_list");
        }



       //End check empty element
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