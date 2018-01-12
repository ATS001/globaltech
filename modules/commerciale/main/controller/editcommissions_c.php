<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 10-01-2018
//Controller EDIT Form
if (MInit::form_verif('editcommissions', false)) {
    if (!MInit::crypt_tp('id', null, 'D')) {
        // returne message error red to client
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'credit' => Mreq::tp('credit'),
        'objet' => Mreq::tp('objet'),
        'methode_payement' => Mreq::tp('methode_payement'),
        //'id_commerciale' => Mreq::tp('id_commerciale'),


    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";


    if ($posted_data["credit"] == NULL OR $posted_data["credit"] <= 0) {
        $empty_list .= "<li>Montant Incorrect</li>";
        $checker = 1;
    }
    if ($posted_data["objet"] == NULL) {
        $empty_list .= "<li>Objet</li>";
        $checker = 1;
    }
/*
    if ($posted_data["methode_payement"] == NULL) {
        $empty_list .= "<li>Méthode de paiement</li>";
        $checker = 1;
    }
*/

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End check empty element
    $edit_commission = new  Mcommission($posted_data);

    //Set ID of row to update
    $edit_commission->id_commission = $posted_data['id'];

    //execute Update returne false if error
    if ($edit_commission->edit_commission()) {

        exit("1#" . $edit_commission->log);
    } else {

        exit("0#" . $edit_commission->log);
    }


}

//No form posted show view
view::load_view('editcommissions');


?>