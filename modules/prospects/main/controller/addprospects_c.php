<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 19-10-2019
//Controller ADD Form
if(MInit::form_verif('addprospects', false))
{

	$posted_data = array(

      'id_commercial'    => Mreq::tp('id_commercial') ,
      'raison_sociale'   => Mreq::tp('raison_sociale') ,
      'offre'            => Mreq::tp('offre') ,
      'ca_previsionnel'  => Mreq::tp('ca_previsionnel') ,
      'ponderation'      => Mreq::tp('ponderation') ,
      'ca_pondere'       => Mreq::tp('ca_pondere') ,
      'date_entree'      => Mreq::tp('date_entree') ,
      'date_cible'       => Mreq::tp('date_cible') ,
      'statut_deal'      => Mreq::tp('statut_deal') ,
      'commentaires'     => Mreq::tp('commentaires') 

	);

      //Check if array have empty element return list
      //for acceptable empty field do not put here
      $checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

	if($posted_data["id_commercial"] == NULL){
                                    $empty_list .= "<li>Commercial</li>";
                                    $checker = 1;
                              }
	if($posted_data["raison_sociale"] == NULL){
                                    $empty_list .= "<li>Raison Sociale</li>";
                                    $checker = 1;
                              }
	if($posted_data["offre"] == NULL){
                                    $empty_list .= "<li>Offre</li>";
                                    $checker = 1;
                              }

	if($posted_data["ca_previsionnel"] == NULL){
                                    $empty_list .= "<li>CA Prévisionnel</li>";
                                    $checker = 1;
                              }
	if($posted_data["ponderation"] == NULL){
                                    $empty_list .= "<li>Pondération</li>";
                                    $checker = 1;
                              }
	if($posted_data["ca_pondere"] == NULL){
                                    $empty_list .= "<li>CA Pondéré</li>";
                                    $checker = 1;
                              }
	if($posted_data["statut_deal"] == NULL){
                                    $empty_list .= "<li>Statut Deal</li>";
                                    $checker = 1;
                              }

		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



       //End check empty element
		$new_prospects = new  Mprospects($posted_data);



        //execute Insert returne false if error
		if($new_prospects->save_new_prospect()){

			exit("1#".$new_prospects->log);
		}else{

			exit("0#".$new_prospects->log);
		}


}

//No form posted show view
view::load_view('addprospects');
?>