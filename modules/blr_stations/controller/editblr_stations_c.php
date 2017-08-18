<?php
if(MInit::form_verif('editblr_stations', false))//If form is Posted do Action else rend empty form
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
    'prm'             => Mreq::tp('prm') ,     
    'site'            => Mreq::tp('site') ,
    'longi'           => Mreq::tp('longi') ,
    'latit'           => Mreq::tp('latit') ,
    'ville'           => Mreq::tp('ville') ,
    'marque'          => Mreq::tp('marque') ,
    'modele'          => Mreq::tp('modele') ,
    'num_serie'       => Mreq::tp('num_serie') ,
    'hauteur'         => Mreq::tp('hauteur') ,
    'puissance'       => Mreq::tp('puissance') ,
    'frequence'       => Mreq::tp('frequence') ,
    'modulation'      => Mreq::tp('modulation') ,
    'remarq'          => Mreq::tp('remarq'),
    'nbr_clients'     => Mreq::tp('nbr_clients') ,
    'type_station'    => Mreq::tp('type_station') ,
    'date_visite'     => Mreq::tp('date_visite'),
    'photo_id'        => Mreq::tp('photo_id') ,//Array
    'photo_titl'      => Mreq::tp('photo_titl') ,//Array
    'pj_id'           => Mreq::tp('pj-id') ,
   
   );
  //Check if array have empty element return list
  //for acceptable empty field do not put here
 $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['prm'] == NULL){

    $empty_list .= "<li>Permissionnaire</li>";
    $checker = 1;
  }
   if($posted_data['site'] == NULL){

    $empty_list .= "<li>Nom du site</li>";
    $checker = 1;
  }
  if($posted_data['longi'] == NULL OR !is_numeric($posted_data['longi'])){

    $empty_list .= "<li>Longitude invalide</li>";
    $checker = 1;
  }
  if($posted_data['latit'] == NULL OR !is_numeric($posted_data['latit'])){

    $empty_list .= "<li>Latitude invalide</li>";
    $checker = 1;
  }
  if($posted_data['ville'] == NULL){

    $empty_list .= "<li>Ville</li>";
    $checker = 1;
  }
  if($posted_data['marque'] == NULL){

    $empty_list .= "<li>Marque</li>";
    $checker = 1;
  }
  if($posted_data['modele'] == NULL){

    $empty_list .= "<li>Modèle</li>";
    $checker = 1;
  }
  if($posted_data['num_serie'] == NULL ){

    $empty_list .= "<li>N° Série invalide </li>";
    $checker = 1;
  }
    //Step 2
  if($posted_data['hauteur'] == NULL OR !is_numeric($posted_data['hauteur'])){

    $empty_list .= "<li>Hauteur invalide</li>";
    $checker = 1;
  }
  if($posted_data['puissance'] == NULL OR !is_numeric($posted_data['puissance'])){

    $empty_list .= "<li>Puissance invalide</li>";
    $checker = 1;
  }
  if($posted_data['frequence'] == NULL ){

    $empty_list .= "<li>Fréquence</li>";
    $checker = 1;
  }
  if($posted_data['modulation'] == NULL){

    $empty_list .= "<li>Modulation</li>";
    $checker = 1;
  }
  
  if($posted_data['type_station'] == NULL){

    $empty_list .= "<li>Type de station</li>";
    $checker = 1;
  }
  
  
   if($posted_data['pj_id'] == null){
    $empty_list .='<li>Ajoutez le formulaire scanné';
    $checker = 1;
  }
  
  if($posted_data['type_station'] == "P2P" && $posted_data['nbr_clients']!= 1)
  {
      $empty_list .='<li>Le nomre de clients doit être égale à 1';
    $checker = 1;
  }
  if($posted_data['type_station'] == "P2MP" && $posted_data['nbr_clients'] <= 1)
  {
      $empty_list .='<li>Le nomre de clients  doit être supérieur à 1';
    $checker = 1;
  }
  
   if($posted_data['date_visite'] == NULL){

      $empty_list .= "<li>Date de visite</li>";
      $checker = 1;
    }
  

  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }
  if($posted_data['pj_id'] == null){
    $empty_list .='<li>Ajoutez la fiche technique scannée';
    $checker = 1;
  }
  //End Checker
  //Call Model
  $new_blr_stations = new  Mblr_stations($posted_data);
  $new_blr_stations->exige_pj     = true;
  
  $new_blr_stations->id_blr_stations = $posted_data['id'];
  //execute Edit returne false if error
  if($new_blr_stations->edit_blr_stations())
  {
    exit("1#".$new_blr_stations->log);//Green message
  }else{
    exit("0#".$new_blr_stations->log);//Red message
  }
//Call View if no POST
}else{
  view::load('blr_stations','editblr_stations');
}






?>