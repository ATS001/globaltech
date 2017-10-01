<?php

defined('_MEXEC') or die;
if (MInit::form_verif('addcontrat', false)) {

    $posted_data = array(
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

    $new_contrat = new Mcontrat($posted_data);

    $new_contrat->exige_pj       = FALSE;
    $new_contrat->exige_pj_photo = FALSE;

    //execute Insert returne false if error
    if ($new_contrat->save_new_contrat()) {

        echo("1#" . $new_contrat->log);
    } else {

        echo("0#" . $new_contrat->log);
    }
} else {
    view::load_view('addcontrat');
}