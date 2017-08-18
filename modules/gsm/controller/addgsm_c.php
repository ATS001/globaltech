<?php
if(MInit::form_verif('addgsm', false))
{
	

	$posted_data = array(
		'nom_station'     => Mreq::tp('nom_station') ,
		'id_perm'         => Mreq::tp('id_perm') ,
		'adresse'         => Mreq::tp('adresse') ,
		'ville'           => Mreq::tp('ville') ,
		'longi'           => Mreq::tp('longi') ,
		'latit'           => Mreq::tp('latit') ,
		'type_support'    => Mreq::tp('type_support') ,
		'shared_site'     => Mreq::tp('shared_site') ,
		'oper_share'      => Mreq::tp('oper_share') ,
		'power_generator' => Mreq::tp('power_generator') ,
		'power_company'   => Mreq::tp('power_company') ,
		'power_solar'     => Mreq::tp('power_solar') ,
		'bh_vsat'         => Mreq::tp('bh_vsat') ,
		'bh_fh'           => Mreq::tp('bh_fh') ,
		'bh_fibre'        => Mreq::tp('bh_fibre') ,
		'tech_2g'         => Mreq::tp('tech_2g') ,
		'tech_3g'         => Mreq::tp('tech_3g') ,
		'tech_4g'         => Mreq::tp('tech_4g') ,
		'tech_cdma'       => Mreq::tp('tech_cdma') ,
		'pj_id'           => Mreq::tp('pj-id') ,
        'photo_id'        => Mreq::tp('photo_id') ,//Array
        'photo_titl'      => Mreq::tp('photo_titl') ,//Array
		);





  //Check if array have empty element return list
  //for acceptable empty field do not put here
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

	/*if($posted_data['id_perm'] == null OR !MInit::exist_select('permissionnaires', $posted_data['id_perm'])){
		$empty_list .='<li>Selectionnez un permissionnaire valide';
		$checker = 1;
	}*/
	if($posted_data['nom_station'] == null){
		$empty_list .='<li>Insérez nom de la station ';
		$checker = 1;
	}if($posted_data['adresse'] == null){
		$empty_list .='<li>Insérez Adresse';
		$checker = 1;
	}
	if($posted_data['ville'] == null OR !MInit::exist_select('ref_ville', $posted_data['ville'])){
		$empty_list .='<li>Selectionnez une ville valide';
		$checker = 1;
	}
	if($posted_data['longi'] == null){
		$empty_list .='<li>Insérez valeur de longitude';
		$checker = 1;
	}
	if($posted_data['latit'] == null){
		$empty_list .='<li>Insérez valeur Latitude';
		$checker = 1;
	}
	if(!in_array($posted_data['type_support'], array('Pylone','Rooftop'))){
		$empty_list .='<li>Selectionnez Type de Support';
		$checker = 1;
	}
	if(!in_array($posted_data['shared_site'], array(0, 1))){
		$empty_list .='<li>Valeur Site Partage invalid';
		$checker = 1;
	}
	if($posted_data['oper_share'] != null AND $posted_data['shared_site'] == 1 ){
		$empty_list .='<li>Insérez l\'Opérateur de partage';
		$checker = 1;
	}
	if(!in_array($posted_data['power_generator'], array(0, 1))){
		$empty_list .='<li>Valeur de Group Electrogène invalid';
		$checker = 1;
	}
	if(!in_array($posted_data['power_company'], array(0, 1))){
		$empty_list .='<li>Valeur de réseau electrique invalid';
		$checker = 1;
	}
	if(!in_array($posted_data['power_solar'], array(0, 1))){
		$empty_list .='<li>Valeur de Energie Solaire invalid';
		$checker = 1;
	}
	if($posted_data['power_generator'] ==  0 AND $posted_data['power_solar'] ==  0 AND $posted_data['power_company'] == 0){
		$empty_list .='<li>Il faut choisir une source d\'energie';
		$checker = 1;
	}
	if(!in_array($posted_data['bh_fh'], array(0, 1))){
		$empty_list .='<li>Valeur de Backhaul FH invalid';
		$checker = 1;
	}
	if(!in_array($posted_data['bh_vsat'], array('0','1'))){
		$empty_list .='<li>Valeur de Backhaul VSAT invalid ';
		$checker = 1;
	}
	if(!in_array($posted_data['bh_fibre'], array(0, 1))){
		$empty_list .='<li>Valeur de Backhaul Fibre invalid';
		$checker = 1;
	}
	if($posted_data['bh_fh'] ==  0 AND $posted_data['bh_vsat'] ==  0 AND $posted_data['bh_fibre'] == 0){
        $empty_list .='<li>Il faut choisir un système d\'interconnexion';
		$checker = 1;
	}
	if(!in_array($posted_data['tech_2g'], array(0, 1))){
		$empty_list .='<li>Valeur de Tech 2G invalid';
		$checker = 1;
	}
	if(!in_array($posted_data['tech_3g'], array(0, 1))){
		$empty_list .='<li>Valeur de Tech 3G invalid';
		$checker = 1;
	}
	if(!in_array($posted_data['tech_4g'], array(0, 1))){
		$empty_list .='<li>Valeur de Tech 4G invalid';
		$checker = 1;
	}
	if(!in_array($posted_data['tech_cdma'], array(0, 1))){
		$empty_list .='<li>Valeur de Tech CDMA invalid';
		$checker = 1;
	}
	if($posted_data['tech_cdma'] ==  0 AND $posted_data['tech_2g'] ==  0 AND $posted_data['tech_3g'] ==  0 AND $posted_data['tech_4g'] == 0){
		$empty_list .='<li>Il faut choisir une technologie déployée';
		$checker = 1;
	}
	if($posted_data['pj_id'] == null){
		$empty_list .='<li>Ajoutez la fiche technique scannée';
		$checker = 1;
	}

//Check if have error return Red message  
	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}



//End check empty element
	$new_gsm = new  Mgsm($posted_data);
  //Exige PJ formulaire
	$new_gsm->exige_pj     = true;
  //execute Insert returne false if error
	if($new_gsm->save_new_gsm()){
        exit("1#".$new_gsm->log);//Return green message
    }else{
        exit("0#".$new_gsm->log);//return Red Message
    }
} else {
  //If no form posted call ADD GSM form view
	view::load('gsm','addgsm');
}






?>