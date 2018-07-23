<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 21-04-2018
//Controller EDIT Form
if (MInit::form_verif('editaction_frs', false)) {
    if (!MInit::crypt_tp('id', null, 'D')) {
        // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'date_action' => Mreq::tp('date_action'),
        'message' => Mreq::tp('message'),
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
        $empty_list .= "<li>Date Action</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End check empty element
    $edit_tickets_action = new Mticket_frs($posted_data);

    //Set ID of row to update
    $edit_tickets_action->id_action_ticket = $posted_data['id'];

    //execute Update returne false if error
    if ($edit_tickets_action->edit_tickets_action()) {

        exit("1#" . $edit_tickets_action->log);
    } else {

        exit("0#" . $edit_tickets_action->log);
    }
}

//No form posted show view
view::load_view('editaction');
?>