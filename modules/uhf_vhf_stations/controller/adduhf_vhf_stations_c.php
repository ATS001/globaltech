<?php

if (MInit::form_verif('adduhf_vhf_stations', false)) {
    $posted_data = array(
        'prm'           => Mreq::tp('prm'),
        'site'          => Mreq::tp('site'),
        'longi'         => Mreq::tp('longi'),
        'latit'         => Mreq::tp('latit'),
        'ville'         => Mreq::tp('ville'),
        'modele'        => Mreq::tp('modele'),
        'hauteur'       => Mreq::tp('hauteur'),
        'puissance'     => Mreq::tp('puissance'),
        'frequence'     => Mreq::tp('frequence'),
        'modulation'    => Mreq::tp('modulation'),
        'porte_antenne' => Mreq::tp('porte_antenne'),
        'type_station'  => Mreq::tp('type_station'),
        'date_visite'   => Mreq::tp('date_visite'),        
        'pj_id'         => Mreq::tp('pj-id'),
        'photo_id'      => Mreq::tp('photo_id'), //Array
        'photo_titl'    => Mreq::tp('photo_titl'), //Array
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if ($posted_data['prm'] == NULL) {

        $empty_list .= "<li>Permissionnaire</li>";
        $checker = 1;
    }
    if ($posted_data['site'] == NULL) {

        $empty_list .= "<li>Nom du site</li>";
        $checker = 1;
    }
    if ($posted_data['longi'] == NULL OR ! is_numeric($posted_data['longi'])) {

        $empty_list .= "<li>Longitude invalide</li>";
        $checker = 1;
    }
    if ($posted_data['latit'] == NULL OR ! is_numeric($posted_data['latit'])) {

        $empty_list .= "<li>Latitude invalide</li>";
        $checker = 1;
    }
    if ($posted_data['ville'] == NULL) {

        $empty_list .= "<li>Ville</li>";
        $checker = 1;
    }

    if ($posted_data['modele'] == NULL) {

        $empty_list .= "<li>Modèle</li>";
        $checker = 1;
    }

    if ($posted_data['hauteur'] == NULL OR ! is_numeric($posted_data['hauteur'])) {

        $empty_list .= "<li>Hauteur invalide</li>";
        $checker = 1;
    }
    if ($posted_data['puissance'] == NULL  OR ! is_numeric($posted_data['puissance'])) {

        $empty_list .= "<li>Puissance invalide</li>";
        $checker = 1;
    }
    if ($posted_data['frequence'] == NULL ) {

        $empty_list .= "<li>Fréquence</li>";
        $checker = 1;
    }
    if ($posted_data['modulation'] == NULL) {

        $empty_list .= "<li>Modulation</li>";
        $checker = 1;
    }

    if ($posted_data['type_station'] == NULL) {

        $empty_list .= "<li>Type de station</li>";
        $checker = 1;
    }

    if ($posted_data['porte_antenne'] == NULL OR ! is_numeric($posted_data['porte_antenne'])) {

        $empty_list .= "<li>Portée d'antenne invalide</li>";
        $checker = 1;
    }
    
    if($posted_data['date_visite'] == NULL){

      $empty_list .= "<li>Date de visite</li>";
      $checker = 1;
    }
  
    if ($posted_data['pj_id'] == null) {
        $empty_list .= '<li>Ajoutez le formulaire scanné';
        $checker = 1;
    }
    
   
    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End check empty element


    $new_uhf_vhf_station = new Muhf_vhf_stations($posted_data);
    $new_uhf_vhf_station->exige_pj = true;

    //execute Insert returne false if error
    if ($new_uhf_vhf_station->save_new_uhf_vhf_stations()) {
        exit("1#" . $new_uhf_vhf_station->log); //Green message
    } else {
        exit("0#" . $new_uhf_vhf_station->log); //Red Message
    }
} else {
    //call form if no post
    view::load('uhf_vhf_stations', 'adduhf_vhf_stations');
}
?>