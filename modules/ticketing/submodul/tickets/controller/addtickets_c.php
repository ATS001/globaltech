<?php

//First check target no Hack
if (!defined('_MEXEC'))
{die();}
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//Controller ADD Form
if (MInit::form_verif('addtickets', false)) {

    $posted_data = array(
        'id_client' => Mreq::tp('id_client'),
        'projet' => Mreq::tp('projet'),
        'message' => Mreq::tp('message'),
        'date_probleme' => Mreq::tp('date_probleme'),
        'date_previs' => Mreq::tp('date_previs'),
        'type_produit' => Mreq::tp('type_produit'),
        'categorie_produit' => Mreq::tp('categorie_produit'),
        'id_produit'=> Mreq::tp('id_produit'),
        'id_technicien'=> Mreq::tp('id_technicien'),
        'serial_number'=>Mreq::tp('serial_number'),
         );

    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["id_client"] == NULL) {
        $empty_list .= "<li>Client</li>";
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
        
    if (date('Y-m-d', strtotime($posted_data['date_previs'])) < date('Y-m-d')) 
      {
        $control_date = " <li>Date prévisionnelle doit être supérieur ou égale à la date d'aujourd'hui</li>";
        $checker = 2;
    }
   
    if ($posted_data["type_produit"] == NULL) {
        $empty_list .= "<li>Type produit</li>";
        $checker = 1;
    }
    
    $empty_list .= "</ul>";
    
    if ($checker == 1) {
        exit("0#$empty_list");
    }
     if ($checker == 2) {
        exit("0#$control_date");
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
view::load_view('addticket');
?>