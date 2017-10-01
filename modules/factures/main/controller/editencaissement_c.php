<?php

if (MInit::form_verif('editencaissement', false)) {//If form is Posted do Action else rend empty form
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    //Listed data from posted form
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'designation' => Mreq::tp('designation'),
        'idfacture' => Mreq::tp('idfacture'),
        'montant' => Mreq::tp('montant'),
        'pj_id' => Mreq::tp('pj-id'),
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";



     if($posted_data['designation'] == NULL ){

            $empty_list .= "<li>Désignation</li>";
            $checker = 1;
        }
        
        if($posted_data['idfacture'] == NULL){

            $empty_list .= "<li>Facture</li>";
            $checker = 1;
        }
        
        if ($posted_data['montant'] == NULL) {

            $empty_list .= "<li>Montant</li>";
            $checker = 1;
        }

    if ($posted_data['type'] == NULL) {

        $empty_list .= "<li>Type</li>";
        $checker = 1;
    }



    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End Checker
    //Call Model
    $new_encaissement = new Mfacture($posted_data);

    $new_encaissement->id_encaissement = $posted_data['id'];
    //execute Edit returne false if error
    if ($new_encaissement->edit_encaissement()) {
        exit("1#" . $new_encaissement->log); //Green message
    } else {
        exit("0#" . $new_encaissement->log); //Red message
    }
    //Call View if no POST
} else {
    view::load_view('editencaissement');
}
?>