
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('edit_info_ste',false))
	{

		$posted_data = array(

			'ste_name'    => Mreq::tp('ste_name') ,
			'ste_bp'      => Mreq::tp('ste_bp') ,
			'ste_adresse' => Mreq::tp('ste_adresse') ,
			'ste_tel'     => Mreq::tp('ste_tel') ,
			'ste_fax'     => Mreq::tp('ste_fax') ,
			'ste_email'   => Mreq::tp('ste_email') ,
			'ste_if'      => Mreq::tp('ste_if') ,
			'ste_rc'      => Mreq::tp('ste_rc') ,
			'ste_website' => Mreq::tp('ste_website') ,

		);


  //Check if array have empty element return list
  //for acceptable empty field do not put here



		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
		if(!MInit::is_regex($posted_data['ville'])){

			$empty_list .= "<li>Ville non valide (a-z 1-9)</li>";
			$checker = 1;
		}

		if($posted_data['ville'] == NULL){

			$empty_list .= "<li>Ville</li>";
			$checker = 1;
		}
		if($posted_data['latitude'] == NULL){

			$empty_list .= "<li>Latitude</li>";
			$checker = 1;
		}
		if($posted_data['longitude'] == NULL){

			$empty_list .= "<li>Longitude</li>";
			$checker = 1;
		}
		if($posted_data['id_departement'] == NULL){

			$empty_list .= "<li>Département</li>";
			$checker = 1;
		}

		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



  //End check empty element

		$ste_info = new  MSte_info($posted_data);



  //execute Insert returne false if error
		if($ste_info->edit_info_ste()){

			echo("1#".$ste_info->log);
		}else{

			echo("0#".$ste_info->log);
		}


	} else {
		view::load_view('info_ste');
	}





	?>