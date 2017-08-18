<?php

if (MInit::form_verif('edituhf_vhf_clt_fixe', false)) {//If form is Posted do Action else rend empty form
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    //Listed data from posted form
    $posted_data = array(
        'id'           => Mreq::tp('id'),
        'station_base' => Mreq::tp('station_base'),
        'longi'        => Mreq::tp('longi'),
        'latit'        => Mreq::tp('latit'),
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

    if ($posted_data['longi'] == NULL OR ! is_numeric($posted_data['longi'])) {

        $empty_list .= "<li>Longitude</li>";
        $checker = 1;
    }

    if ($posted_data['latit'] == NULL OR ! is_numeric($posted_data['latit'])) {

        $empty_list .= "<li>Latitude</li>";
        $checker = 1;
    }

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

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }
    
    //Call Model
    $uhf_vhf_clients = new Muhf_vhf_clients($posted_data);

     $uhf_vhf_clients->id_uhf_vhf_clients = $posted_data['id'];
    //execute Edit returne false if error
    if ( $uhf_vhf_clients->edit_uhf_vhf_clt_fixe()) {
        exit("1#" .  $uhf_vhf_clients->log); //Green message
    } else {
        exit("0#" .  $uhf_vhf_clients->log); //Red message
    }
//Call View if no POST
} else {
    view::load('uhf_vhf_stations', 'edituhf_vhf_clt_fixe');
}
?>