<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//Controller EDIT Form
if (MInit::form_verif('editticket_frs', false)) {
    if (!MInit::crypt_tp('id', null, 'D')) {
              exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'id_fournisseur' => Mreq::tp('id_fournisseur'),
        'date_incident' => Mreq::tp('date_incident'),
        'autre_nt' => Mreq::tp('autre_nt'),
        'nature_incident' => Mreq::tp('nature_incident'),
        'description' => Mreq::tp('description'),
        'prise_charge_frs' => Mreq::tp('prise_charge_frs'),
        'autre_pecf' => Mreq::tp('autre_pecf'),
        'prise_charge_glbt' => Mreq::tp('prise_charge_glbt'),
        'autre_pecg' => Mreq::tp('autre_pecg'),
    );


    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["id_fournisseur"] == NULL) {
        $empty_list .= "<li>Fournisseur</li>";
        $checker = 1;
    }
    if ($posted_data["date_incident"] == NULL) {
        $empty_list .= "<li>Date incident</li>";
        $checker = 1;
    }
    if ($posted_data["nature_incident"] == NULL) {
        $empty_list .= "<li>Nature incident</li>";
        $checker = 1;
    }

    if ($posted_data["prise_charge_frs"] == NULL) {
        $empty_list .= "<li>Prise en charge Fournisseur</li>";
        $checker = 1;
    }
    if ($posted_data["prise_charge_glbt"] == NULL) {
        $empty_list .= "<li>Prise en charge Globaltech</li>";
        $checker = 1;
    }


    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End check empty element
    $edit_ticket_frs = new Mticket_frs($posted_data);

    //Set ID of row to update
    $edit_ticket_frs->id_tickets = $posted_data['id'];

    //execute Update returne false if error
    if ($edit_ticket_frs->edit_tickets()) {

        exit("1#" . $edit_ticket_frs->log);
    } else {

        exit("0#" . $edit_ticket_frs->log);
    }
}

//No form posted show view
view::load_view('editticket_frs');
?>