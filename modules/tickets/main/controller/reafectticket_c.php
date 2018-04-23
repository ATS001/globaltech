<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//Controller ADD Form
if (MInit::form_verif('reafectticket', false)) {

    $posted_data = array(
        'id_client' => Mreq::tp('id_client'),
        'projet' => Mreq::tp('projet'),
        'message' => Mreq::tp('message'),
        'date_previs' => Mreq::tp('date_previs'),
        'date_realis' => Mreq::tp('date_realis'),
        'type_produit' => Mreq::tp('type_produit'),
        'categorie_produit' => Mreq::tp('categorie_produit'),
        'idtechnicien' => Mreq::tp('idtechnicien'),
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["id_client"] == NULL) {
        $empty_list .= "<li>Client</li>";
        $checker = 1;
    }
    if ($posted_data["projet"] == NULL) {
        $empty_list .= "<li>Projet</li>";
        $checker = 1;
    }
    if ($posted_data["message"] == NULL) {
        $empty_list .= "<li>Message</li>";
        $checker = 1;
    }
    if ($posted_data["date_previs"] == NULL) {
        $empty_list .= "<li>Date prévisionnelle</li>";
        $checker = 1;
    }
    if ($posted_data["date_realis"] == NULL) {
        $empty_list .= "<li>Date de réalisation</li>";
        $checker = 1;
    }
    if ($posted_data["type_produit"] == NULL) {
        $empty_list .= "<li>Type produit</li>";
        $checker = 1;
    }
    if ($posted_data["categorie_produit"] == NULL) {
        $empty_list .= "<li>Catégorie produit</li>";
        $checker = 1;
    }
    if ($posted_data["idtechnicien"] == NULL) {
        $empty_list .= "<li>Technicien</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End check empty element
    $new_tickets = new Mtickets($posted_data);

    //execute Insert returne false if error
    if ($new_tickets->save_new_tickets()) {

        exit("1#" . $new_tickets->log);
    } else {

        exit("0#" . $new_tickets->log);
    }
}

//No form posted show view
view::load_view('reafectticket');
?>