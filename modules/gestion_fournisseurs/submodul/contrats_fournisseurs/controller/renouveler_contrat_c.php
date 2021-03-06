<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller


defined('_MEXEC') or die;
if (MInit::form_verif('renouveler_contrat', false)) {
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

   	$new_contrats_frn = new Mcontrats_fournisseurs($posted_data);
    $new_contrats_frn->id_contrats_frn = $posted_data['id'];
    $new_contrats_frn->get_contrats_frn();
    
    $new_contrats_frn->exige_pj = TRUE;


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


  if(date('Y-m-d', strtotime($posted_data['date_fin'])) <= date('Y-m-d', strtotime($posted_data['date_effet'])) ){

    $control_date = "<ul>La date de fin doit être supérieur à la date d'effet !!!</ul>";
    $checker = 2;
  }

   if($checker == 2)
  {
    exit("0#$control_date");
  }

  if(date('Y-m-d', strtotime($posted_data['date_notif'])) >= date('Y-m-d', strtotime($posted_data['date_fin']))  or date('Y-m-d', strtotime($posted_data['date_notif'])) <= date('Y-m-d', strtotime($posted_data['date_effet'])) ){

        $control_notif = "<ul>La date de notification doit être supérieur à la date d'effet et  inférieur de la date de fin !!!</ul>" ;
        $checker = 3;
  }
  if($checker == 3)
  {
    exit("0#$control_notif");
  }


   if(date('Y-m-d', strtotime($posted_data['date_effet']))  < date('Y-m-d', strtotime($new_contrats_frn->Shw('date_fin',1))) ){

    $control_renouvelement = "<ul>La date d'effet doit être supérieur à la fin de l'ancien contrat !!!</ul>";
    $checker = 4;
   }
  if($checker == 4)
  {
    exit("0#$control_renouvelement");
  }


  

    //End check empty element


    $new_contrats_frn = new Mcontrats_fournisseurs($posted_data);
    $new_contrats_frn->id_contrats_frn = $posted_data['id'];
    
    $new_contrats_frn->exige_pj = TRUE;

    //execute Insert returne false if error
   if($new_contrats_frn->save_new_contrats_frn()){

		if($new_contrats_frn->valid_contrats_frn(4))
			{
    echo("1#".$new_contrats_frn->log);
    	    }
  }else{

    echo("0#".$new_contrats_frn->log);
  }


} else {
    view::load_view('renouveler_contrat');
}

?>