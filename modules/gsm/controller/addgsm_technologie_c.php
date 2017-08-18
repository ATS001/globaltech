<?php 
//SYS MRN ERP
// Modul: gsm_technologie => Controller
defined('_MEXEC') or die;
if(MInit::form_verif('addgsm_technologie', false))
 { 

   $posted_data = array(
     'id_site_gsm'    => Mreq::tp('id_site_gsm') ,
     'technologie'    => Mreq::tp('technologie') ,
     'marque_bts'     => Mreq::tp('marque_bts') ,
     'num_serie'      => Mreq::tp('num_serie') ,
     'modele_antenne' => Mreq::tp('modele_antenne') ,
     'nbr_radio'      => Mreq::tp('nbr_radio') ,
     'nbr_secteur'    => Mreq::tp('nbr_secteur') 
    

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['technologie'] == NULL){

    $empty_list .= "<li>Technologie</li>";
    $checker = 1;
  }
  if($posted_data['marque_bts'] == NULL){

    $empty_list .= "<li>Marque BTS</li>";
    $checker = 1;
  }
    if($posted_data['num_serie'] == NULL){

    $empty_list .= "<li>Numéro de série</li>";
    $checker = 1;
  }
  if($posted_data['modele_antenne'] == NULL){
  		
    $empty_list .= "<li>Modele antenne</li>";
    $checker = 1;
  }
  if($posted_data['nbr_radio'] == NULL and  !is_numeric($posted_data['nbr_radio']) ){

    $empty_list .= "<li>Nombre radios</li>";
    $checker = 1;
  }
  
 
  
  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element


  $new_gsm_technologie = new  Mgsm_technologie($posted_data);

  //execute Insert returne false if error
  if($new_gsm_technologie->save_new_gsm_technologie()){
    exit("1#".$new_gsm_technologie->log);//Green message
  }else{
    exit("0#".$new_gsm_technologie->log);//Red Message
  }

} else {
  //call form if no post
  view::load('gsm','addgsm_technologie');
}
?>