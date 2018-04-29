<?php
//First check target no Hack
if (!defined('_MEXEC')) {die();}
//SYS GLOBAL TECH
// Modul: tickets
//Created : 06-04-2018
//Controller ADD Form
if (MInit::form_verif('addaction', false)) {

    $posted_data = array(
        'id_ticket' => Mreq::tp('id_ticket'),
        'message' => Mreq::tp('message'),
        'date_action' => Mreq::tp('date_action'),
        'pj_id' => Mreq::tp('pj-id'),
        'photo_id' => Mreq::tp('photo-id'),
    );

    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["message"] == NULL) {
        $empty_list .= "<li>Message</li>";
        $checker = 1;
    }

    if ($posted_data["date_action"] == NULL) {
        $empty_list .= "<li>Date action</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End check empty element
    $new_action = new Mtickets($posted_data);
    $new_action->exige_pj=false;
    $new_action->exige_photo=false;

    //execute Insert returne false if error
    if ($new_action->save_new_action()) {

        exit("1#" . $new_action->log);
    } else {

        exit("0#" . $new_action->log);
    }
}

//No form posted show view
view::load_view('addaction');
?>