<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Controller EDIT Form
if (MInit::form_verif('editcommerciale', false)) {
    if (!MInit::crypt_tp('id', null, 'D')) {
        // returne message error red to client
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'nom' => Mreq::tp('nom'),
        'prenom' => Mreq::tp('prenom'),
        'is_glbt' => Mreq::tp('is_glbt'),
        'cin' => Mreq::tp('cin'),
        'rib' => Mreq::tp('rib'),
        'tel' => Mreq::tp('tel'),
        'email' => Mreq::tp('email'),
        'sexe' => Mreq::tp('sexe'),
        'adresse' => Mreq::tp('adresse'),
        'pj_id' => Mreq::tp('pj-id'),
        'photo_id' => Mreq::tp('photo-id'),

    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["nom"] == NULL) {
        $empty_list .= "<li>Nom</li>";
        $checker = 1;
    }
    if ($posted_data["prenom"] == NULL) {
        $empty_list .= "<li>Prénom</li>";
        $checker = 1;
    }
    if ($posted_data["is_glbt"] == NULL) {
        $empty_list .= "<li>Interne</li>";
        $checker = 1;
    }
    if ($posted_data["cin"] == NULL) {
        $empty_list .= "<li>CIN</li>";
        $checker = 1;
    }

    if ($posted_data["tel"] == NULL) {
        $empty_list .= "<li>Téléphone</li>";
        $checker = 1;
    }

    if ($posted_data["sexe"] == NULL) {
        $empty_list .= "<li>Sexe</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End check empty element
    $edit_commerciale = new  Mcommerciale($posted_data);

    //Set ID of row to update
    $edit_commerciale->id_commerciale = $posted_data['id'];
    $edit_commerciale->exige_pj = false;
    $edit_commerciale->exige_photo = false;

    //execute Update returne false if error
    if ($edit_commerciale->edit_commerciale()) {

        exit("1#" . $edit_commerciale->log);
    } else {

        exit("0#" . $edit_commerciale->log);
    }


}

//No form posted show view
view::load_view('editcommerciale');


?>