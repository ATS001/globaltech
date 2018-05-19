<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//Controller EDIT Form
if(MInit::form_verif('editentrepots', false))
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
'libelle'         => Mreq::tp('libelle') ,
'emplacement'         => Mreq::tp('emplacement') ,
'date_creation'         => Mreq::tp('date_creation') ,


        );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    /*if($posted_data["reference"] == NULL){
                                    $empty_list .= "<li>Référence</li>";
                                    $checker = 1;
                              }*/
	if($posted_data["libelle"] == NULL){
                                    $empty_list .= "<li>Entrepot</li>";
                                    $checker = 1;
                              }
	if($posted_data["emplacement"] == NULL){
                                    $empty_list .= "<li>Emplacement Physique</li>";
                                    $checker = 1;
                              }
	if($posted_data["date_creation"] == NULL){
                                    $empty_list .= "<li>Date de création</li>";
                                    $checker = 1;
                              }



        $empty_list.= "</ul>";
        if($checker == 1)
        {
            exit("0#$empty_list");
        }



       //End check empty element
        $edit_entrepots = new  Mentrepots($posted_data);

        //Set ID of row to update
        $edit_entrepots->id_entrepots = $posted_data['id'];
        
        //execute Update returne false if error
        if($edit_entrepots->edit_entrepots()){

            exit("1#".$edit_entrepots->log);
        }else{

            exit("0#".$edit_entrepots->log);
        }


}

//No form posted show view
view::load_view('editentrepots');







    ?>