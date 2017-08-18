<?php

if(MInit::form_verif('editblr_clients', false))//If form is Posted do Action else rend empty form
{
	//Check if id is been the correct id compared with idc
  if(!MInit::crypt_tp('id', null, 'D') )
  {  
  //returne message error red to client 
    exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
  }
  //Listed data from posted form
  $posted_data = array(
    'id'           => Mreq::tp('id'),  
    'station_base' => Mreq::tp('station_base'),
    'site'         => Mreq::tp('site'),
	'longi'        => Mreq::tp('longi'),
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
	if($posted_data['frequence'] == NULL OR !is_numeric($posted_data['frequence']) ){

		$empty_list .= "<li>Fréquence</li>";
		$checker = 1;
	}
	
	if($posted_data['hauteur'] == NULL){
		
		$empty_list .= "<li>Hauteur</li>";
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
	
	
	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}
  //End Checker
  //Call Model
  $new_blr_clients = new  Mblr_clients($posted_data);
  $new_blr_clients->exige_pj = true;
  $new_blr_clients->id_blr_clients = $posted_data['id'];
  //execute Edit returne false if error
  if($new_blr_clients->edit_blr_clients())
  {
    exit("1#".$new_blr_clients->log);//Green message
  }else{
    exit("0#".$new_blr_clients->log);//Red message
  }
//Call View if no POST
}else{
  view::load('blr_stations','editblr_clients');
}


?><?php 
