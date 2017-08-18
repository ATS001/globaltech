<?php

defined('_MEXEC') or die;
if (MInit::form_verif('adduhf_vhf_clt_mobile', false)) {

    $posted_data = array(
        'type_client'        => "Mobile",
        'station_base'       => Mreq::tp('station_base'),
        'marque'             => Mreq::tp('marque'),
        'modele'             => Mreq::tp('modele'),
        'num_serie'          => Mreq::tp('num_serie'),
        'matricule_vehicule' => Mreq::tp('matricule_vehicule'),
        'active'             => Mreq::tp('active'),
        'photo_id'           => Mreq::tp('photo_id'), //Array
        'photo_titl'         => Mreq::tp('photo_titl'), //Array
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

   
    if ($posted_data['active'] == NULL) {

        $empty_list .= "<li>Active</li>";
        $checker = 1;
    }

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
    
    if ($posted_data['matricule_vehicule'] == NULL) {

        $empty_list .= "<li>Matricule de vehicule</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    $new_uhf_vhf_clients = new Muhf_vhf_clients($posted_data);

    //execute Insert returne false if error
    if ($new_uhf_vhf_clients->save_new_uhf_vhf_clt_mobile()) {

        echo("1#" . $new_uhf_vhf_clients->log);
    } else {

        echo("0#" . $new_uhf_vhf_clients->log);
    }
} else {

    view::load('uhf_vhf_stations', 'adduhf_vhf_clt_mobile');
}


