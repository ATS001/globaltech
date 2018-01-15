<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 09-01-2018
//Controller ADD Form
if (MInit::form_verif('addcommissions', false)) {

    $posted_data = array(
        'credit' => Mreq::tp('credit'),
        'objet' => Mreq::tp('objet'),
        'type'=>'Manuelle',
        'id_commerciale' => Mreq::tp('id_commerciale'),

    );

//var_dump($posted_data);
    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["credit"] == NULL OR $posted_data["credit"] <= 0) {
        $empty_list .= "<li>Montant Incorrect</li>";
        $checker = 1;
    }
    if ($posted_data["objet"] == NULL ) {
        $empty_list .= "<li>Objet</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    $posted_data["type"]="B";

    //End check empty element
    $new_commission = new  Mcommission($posted_data);


    //execute Insert returne false if error
    if ($new_commission->save_new_commission()) {

        exit("1#" . $new_commission->log);
    } else {

        exit("0#" . $new_commission->log);
    }


}

//No form posted show view
view::load_view('addcommissions');


?>