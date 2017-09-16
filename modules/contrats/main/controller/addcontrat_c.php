<?php

defined('_MEXEC') or die;
if (MInit::form_verif('addcontrat', false)) {

    $posted_data = array(
        'tkn_frm' => Mreq::tp('tkn_frm'),
        'iddevis' => Mreq::tp('iddevis'),
        'date_effet' => Mreq::tp('date_effet'),
        'date_fin' => Mreq::tp('date_fin'),
        'idtype_echeance =>' => Mreq::tp('idtype_echeance'),
        'commentaire' => Mreq::tp('commentaire'),
        'pj_id' => Mreq::tp('pj-id'),
        'pj_photo_id' => Mreq::tp('pj_photo-id')
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

    if ($posted_data['commentaire'] == NULL) {

        $empty_list .= "<li>Commentaire</li>";
        $checker = 1;
    }



    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End check empty element

    $new_contrat = new Mcontrat($posted_data);

    $new_contrat->exige_pj = FALSE;
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