<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//Controller ADD Form
if(MInit::form_verif('reaffecttechnicien_frs', false))
{

		$posted_data = array(

			'id_fournisseur'         => Mreq::tp('id_fournisseur') ,
'date_incident'         => Mreq::tp('date_incident') ,
'nature_incident'         => Mreq::tp('nature_incident') ,
'description'         => Mreq::tp('description') ,
'prise_charge_frs'         => Mreq::tp('prise_charge_frs') ,
'prise_charge_glbt'         => Mreq::tp('prise_charge_glbt') ,
'id_technicien'         => Mreq::tp('id_technicien') ,
'date_affectation'         => Mreq::tp('date_affectation') ,
'code_cloture'         => Mreq::tp('code_cloture') ,
'observation'         => Mreq::tp('observation') ,


		);


        //Check if array have empty element return list
        //for acceptable empty field do not put here
		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

			if($posted_data["id_fournisseur"] == NULL){
                                    $empty_list .= "<li>Fournisseur</li>";
                                    $checker = 1;
                              }
	if($posted_data["date_incident"] == NULL){
                                    $empty_list .= "<li>Date incident</li>";
                                    $checker = 1;
                              }
	if($posted_data["nature_incident"] == NULL){
                                    $empty_list .= "<li>Nature incident</li>";
                                    $checker = 1;
                              }
	if($posted_data["description"] == NULL){
                                    $empty_list .= "<li>Description</li>";
                                    $checker = 1;
                              }
	if($posted_data["prise_charge_frs"] == NULL){
                                    $empty_list .= "<li>Prise en charge Fournisseur</li>";
                                    $checker = 1;
                              }
	if($posted_data["prise_charge_glbt"] == NULL){
                                    $empty_list .= "<li>Prise en charge Globaltech</li>";
                                    $checker = 1;
                              }
	if($posted_data["id_technicien"] == NULL){
                                    $empty_list .= "<li>Technicien</li>";
                                    $checker = 1;
                              }
	if($posted_data["date_affectation"] == NULL){
                                    $empty_list .= "<li>Date affectation</li>";
                                    $checker = 1;
                              }
	if($posted_data["code_cloture"] == NULL){
                                    $empty_list .= "<li>Code clôture</li>";
                                    $checker = 1;
                              }
	if($posted_data["observation"] == NULL){
                                    $empty_list .= "<li>Observation</li>";
                                    $checker = 1;
                              }



		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



       //End check empty element
		$new_ticket_frs = new  Mticket_frs($posted_data);



        //execute Insert returne false if error
		if($new_ticket_frs->save_new_ticket_frs()){

			exit("1#".$new_ticket_frs->log);
		}else{

			exit("0#".$new_ticket_frs->log);
		}


}

//No form posted show view
view::load_view('reaffecttechnicien_frs');







	?>