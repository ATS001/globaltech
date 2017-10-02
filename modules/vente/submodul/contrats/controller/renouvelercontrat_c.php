<?php

defined('_MEXEC') or die;
if (MInit::form_verif('renouvelercontrat', false)) {

    $posted_data = array(
    	'id'   			   => Mreq::tp('id') ,
        'tkn_frm'         => Mreq::tp('tkn_frm'),
        'iddevis'         => Mreq::tp('iddevis'),
        'date_effet'      => Mreq::tp('date_effet'),
        'date_fin'        => Mreq::tp('date_fin'),
        'idtype_echeance' => Mreq::tp('idtype_echeance'),
        'commentaire'     => Mreq::tp('commentaire'),
        'periode_fact'    => Mreq::tp('periode_fact'),
        'date_notif'      => Mreq::tp('date_notif') ,
        'pj_id'           => Mreq::tp('pj-id'),
        'pj_photo_id'     => Mreq::tp('pj_photo-id')
    );

    $new_contrat = new Mcontrat($posted_data);
    $new_contrat->id_contrat = $posted_data['id'];
	$new_contrat->get_contrat();


    $new_contrat->exige_pj       = FALSE;
    $new_contrat->exige_pj_photo = FALSE;


    //Check if array have empty element return list
    //for acceptable empty field do not put here


    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";



    if ($posted_data['iddevis'] == NULL) {

        $empty_list .= "<li>Devis</li>";
        $checker = 1;
    }

    if ($posted_data['date_effet'] == NULL) {

        $empty_list .= "<li>Date d'effet</li>";
        $checker = 1;
    }

    if ($posted_data['date_fin'] == NULL) {

        $empty_list .= "<li>Date fin</li>";
        $checker = 1;
    }

    if ($posted_data['idtype_echeance'] == NULL) {

        $empty_list .= "<li>Type échéance</li>";
        $checker = 1;
    }

/*    if ($posted_data['commentaire'] == NULL) {

        $empty_list .= "<li>Commentaire</li>";
        $checker = 1;
    }*/
     if ($posted_data['periode_fact'] == NULL) {

        $empty_list .= "<li>Période de facturation</li>";
        $checker = 1;
    }
    if($posted_data['date_notif'] == NULL){

        $empty_list .= "<li>Date de notification</li>";
        $checker = 1;
    }


    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    if(date('Y-m-d', strtotime($posted_data['date_fin'])) <= date('Y-m-d', strtotime($posted_data['date_effet'])) ){

         $control_date = "<ul>La date de fin doit être supérieur de la date d'effet !!!</ul>";
         $checker = 2;
    }

    if($checker == 2)
    {
         exit("0#$control_date");
    }

    if(date('Y-m-d', strtotime($posted_data['date_notif'])) >= date('Y-m-d', strtotime($posted_data['date_fin']))  or date('Y-m-d', strtotime($posted_data['date_notif'])) <= date('Y-m-d', strtotime($posted_data['date_effet'])) ){

        $control_notif = "<ul>La date de notification doit être supérieur de la date d'effet et  inférieur de la date de fin !!!</ul>" ;
        $checker = 3;
    }

    if($checker == 3)
    {
        exit("0#$control_notif");
    }

    if(date('Y-m-d', strtotime($posted_data['date_effet']))  < $new_contrat->s('date_fin') ){

    	$control_renouvelement = "<ul>La date d'effet doit être supérieur de la fin de l'ancien contrat !!!</ul>";
   		$checker = 8;
    }
  	if($checker == 8)
  	{
   		exit("0#$control_renouvelement");
  	}

    $date1=date_create(date('Y-m-d', strtotime($posted_data['date_effet'])));
    $date2=date_create(date('Y-m-d', strtotime($posted_data['date_fin'])));
    $diff=date_diff($date1,$date2);

 /*   var_dump($date1);
    var_dump($date2);
   

var_dump( $diff->format('%a'));
exit();*/
    $contratA = new Mcontrat($posted_data);
    $contratA->get_id_type_echeance('Annuelle');

    $contratM = new Mcontrat($posted_data);
    $contratM->get_id_type_echeance('Mensuelle');

    $contratT = new Mcontrat($posted_data);
    $contratT->get_id_type_echeance('Trimestrielle');

    $contratS = new Mcontrat($posted_data);
    $contratS->get_id_type_echeance('Simestrielle');

/*var_dump( $diff->format('%a'));   
var_dump($contratA->Shw_type('id',1));
var_dump($contratM->Shw_type('id',1));
var_dump($contratT->Shw_type('id',1));
var_dump($contratS->Shw_type('id',1));
var_dump($posted_data['idtype_echeance']);
exit();*/


    if ($diff->format('%a') < 365 and $posted_data['idtype_echeance'] == $contratA->Shw_type('id',1))
    {
        $control_echeance_a = "<ul>Il faut choisir une période supérieure ou égale à 365 jours pour séléctionner le type d'échéance : Annuelle  !!!</ul>";
        $checker = 4;
    }
    if($checker == 4)
    {
        exit("0#$control_echeance_a");
    }

    if ($diff->format('%a') < 28 and $posted_data['idtype_echeance'] == $contratM->Shw_type('id',1))
    {
        $control_echeance_m = "<ul>Il faut choisir une période supérieure ou égale à 28 jours pour séléctionner le type d'échéance : Mensuelle !!!</ul>";
        $checker = 5;
    }
    if($checker == 5)
    {
        exit("0#$control_echeance_m");
    }

    if ($diff->format('%a') < 88 and $posted_data['idtype_echeance'] == $contratT->Shw_type('id',1))
    {
        $control_echeance_t = "<ul>Il faut choisir une période supérieure ou égale à 88 jours pour séléctionner le type d'échéance : Trimestrielle !!!</ul>";
        $checker = 6;
    }
    if($checker == 6)
    {
        exit("0#$control_echeance_t");
    }

    if ($diff->format('%a') < 178 and $posted_data['idtype_echeance'] == $contratS->Shw_type('id',1))
    {
        $control_echeance_s = "<ul>Il faut choisir une période supérieure ou égale à 178 jours pour séléctionner le type d'échéance : Simestrielle !!!</ul>";
        $checker = 7;
    }

    if($checker == 7)
    {
        exit("0#$control_echeance_s");
    }



    //End check empty element

    $new_contrat = new Mcontrat($posted_data);
    $new_contrat->id_contrat = $posted_data['id'];
	

    $new_contrat->exige_pj       = FALSE;
    $new_contrat->exige_pj_photo = FALSE;

    //execute Insert returne false if error
    if ($new_contrat->save_new_contrat()) {

    	if($new_contrat->valid_contrats(3))
    	{
        echo("1#" . $new_contrat->log);
        }
    } else {

        echo("0#" . $new_contrat->log);
    }
} else {
    view::load_view('renouvelercontrat');
}
