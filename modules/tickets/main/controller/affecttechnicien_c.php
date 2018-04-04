<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 03-04-2018
//Controller ADD Form
if (MInit::form_verif('affecttechnicien', false)) {

    $posted_data = array(
        'id' => Mreq::tp('id'),
        'id_technicien' => Mreq::tp('id_technicien'),
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    
    if ($posted_data["id_technicien"] == NULL) {
        $empty_list .= "<li>Technicien</li>";
        $checker = 1;
    }
  

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }



    //End check empty element
    $new_tickets = new Mtickets($posted_data);
    $new_tickets->id_tickets=$posted_data["id"];
//******************************************************
    //execute Insert returne false if error
    if ($new_tickets->affect_ticket()) {

        exit("1#" . $new_tickets->log);
    } else {

        exit("0#" . $new_tickets->log);
    }
    //*************************************
    
    
}

//No form posted show view
view::load_view('affecttechnicien');
?>