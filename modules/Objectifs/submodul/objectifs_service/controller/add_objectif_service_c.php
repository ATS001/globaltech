<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs_service
//Created : 23-09-2018
//Controller ADD Form
if(MInit::form_verif('addobjectifs_service', false))
{

		$posted_data = array(

			'descreption'  => Mreq::tp('descreption') ,
            'service'      => Mreq::tp('service') ,
            'objectif'     => Mreq::tp('objectif') ,
            'date_debut'   => Mreq::tp('date_debut') ,
            'date_fin'     => Mreq::tp('date_fin') ,


		);


        //Check if array have empty element return list
        //for acceptable empty field do not put here
		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

		if($posted_data["descreption"] == NULL){
			$empty_list .= "<li>Descreption</li>";
			$checker = 1;
		}
		if($posted_data["service"] == NULL){
			$empty_list .= "<li>Id Service</li>";
			$checker = 1;
		}
		if($posted_data["objectif"] == NULL){
			$empty_list .= "<li>Objectif à atteindre</li>";
			$checker = 1;
		}
		if($posted_data["date_debut"] == NULL){
			$empty_list .= "<li>Date début</li>";
			$checker = 1;
		}
		if($posted_data["date_fin"] == NULL){
			$empty_list .= "<li>Date Fin</li>";
			$checker = 1;
		}
		$date_debut = new DateTime($posted_data["date_debut"]);
        $date_fin   = new DateTime($posted_data["date_fin"]);
		if($date_debut  > $date_fin){
			$empty_list .= "<li>La Date fin ne doit pas précider la Date début !</li>";
			$checker = 1;
		}




		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



       //End check empty element
		$new_objectifs_service = new  Mobjectifs_service($posted_data);



        //execute Insert returne false if error
		if($new_objectifs_service->save_new_objectifs_service()){

			exit("1#".$new_objectifs_service->log);
		}else{

			exit("0#".$new_objectifs_service->log);
		}


}

//No form posted show view
view::load_view('add_objectif_service');







	?>