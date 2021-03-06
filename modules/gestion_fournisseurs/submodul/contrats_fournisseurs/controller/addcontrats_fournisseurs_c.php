<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller



defined('_MEXEC') or die;
if(MInit::form_verif('addcontrats_fournisseurs',false))
{
	
  $posted_data = array(
   'reference'        => Mreq::tp('reference') ,
   'id_fournisseur'   => Mreq::tp('id_fournisseur') ,
   'date_effet'       => Mreq::tp('date_effet') ,
   'date_fin'    	    => Mreq::tp('date_fin') ,
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

/*  if($posted_data['commentaire'] == NULL){

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
 

  //End check empty element

  $new_contrats_frn = new  Mcontrats_fournisseurs($posted_data);

  $new_contrats_frn->exige_pj       = True;

  //execute Insert returne false if error
  if($new_contrats_frn->save_new_contrats_frn()){

    echo("1#".$new_contrats_frn->log);
  }else{

    echo("0#".$new_contrats_frn->log);
  }


} else {
  view::load_view('addcontrats_fournisseurs');
}
?>