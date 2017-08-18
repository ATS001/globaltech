<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addgsm_secteur', false))
{
	
	$posted_data = array(
			'num_secteur'                    => Mreq::tp('num_secteur') ,
			'hba'          					 => Mreq::tp('hba') ,
			'azimuth'    	            	 => Mreq::tp('azimuth'),
			'tilt_mecanique'          	 => Mreq::tp('tilt_mecanique'),
			'tilt_electrique'          	=> Mreq::tp('tilt_electrique'),
			'id_technologie'				=>Mreq::tp('id_technologie'),
			'id_site'							=>Mreq::tp('id_site')
			);
	
	
	//Check if array have empty element return list
	//for acceptable empty field do not put here
	
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	if($posted_data['num_secteur'] == NULL){
		
		$empty_list .= "<li>Numéro de secteur</li>";
		$checker = 1;
	}
	if($posted_data['hba'] == NULL){
		
		$empty_list .= "<li>HBA</li>";
		$checker = 1;
	}
	
	 if($posted_data['azimuth'] == NULL){
		
		$empty_list .= "<li>azimuth</li>";
		$checker = 1;
	}
	
	if($posted_data['tilt_mecanique'] == NULL){
		
		$empty_list .= "<li>Tilt mécanique</li>";
		$checker = 1;
	}
	
	if($posted_data['tilt_electrique'] == NULL){
		
		$empty_list .= "<li>Tilt mécanique</li>";
		$checker = 1;
	}
	
	
	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}
	
	
	$new_gsm_secteur = new  Mgsm_secteur($posted_data);
	
	
	//execute Insert returne false if error
	if($new_gsm_secteur->save_new_gsm_secteur()){
		
		echo("1#".$new_gsm_secteur->log);
		
		
	}else{
		
		echo("0#".$new_gsm_secteur->log);
		
	}
	
} else {
	view::load('gsm','addgsm_secteur');
}

