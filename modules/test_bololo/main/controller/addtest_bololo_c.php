<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: test_bololo
//Created : 29-10-2017
//Controller ADD Form
if(MInit::form_verif('addtest_bololo', false))
	{

		$posted_data = array(

			'nom'         => Mreq::tp('nom') ,
			'prenom'   => Mreq::tp('prenom') ,
			'dat_nais' => Mreq::tp('dat_nais') ,


		);


        //Check if array have empty element return list
        //for acceptable empty field do not put here
		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

		if($posted_data["nom"] == NULL){
			$empty_list .= "<li>Nom</li>";
			$checker = 1;
		}
		if($posted_data["prenom"] == NULL){
			$empty_list .= "<li>Prénom</li>";
			$checker = 1;
		}
		if($posted_data["dat_nais"] == NULL){
			$empty_list .= "<li>Date Naiss</li>";
			$checker = 1;
		}



		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



       //End check empty element
		$new_test_bololo = new  Mtest_bololo($posted_data);



        //execute Insert returne false if error
		if($new_test_bololo->save_new_test_bololo()){

			exit("1#".$new_test_bololo->log);
		}else{

			exit("0#".$new_test_bololo->log);
		}


	}

//No form posted show view
	view::load_view('addtest_bololo');







	?>