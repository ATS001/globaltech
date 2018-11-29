<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Controller ADD Form
if (MInit::form_verif('addcommerciale', false)) {

    $posted_data = array(

        'nom' => Mreq::tp('nom'),
        'prenom' => Mreq::tp('prenom'),
        'is_glbt' => Mreq::tp('is_glbt'),
        'cin' => Mreq::tp('cin'),
        'rib' => Mreq::tp('rib'),
        'tel' => Mreq::tp('tel'),
        'email' => Mreq::tp('email'),
        'sexe' => Mreq::tp('sexe'),
        'adresse' => Mreq::tp('adresse'),
        'service' => MReq::tp('service'),
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

     if ($posted_data["is_glbt"] == 'Non' AND  $posted_data["service"] != '') {
        $empty_list2 = "<ul>Commerciale externe , interdiction de choisir un service</ul>";
        $checker = 2;
    }
    
    
    if ($posted_data["is_glbt"] == 'Oui' AND  $posted_data["service"] == '') {
        $empty_list3 = "<ul>Commerciale interne , veuillez choisir un service </ul>";
        $checker = 3;
    }
    
    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }
     if ($checker == 2) {
        exit("0#$empty_list2");
    }
    
    if ($checker == 3) {
        exit("0#$empty_list3");
    }


    //End check empty element
    $new_commerciale = new  Mcommerciale($posted_data);
    $new_commerciale->exige_pj=false;
    $new_commerciale->exige_photo=false;

    //execute Insert returne false if error
    if ($new_commerciale->save_new_commerciale()) {

        exit("1#" . $new_commerciale->log);
    } else {

        exit("0#" . $new_commerciale->log);
    }


}

//No form posted show view
view::load_view('addcommerciale');


?>