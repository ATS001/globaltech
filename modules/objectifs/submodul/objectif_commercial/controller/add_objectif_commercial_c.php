<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_commercial
//Created : 01-11-2018
//Controller ADD Form
if(MInit::form_verif('add_objectif_commercial', false))
{

	$posted_data = array(

		'description'   => Mreq::tp('description') ,
		'objectif'      => Mreq::tp('objectif') ,
		'realise'       => Mreq::tp('realise') ,
		'id_commercial' => Mreq::tp('id_commercial') ,
		'date_s'        => Mreq::tp('date_s') ,
		'date_e'        => Mreq::tp('date_e') ,


	);


									//Check if array have empty element return list
									//for acceptable empty field do not put here
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

	if($posted_data["description"] == NULL){
		$empty_list .= "<li>Description</li>";
		$checker = 1;
	}
	if(strtotime($posted_data["date_e"]) <= strtotime($posted_data["date_s"])){
		$empty_list .= "<li>Date Fin incorrect</li>";
		$checker = 1;
	}
	if($posted_data["objectif"] == NULL OR !is_numeric($posted_data["objectif"])){
		$empty_list .= "<li>Objectif</li>";
		$checker = 1;
	}
	
	if($posted_data["id_commercial"] == NULL){
		$empty_list .= "<li>Commercial</li>";
		$checker = 1;
	}
	if($posted_data["date_s"] == NULL){
		$empty_list .= "<li>Date début</li>";
		$checker = 1;
	}
	if($posted_data["date_e"] == NULL){
		$empty_list .= "<li>Date fin</li>";
		$checker = 1;
	}



	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}



									//End check empty element
	$new_objectif_commercial = new  Mobjectif_commercial($posted_data);



									//execute Insert returne false if error
	if($new_objectif_commercial->save_new_objectif_commercial()){

		exit("1#".$new_objectif_commercial->log);
	}else{

		exit("0#".$new_objectif_commercial->log);
	}


}

//No form posted show view
view::load_view('add_objectif_commercial');







	?>