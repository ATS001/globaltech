<?php 
//SYS MRN ERP
// Modul: gsm_technologie => Controller
defined('_MEXEC') or die;
//Get all Installateur info
 $info_technologie= new Mgsm_technologie();
//Set ID of Module with POST id
 $info_technologie->id_technologie = Mreq::tp('id');

if(MInit::form_verif('editgsm_technologie', false))
{
	//Check if id is been the correct id compared with idc
	if(!MInit::crypt_tp('id', null, 'D') or !$info_technologie->get_technologie() )
	{  
  //returne message error red to client 
		exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
  //Listed data from posted form
	$posted_data = array(
		'id'             => Mreq::tp('id') ,
		'id_site_gsm'    => Mreq::tp('id_site_gsm') ,
		//'technologie'    => Mreq::tp('technologie') ,
		'marque_bts'     => Mreq::tp('marque_bts') ,
		'num_serie'      => Mreq::tp('num_serie') ,
		'modele_antenne' => Mreq::tp('modele_antenne') ,
		'nbr_radio'      => Mreq::tp('nbr_radio') ,
		'nbr_secteur'    => Mreq::tp('nbr_secteur') 


		);


  //Check if array have empty element return list
  //for acceptable empty field do not put here
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	/*if($posted_data['technologie'] == NULL){

		$empty_list .= "<li>Technologie</li>";
		$checker = 1;
	}*/
	if($posted_data['marque_bts'] == NULL){

		$empty_list .= "<li>Marque BTS</li>";
		$checker = 1;
	}
	if($posted_data['num_serie'] == NULL){

		$empty_list .= "<li>Numéro de série</li>";
		$checker = 1;
	}
	if($posted_data['modele_antenne'] == NULL){

		$empty_list .= "<li>Modele antenne</li>";
		$checker = 1;
	}
	if($posted_data['nbr_radio'] == NULL and  !is_numeric($posted_data['nbr_radio']) ){

		$empty_list .= "<li>Nombre radios</li>";
		$checker = 1;
	}



	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}

  //End check empty element


	$new_gsm_technologie = new  Mgsm_technologie($posted_data);

	$new_gsm_technologie->id_technologie = $posted_data['id'];

  //execute Insert returne false if error
	if($new_gsm_technologie->edit_gsm_technologie())
	{
    exit("1#".$new_gsm_technologie->log);//Green message
	} 
	else
	{
    exit("0#".$new_gsm_technologie->log);//Red Message
	}

} 
else 
{
  //call form if no post
	view::load('gsm','editgsm_technologie');
}

?>