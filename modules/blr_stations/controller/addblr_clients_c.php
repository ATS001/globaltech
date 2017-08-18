<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addblr_clients', false))
{
	
	$posted_data = array(
		'station_base' => Mreq::tp('station_base') ,
		'site'         => Mreq::tp('site') ,
		'longi'        => Mreq::tp('longi') ,
		'latit'        => Mreq::tp('latit'),
		'hauteur'      => Mreq::tp('hauteur'),
		'frequence'    => Mreq::tp('frequence') ,
		'marque'       => Mreq::tp('marque'),
		'modele'       => Mreq::tp('modele'),
		'remarq'       => Mreq::tp('remarq'),
		'pj_id'        => Mreq::tp('pj-id') ,
	    'photo_id'     => Mreq::tp('photo_id'),//Array
   		'photo_titl'   => Mreq::tp('photo_titl'),//Array
   			);
	
	
	//Check if array have empty element return list
	//for acceptable empty field do not put here
	
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	
	if($posted_data['site'] == NULL){
		
		$empty_list .= "<li>Nom de Site</li>";
		$checker = 1;
	}
	if($posted_data['longi'] == NULL){
		
		$empty_list .= "<li>Longitude</li>";
		$checker = 1;
	}
	
	if($posted_data['latit'] == NULL){
		
		$empty_list .= "<li>Latitude</li>";
		$checker = 1;
	}
	
	if($posted_data['hauteur'] == NULL){
		
		$empty_list .= "<li>Hauteur</li>";
		$checker = 1;
	}
	if($posted_data['frequence'] == NULL OR !is_numeric($posted_data['frequence']) ){

		$empty_list .= "<li>Fréquence</li>";
		$checker = 1;
	}
	
	if($posted_data['marque'] == NULL){
		
		$empty_list .= "<li>Marque</li>";
		$checker = 1;
	}
	if($posted_data['modele'] == NULL){
		
		$empty_list .= "<li>modele</li>";
		$checker = 1;
	}	
	if($posted_data['pj_id'] == null){
		$empty_list .='<li>Ajoutez le formulaire scanné';
		$checker = 1;
	}
	
	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}

	$new_blr_clients = new  Mblr_clients($posted_data);
	$new_blr_clients->exige_pj = true;

	//execute Insert returne false if error
	if($new_blr_clients->save_new_blr_clients()){
		
		echo("1#".$new_blr_clients->log);

	}else{
		
		echo("0#".$new_blr_clients->log);
	}
} else {

	view::load('blr_stations', 'addblr_clients');
}


