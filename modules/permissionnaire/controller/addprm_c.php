<?php
if(MInit::form_verif('addprm', false))
{
	  $posted_data = array(
   'r_social'       => Mreq::tp('r_social') ,
   'sigle'          => Mreq::tp('sigle') ,
   'categorie'      => Mreq::tp('categorie') ,
   'secteur_activ'  => Mreq::tp('secteur_activ') ,
   'nif'            => Mreq::tp('nif') ,
   'rc'             => Mreq::tp('rc') ,
   'multi_national' => Mreq::tp('multi_national') ,
   'pay_siege'      => Mreq::tp('pay_siege') ,
   'adresse'        => Mreq::tp('adresse') ,
   'bp'             => Mreq::tp('bp') ,
   'ville'          => Mreq::tp('ville') ,
   'email'          => Mreq::tp('email') ,
   'tel'            => Mreq::tp('tel') ,
   'fax'            => Mreq::tp('fax') ,   
   'nom_p'          => Mreq::tp('nom_p') ,
   'nation_p'       => Mreq::tp('nation_p') ,
   'qualite_p'      => Mreq::tp('qualite_p') ,
   'adresse_p'      => Mreq::tp('adresse_p') ,
   'tel_p'          => Mreq::tp('tel_p') ,
   'email_p'        => Mreq::tp('email_p') ,
   'pj_id'          => Mreq::tp('pj-id') 
   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['pj_id'] == NULL){

    $empty_list .= "<li>Formulaire scanné</li>";
    $checker = 1;
  }
  if($posted_data['r_social'] == NULL){

    $empty_list .= "<li>Raison Social</li>";
    $checker = 1;
  }
  if($posted_data['sigle'] == NULL){

    $empty_list .= "<li>Sigle</li>";
    $checker = 1;
  }
  if($posted_data['categorie'] == NULL){

    $empty_list .= "<li>Catégorie permissionnaire</li>";
    $checker = 1;
  }
  if($posted_data['secteur_activ'] == NULL){

    $empty_list .= "<li>Secteur d'Activité </li>";
    $checker = 1;
  }
  if($posted_data['nif'] == NULL){

    $empty_list .= "<li>N° Identification Fiscale</li>";
    $checker = 1;
  }
  if($posted_data['rc'] == NULL){

    $empty_list .= "<li>N° Registre de commerce</li>";
    $checker = 1;
  }
  if($posted_data['multi_national'] == NULL){

    $empty_list .= "<li>Choix de groupement </li>";
    $checker = 1;
  }
  if($posted_data['pay_siege'] == NULL){

    $empty_list .= "<li>Pays du siège mère </li>";
    $checker = 1;
  }
    //Step 2
  if($posted_data['adresse'] == NULL){

    $empty_list .= "<li>Adresse</li>";
    $checker = 1;
  }
  if($posted_data['bp'] == NULL){

    $empty_list .= "<li>Boite Postal</li>";
    $checker = 1;
  }
  if($posted_data['ville'] == NULL){

    $empty_list .= "<li>Ville</li>";
    $checker = 1;
  }
  if($posted_data['email'] == NULL){

    $empty_list .= "<li>Email </li>";
    $checker = 1;
  }
  if($posted_data['tel'] == NULL){

    $empty_list .= "<li>Tél</li>";
    $checker = 1;
  }
  if($posted_data['fax'] == NULL){

    $empty_list .= "<li>Fax </li>";
    $checker = 1;
  }
    //Step 3 
  if($posted_data['nom_p'] == NULL){

    $empty_list .= "<li>Nom Personne Physique </li>";
    $checker = 1;
  }
  if($posted_data['adresse_p'] == NULL){

    $empty_list .= "<li>Adresse Personne Physique </li>";
    $checker = 1;
  }
  if($posted_data['qualite_p'] == NULL){

    $empty_list .= "<li>Qualite Personne Physique </li>";
    $checker = 1;
  }
  if($posted_data['nation_p'] == NULL){

    $empty_list .= "<li> Nationalité </li>";
    $checker = 1;
  }
  if($posted_data['tel_p'] == NULL){

    $empty_list .= "<li>Tél Personne Physique </li>";
    $checker = 1;
  }
  if($posted_data['email_p'] == NULL){

    $empty_list .= "<li>Email Personne Physique </li>";
    $checker = 1;
  }

  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element


  $new_prm = new  Mprms($posted_data);
  //$new_prm->exige_logo     = true;
  $new_prm->exige_pj      = true;
  //execute Insert returne false if error
  if($new_prm->save_new_prm()){
    exit("1#".$new_prm->log);//Green message
  }else{
    exit("0#".$new_prm->log);//Red Message
  }

} else {
  //call form if no post
  view::load('permissionnaire','addprm');
}
?>