<?php
defined('_MEXEC') or die;
//Get all secteur info
$info_gsm_secteur= new Mgsm_secteur();
//Set ID of Module with POST id
$info_gsm_secteur->id_gsm_secteur = Mreq::tp('id');

if(MInit::form_verif('editgsm_secteur', false))
{
//Check if Post ID <==> Post idc or get_secteur return false. 
	if(!MInit::crypt_tp('id', null, 'D')  or !$info_gsm_secteur->get_gsm_secteur())
	{ 	
 	//returne message error red to client 
		exit('3#'.$info_gsm_secteur->log .'<br>Les informations sont erronées contactez l\'administrateur');
	}

	//Listed data from posted form
	$posted_data = array(
		'id'          				      => Mreq::tp('id') ,
		//'num_secteur'       	  => Mreq::tp('num_secteur') ,
		'hba'                  		  => Mreq::tp('hba') ,
		'azimuth'    				  => Mreq::tp('azimuth') ,
		'tilt_mecanique'          => Mreq::tp('tilt_mecanique') ,
		'tilt_electrique'            => Mreq::tp('tilt_electrique') ,


		);
	
	
	//Check if array have empty element return list
	//for acceptable empty field do not put here
	
	
	
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	/*if($posted_data['num_secteur'] == NULL){
		
		$empty_list .= "<li>Numéro du secteur</li>";
		$checker = 1;
	}*/
	if($posted_data['hba'] == NULL){
		
		$empty_list .= "<li>HBA</li>";
		$checker = 1;
	}
	if($posted_data['azimuth'] == NULL){
		
		$empty_list .= "<li>Azimuth</li>";
		$checker = 1;
	}
	if($posted_data['tilt_mecanique'] == NULL){
		
		$empty_list .= "<li>Tilt mécanique</li>";
		$checker = 1;
	}
	
	if($posted_data['tilt_electrique'] == NULL){
		
		$empty_list .= "<li>Tilt électrique</li>";
		$checker = 1;
	}
	
	
	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}
	
	
	//End check empty element

	$new_gsm_secteur = new  Mgsm_secteur($posted_data);
	
	$new_gsm_secteur->id_gsm_secteur = $posted_data['id'];
	
	
	//execute Insert returne false if error
	if($new_gsm_secteur->update_gsm_secteur()){
		
		echo("1#".$new_gsm_secteur->log);
		
	}else{
		
		echo("0#".$new_gsm_secteur->log);
	}
	
} else {
	view::load('gsm','editgsm_secteur');
}


?>