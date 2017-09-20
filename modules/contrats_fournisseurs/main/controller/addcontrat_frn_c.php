<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller



defined('_MEXEC') or die;
if(MInit::form_verif('addcontrat_frn',false))
{
	
  $posted_data = array(
   'reference'        => Mreq::tp('reference') ,
   'id_fournisseur'   => Mreq::tp('id_fournisseur') ,
   'date_effet'       => Mreq::tp('date_effet') ,
   'date_fin'    	  => Mreq::tp('date_fin') ,
   'commentaire'      => Mreq::tp('commentaire') ,
   'pj_id'            => Mreq::tp('pj-id'),
   'pj_photo_id'      => Mreq::tp('pj_photo-id')
   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['reference'] == NULL){

    $empty_list .= "<li>Réference</li>";
    $checker = 1;
  }

  if($posted_data['id_fournisseur'] == NULL){

    $empty_list .= "<li>Fournisseur</li>";
    $checker = 1;
  }
  if($posted_data['date_effet'] == NULL){

    $empty_list .= "<li>Date Effet</li>";
    $checker = 1;
  }
  if($posted_data['date_fin'] == NULL){

    $empty_list .= "<li>Date Fin</li>";
    $checker = 1;
  }

  if($posted_data['commentaire'] == NULL){

    $empty_list .= "<li>Commentaire</li>";
    $checker = 1;
  }

  if($posted_data['pj_id'] == NULL){

    $empty_list .= "<li>Pièce jointe du contrat</li>";
    $checker = 1;
  }



  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element

  $new_contrats_frn = new  Mcontrats_fournisseurs($posted_data);

  $new_contrats_frn->exige_pj       = false;
  $new_contrats_frn->exige_pj_photo = false;

  //execute Insert returne false if error
  if($new_contrats_frn->save_new_contrats_frn()){

    echo("1#".$new_contrats_frn->log);
  }else{

    echo("0#".$new_contrats_frn->log);
  }


} else {
  view::load_view('addcontrat_frn');
}
?>