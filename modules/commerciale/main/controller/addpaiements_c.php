<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();

if (MInit::form_verif('addpaiements', false)) {
   
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'debit' => Mreq::tp('debit'),
        'reste' => Mreq::tp('reste'),
        'id_commerciale' => Mreq::tp('id_commerciale'),
        'methode_payement' => Mreq::tp('methode_payement'),
        'date_debit' => Mreq::tp('date_debit'),
        'objet'=>Mreq::tp('objet'),
        'pj_id' => Mreq::tp('pj-id')
    );


    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["debit"] == NULL) {
        $empty_list .= "<li>Montant</li>";
        $checker = 1;
    }

    if ($posted_data["objet"] == NULL) {
        $empty_list .= "<li>Objet</li>";
        $checker = 1;
    }


    if ($posted_data["methode_payement"] == NULL) {
        $empty_list .= "<li>Méthode de paiement</li>";
        $checker = 1;
    }

    if ($posted_data["date_debit"] == NULL) {
        $empty_list .= "<li>Date de paiement</li>";
        $checker = 1;
    }

    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    //Add posted data fields verificator here


    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End check empty element
    $commission = new Mcommission($posted_data);

    //$commission->exige_pj       = false;

    //Set ID of row to update
    $commission->id_commission = $posted_data['id'];

    if ($posted_data["debit"] > $posted_data["reste"]) {
        $ctrl_depassement = " <ul>Le montant doit être inférieur ou égale à <b>".$posted_data["reste"] ."</b> Franc CFA</ul>";
        $checker = 2;
    }

    if ($checker == 2) {
        exit("0#$ctrl_depassement");
    }

    if ($posted_data["debit"] < 0) {
        $ctrl_negatif = " <ul>Le montant doit être positif</ul>";
        $checker = 3;
    }

    if ($checker == 3) {
        exit("0#$ctrl_negatif");
    }

    //$etat = $new_aemploi->aemploi_info['etat'];
    // $etat = Msetting::get_set('etat_aemploi', '');

    if ($posted_data["debit"] == $posted_data["reste"]) {
        $etat = Msetting::get_set('commission', 'payer_total');
        if ($commission->payer_commission()) {
            if ($commission->valid_commission($etat, $validator = NULL)) {

                exit("1#" . $commission->log); //Green message
            } else {
                exit("1#" . $commission->log);
            }
        } else {

            exit("0#" . $commission->log);
        }
    }

    $etat = Msetting::get_set('commission', 'payer_part');
   
    if ($posted_data["debit"] < $posted_data["reste"]) {

        $etat = Msetting::get_set('commission', 'payer_part');
        if ($commission->payer_commission()) {
            if ($commission->valid_commission($etat, $validator = NULL)) {

                exit("1#" . $commission->log); //Green message
            } else {
                exit("1#" . $commission->log);
            }
        } else {

            exit("0#" . $commission->log);
        }
    }

}

//No form posted show view
view::load_view('addpaiements');
?>