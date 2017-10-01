<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller


defined('_MEXEC') or die;
if (MInit::form_verif('editcontrat_frn', false)) {
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        // returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }

  	$posted_data = array(
  		'id'   			   => Mreq::tp('id') ,
   		'reference'        => Mreq::tp('reference') ,
   		'id_fournisseur'   => Mreq::tp('id_fournisseur') ,
   		'date_effet'       => Mreq::tp('date_effet') ,
   		'date_fin'    	   => Mreq::tp('date_fin') ,
  		'commentaire'      => Mreq::tp('commentaire') ,
      'date_notif'       => Mreq::tp('date_notif') ,
   		'pj_id'            => Mreq::tp('pj-id')
  
   	);


    //Check if array have empty element return list
    //for acceptable empty field do not put here


     $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";


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

 /* if($posted_data['commentaire'] == NULL){

    $empty_list .= "<li>Commentaire</li>";
    $checker = 1;
  }*/

  if($posted_data['date_notif'] == NULL){

    $empty_list .= "<li>Date de notification</li>";
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


  if($posted_data['date_fin'] <= $posted_data['date_effet']){

    $control_date = "<ul>La date de fin doit être supérieur de la date d'effet !!!</ul>";
    $checker = 2;
  }

   if($checker == 2)
  {
    exit("0#$control_date");
  }

   if($posted_data['date_notif'] >= $posted_data['date_fin']  or $posted_data['date_notif'] <= $posted_data['date_effet'] ){

    $control_notif = "<ul>La date de notification doit être supérieur de la date d'effet et  inférieur de la date de fin !!!</ul>";
    $checker = 3;
   }
  if($checker == 3)
  {
    exit("0#$control_notif");
  }
    //End check empty element


    $new_contrats_frn = new Mcontrats_fournisseurs($posted_data);
    $new_contrats_frn->id_contrats_frn = $posted_data['id'];

    $new_contrats_frn->exige_pj = TRUE;

    //execute Insert returne false if error
    if ($new_contrats_frn->edit_contrats_frn()) {

        echo("1#" . $new_contrats_frn->log);
    } else {

        echo("0#" . $new_contrats_frn->log);
    }
} else {
    view::load_view('editcontrat_frn');
}

?>