<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();

if (MInit::form_verif('adddecharge', false)) {
     $posted_data = array(
        'id' => Mreq::tp('id'),
        'decharge_id' => Mreq::tp('decharge-id')
    );


    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["decharge_id"] == NULL) {
        $empty_list .= "<li>Décharge</li>";
        $checker = 1;
    }
  
    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End check empty element
    $commission = new Mcommission($posted_data);

    //$commission->exige_pj       = false;

    //Set ID of row to update
    $commission->id_commission = $posted_data['id'];
//execute Insert returne false if error
    if ($commission->add_decharge()) {

        exit("1#" . $commission->log);
    } else {

        exit("0#" . $commission->log);
    }


}

//No form posted show view
view::load_view('adddecharge');
?>