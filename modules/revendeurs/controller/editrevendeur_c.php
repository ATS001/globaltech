<?php
if(MInit::form_verif('editrevendeur', false))//If form is Posted do Action else rend empty form
{
	//Check if id is been the correct id compared with idc
  if(!MInit::crypt_tp('id', null, 'D') )
  {  
  //returne message error red to client 
    exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
  }
  //Listed data from posted form
  $posted_data = array(
   'id'             => Mreq::tp('id') ,
   'type_rev'       => 'MORALE' ,
   'denomination'   => Mreq::tp('denomination') ,
   'piece_identite' => Mreq::tp('piece_identite') ,
   'num_agrement'   => Mreq::tp('num_agrement') ,
   'qualification'  => Mreq::tp('qualification') ,
   'ville'          => Mreq::tp('ville') ,
   'adresse'        => Mreq::tp('adresse') ,
   'email'          => Mreq::tp('email') ,
   'tel'            => Mreq::tp('tel') ,
   'fax'            => Mreq::tp('fax') ,   
   'vsat'           => Mreq::tp('vsat') ,
   'uhf_vhf'        => Mreq::tp('uhf_vhf') ,
   'gsm'            => Mreq::tp('gsm') ,
   'blr'            => Mreq::tp('blr') ,
   'pj_id'          => Mreq::tp('pj-id'),
   'pj_image_id'    => Mreq::tp('pj_image-id')
   );

  //Check if array have empty element return list
  //for acceptable empty field do not put here
   $checker = null;
   $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['pj_id'] == NULL){

    $empty_list .= "<li>Demande d'agrément scanné</li>";
    $checker = 1;
  }
  /*if($posted_data['type_rev'] == NULL){

    $empty_list .= "<li>Type revendeur</li>";
    $checker = 1;
  }*/
    if($posted_data['pj_image_id'] == NULL){

    $empty_list .= "<li>Logo de la société</li>";
    $checker = 1;
  }
  if($posted_data['denomination'] == NULL){
  		
    $empty_list .= "<li>Raison Sociale</li>";
    $checker = 1;
  }
  if($posted_data['piece_identite'] == NULL){

    $empty_list .= "<li>Registre Commerce</li>";
    $checker = 1;
  }
  if($posted_data['num_agrement'] == NULL){

    $empty_list .= "<li>Numéro d'agrément</li>";
    $checker = 1;
  }
  if($posted_data['qualification'] == NULL){

    $empty_list .= "<li>Secteur d'activité</li>";
    $checker = 1;
  }
 
    //Step 2
  if($posted_data['ville'] == NULL){

    $empty_list .= "<li>Ville</li>";
    $checker = 1;
  }

  if($posted_data['adresse'] == NULL){

    $empty_list .= "<li>Adresse</li>";
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
  if($posted_data['vsat'] == NULL){

    $empty_list .= "<li>Vente VSAT </li>";
    $checker = 1;
  }
  if($posted_data['uhf_vhf'] == NULL){

    $empty_list .= "<li>Vente UHF/VHF </li>";
    $checker = 1;
  }
  if($posted_data['gsm'] == NULL){

    $empty_list .= "<li>Vente GSM</li>";
    $checker = 1;
  }
  if($posted_data['blr'] == NULL){

    $empty_list .= "<li>Vente BLR</li>";
    $checker = 1;
  }
  
  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }
  //End Checker

  //Call Model
  $new_rev = new  Mrev($posted_data);
  $new_rev->exige_pj          = true;
  $new_rev->exige_pj_image    = true;

  $new_rev->id_rev = $posted_data['id'];
  //execute Insert returne false if error
  if($new_rev->edit_rev()){
    exit("1#".$new_rev->log);//Green message
  }else{
    exit("0#".$new_rev->log);//Red Message
  }

} else {
  //call form if no post
  view::load('revendeurs','editrevendeur');
}
 
?>