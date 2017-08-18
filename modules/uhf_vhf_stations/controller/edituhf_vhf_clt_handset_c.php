<?php

defined('_MEXEC') or die;
if (MInit::form_verif('edituhf_vhf_clt_handset', false)) {

    $posted_data = array(
        'id'           => Mreq::tp('id') ,
        'station_base' => Mreq::tp('station_base'),
        'marque'       => Mreq::tp('marque'),
        'modele'       => Mreq::tp('modele'),
        'num_serie'    => Mreq::tp('num_serie'),
        'active'       => Mreq::tp('active'),
        'photo_id'     => Mreq::tp('photo_id'), //Array
        'photo_titl'   => Mreq::tp('photo_titl'), //Array
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data['marque'] == NULL) {

        $empty_list .= "<li>Marque</li>";
        $checker = 1;
    }
    if ($posted_data['modele'] == NULL) {

        $empty_list .= "<li>modele</li>";
        $checker = 1;
    }
    
     if ($posted_data['num_serie'] == NULL) {

        $empty_list .= "<li>numéro de série</li>";
        $checker = 1;
    }
    
     if ($posted_data['active'] == NULL) {

        $empty_list .= "<li>Active</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    $uhf_vhf_clients = new Muhf_vhf_clients($posted_data);
    $uhf_vhf_clients->id_uhf_vhf_clients = $posted_data['id'];
    
    //execute Insert returne false if error
    if ($uhf_vhf_clients->edit_uhf_vhf_clt_handset()) {

        echo("1#" . $uhf_vhf_clients->log);
    } else {

        echo("0#" . $uhf_vhf_clients->log);
    }
}