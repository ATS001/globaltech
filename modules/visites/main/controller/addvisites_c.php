<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//Controller ADD Form
if (MInit::form_verif('addvisites', false)) {

    $posted_data = array(
        'commerciale' => Mreq::tp('commerciale'),
        'raison_sociale' => Mreq::tp('raison_sociale'),
        'nature_visite' => Mreq::tp('nature_visite'),
        'objet_visite' => Mreq::tp('objet_visite'),
          'id_client' => Mreq::tp('id_client'),
          'id_prospects' => Mreq::tp('id_prospects'),
        'date_visite' => Mreq::tp('date_visite'),
        'interlocuteur' => Mreq::tp('interlocuteur'),
        'fonction_interloc' => Mreq::tp('fonction_interloc'),
        'coordonees_interloc' => Mreq::tp('coordonees_interloc'),
        'commentaire' => Mreq::tp('commentaire')
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["commerciale"] == NULL) {
        $empty_list .= "<li>Commerciale</li>";
        $checker = 1;
    }
    if ($posted_data["raison_sociale"] == NULL) {
        $empty_list .= "<li>Raison sociale</li>";
        $checker = 1;
    }
    if ($posted_data["nature_visite"] == NULL) {
        $empty_list .= "<li>Client / Prospect</li>";
        $checker = 1;
    }
    if ($posted_data["objet_visite"] == NULL) {
        $empty_list .= "<li>Objet Visite</li>";
        $checker = 1;
    }
    if ($posted_data["date_visite"] == NULL) {
        $empty_list .= "<li>Date Visite</li>";
        $checker = 1;
    }
    if ($posted_data["interlocuteur"] == NULL) {
        $empty_list .= "<li>Interlocuteur</li>";
        $checker = 1;
    }
    if ($posted_data["fonction_interloc"] == NULL) {
        $empty_list .= "<li>Fonction Interlocuteur</li>";
        $checker = 1;
    }
    if ($posted_data["coordonees_interloc"] == NULL) {
        $empty_list .= "<li>Coordonnées Interlocuteur</li>";
        $checker = 1;
    }
    if ($posted_data["commentaire"] == NULL) {
        $empty_list .= "<li>Commentaire</li>";
        $checker = 1;
    }



    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }



    //End check empty element
    $new_visites = new Mvisites($posted_data);



    //execute Insert returne false if error
    if ($new_visites->save_new_visites()) {

        exit("1#" . $new_visites->log);
    } else {

        exit("0#" . $new_visites->log);
    }
}

//No form posted show view
view::load_view('addvisites');
?>
