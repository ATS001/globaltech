<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fournisseurs
//Created : 19-07-2018
//Controller ADD Form
if(MInit::form_verif('bloquerfournisseur', false))
{

		$posted_data = array(

			'reference'         => Mreq::tp('reference') ,
'denomination'         => Mreq::tp('denomination') ,
'r_social'         => Mreq::tp('r_social') ,
'r_commerce'         => Mreq::tp('r_commerce') ,
'nif'         => Mreq::tp('nif') ,
'nom'         => Mreq::tp('nom') ,
'prenom'         => Mreq::tp('prenom') ,
'civilite'         => Mreq::tp('civilite') ,
'adresse'         => Mreq::tp('adresse') ,
'id_pays'         => Mreq::tp('id_pays') ,
'id_ville'         => Mreq::tp('id_ville') ,
'tel'         => Mreq::tp('tel') ,
'fax'         => Mreq::tp('fax') ,
'bp'         => Mreq::tp('bp') ,
'email'         => Mreq::tp('email') ,
'rib'         => Mreq::tp('rib') ,
'id_devise'         => Mreq::tp('id_devise') ,
'pj'         => Mreq::tp('pj') ,
'pj_photo'         => Mreq::tp('pj_photo') ,


		);


        //Check if array have empty element return list
        //for acceptable empty field do not put here
		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

			if($posted_data["reference"] == NULL){
                                    $empty_list .= "<li>Code fournisseur</li>";
                                    $checker = 1;
                              }
	if($posted_data["denomination"] == NULL){
                                    $empty_list .= "<li>Denomination du client</li>";
                                    $checker = 1;
                              }
	if($posted_data["r_social"] == NULL){
                                    $empty_list .= "<li>Raison social</li>";
                                    $checker = 1;
                              }
	if($posted_data["r_commerce"] == NULL){
                                    $empty_list .= "<li>Registre de commerce</li>";
                                    $checker = 1;
                              }
	if($posted_data["nif"] == NULL){
                                    $empty_list .= "<li>Id fiscale</li>";
                                    $checker = 1;
                              }
	if($posted_data["nom"] == NULL){
                                    $empty_list .= "<li>Nom</li>";
                                    $checker = 1;
                              }
	if($posted_data["prenom"] == NULL){
                                    $empty_list .= "<li>Prénom</li>";
                                    $checker = 1;
                              }
	if($posted_data["civilite"] == NULL){
                                    $empty_list .= "<li>Sexe</li>";
                                    $checker = 1;
                              }
	if($posted_data["adresse"] == NULL){
                                    $empty_list .= "<li>Adresse</li>";
                                    $checker = 1;
                              }
	if($posted_data["id_pays"] == NULL){
                                    $empty_list .= "<li>Pays</li>";
                                    $checker = 1;
                              }
	if($posted_data["id_ville"] == NULL){
                                    $empty_list .= "<li>Ville</li>";
                                    $checker = 1;
                              }
	if($posted_data["tel"] == NULL){
                                    $empty_list .= "<li>Telephone</li>";
                                    $checker = 1;
                              }
	if($posted_data["fax"] == NULL){
                                    $empty_list .= "<li>Fax</li>";
                                    $checker = 1;
                              }
	if($posted_data["bp"] == NULL){
                                    $empty_list .= "<li>Boite postale</li>";
                                    $checker = 1;
                              }
	if($posted_data["email"] == NULL){
                                    $empty_list .= "<li>E-mail</li>";
                                    $checker = 1;
                              }
	if($posted_data["rib"] == NULL){
                                    $empty_list .= "<li>compte bancaire du fournisseur</li>";
                                    $checker = 1;
                              }
	if($posted_data["id_devise"] == NULL){
                                    $empty_list .= "<li>Devise de facturation du client</li>";
                                    $checker = 1;
                              }
	if($posted_data["pj"] == NULL){
                                    $empty_list .= "<li>pj</li>";
                                    $checker = 1;
                              }
	if($posted_data["pj_photo"] == NULL){
                                    $empty_list .= "<li>photo du client</li>";
                                    $checker = 1;
                              }



		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



       //End check empty element
		$new_fournisseurs = new  Mfournisseurs($posted_data);



        //execute Insert returne false if error
		if($new_fournisseurs->save_new_fournisseurs()){

			exit("1#".$new_fournisseurs->log);
		}else{

			exit("0#".$new_fournisseurs->log);
		}


}

//No form posted show view
view::load_view('bloquerfournisseur');







	?>