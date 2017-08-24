<?php 
//SYS GLOBAL TECH
// Modul: clients => Controller

defined('_MEXEC') or die;
if(MInit::form_verif('addcategorie_client',false))
{
	
  $posted_data = array(
   'r_social'       => Mreq::tp('r_social') ,
   'r_commerce'     => Mreq::tp('r_commerce') ,
   'nom'            => Mreq::tp('nom') ,
   'prenom'         => Mreq::tp('prenom') ,
   'civilite'       => Mreq::tp('civilite') ,
   'adresse'        => Mreq::tp('adresse') ,
   'id_pays'        => Mreq::tp('id_pays') ,
   'id_ville'       => Mreq::tp('id_ville') ,
   'tel'            => Mreq::tp('tel') ,
   'fax'            => Mreq::tp('fax') ,
   'bp'             => Mreq::tp('bp') ,
   'email'          => Mreq::tp('email') , 
   'id_categorie'   => Mreq::tp('id_categorie') ,
   'pj_id'          => Mreq::tp('pj-id'),
   'pj_photo'       => Mreq::tp('pj_photo')
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


 $new_categorie_client = new  Mcategorie_client($posted_data);
  
  

  //execute Insert returne false if error
  if($new_categorie_client->save_new_categorie_client()){

    echo("1#".$new_categorie_client->log);
  }else{

    echo("0#".$new_categorie_client->log);
  }


} else {
  view::load('categorie_client','addcategorie_client');
}
?>