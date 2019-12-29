<?php 
//SYS GLOBAL TECH
// Modul: client => Controller
defined('_MEXEC') or die;
if(MInit::form_verif('editclient',false))
{
  //Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D'))
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }
  $posted_data = array(
   'id'             => Mreq::tp('id') ,
  // 'code'           => Mreq::tp('code') ,
   'denomination'   => Mreq::tp('denomination') ,
   'id_categorie'   => Mreq::tp('id_categorie') ,
   'r_social'       => Mreq::tp('r_social') ,
   'r_commerce'     => Mreq::tp('r_commerce') ,
   'nif'            => Mreq::tp('nif') ,
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
   'id_banque'      => Mreq::tp('id_banque') ,
   'rib'            => Mreq::tp('rib') , 
   //'id_devise'      => Mreq::tp('id_devise') , 
   'tva'            => Mreq::tp('tva') ,
   'pj_id'          => Mreq::tp('pj-id'),
   'pj_photo_id'    => Mreq::tp('pj_photo-id')
   );

 /* var_dump($posted_data['id_ville']);*/
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  

$checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

 /* if($posted_data['code'] == NULL){

    $empty_list .= "<li>Code Client</li>";
    $checker = 1;
  }*/

  if($posted_data['denomination'] == NULL){

    $empty_list .= "<li>Dénomination</li>";
    $checker = 1;
  }

  if(!is_numeric($posted_data['id_ville'])){

    $posted_data['id_ville']=NULL;
  }
  /*if($posted_data['r_social'] == NULL){

    $empty_list .= "<li>Raison Social</li>";
    $checker = 1;
  }*/

  /*if($posted_data['id_categorie'] == NULL){

    $empty_list .= "<li>Catégorie client</li>";
    $checker = 1;
  }*/
 
  /*if($posted_data['nif'] == NULL){

    $empty_list .= "<li>N° Identification Fiscale</li>";
    $checker = 1;
  }
  if($posted_data['r_commerce'] == NULL){

    $empty_list .= "<li>N° Registre de commerce</li>";
    $checker = 1;
  }*/
    
  /*if($posted_data['adresse'] == NULL){

    $empty_list .= "<li>Adresse</li>";
    $checker = 1;
  }*/
  if($posted_data['tel'] == NULL){

    $empty_list .= "<li>Tél</li>";
    $checker = 1;
  }

  /*if($posted_data['bp'] == NULL){

    $empty_list .= "<li>Boite Postal</li>";
    $checker = 1;
  }  */
 if($posted_data['id_pays'] == NULL){

    $empty_list .= "<li>Pays</li>";
    $checker = 1;
  }

 if($posted_data['email'] == NULL){

    $empty_list .= "<li>Email</li>";
    $checker = 1;
  }
    if($posted_data['id_banque'] == NULL){

    $empty_list .= "<li>Banque</li>";
    $checker = 1;
  }
  /*
  if($posted_data['id_ville'] == NULL){

    $empty_list .= "<li>Ville</li>";
    $checker = 1;
  }

  if($posted_data['id_devise'] == NULL){

    $empty_list .= "<li>Devise</li>";
    $checker = 1;
  }*/


  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element
    
  
 $client = new  Mclients($posted_data);
 $client->id_client = $posted_data['id']; 
  
  $client->exige_pj       = false;
  $client->exige_pj_photo = false;

  //execute Insert returne false if error
  if($client->edit_client()){

    echo("1#".$client->log);
  }else{

    echo("0#".$client->log);
  }


} else {
  view::load('clients','editclient');
}
?>