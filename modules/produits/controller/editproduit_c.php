<?php

if (MInit::form_verif('editproduit', false)) {//If form is Posted do Action else rend empty form
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    //Listed data from posted form
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'designation' => Mreq::tp('designation'),
        'stock_min' => Mreq::tp('stock_min'),
        'prix_vente' => Mreq::tp('prix_vente'),
        'idcategorie' => Mreq::tp('idcategorie'),
        'iduv' => Mreq::tp('iduv'),
        'idtype' => Mreq::tp('idtype'),
        'id_entrepot' => Mreq::tp('id_entrepot'),
        'exige-sn' => Mreq::tp('exige-sn')
    );

    //Check if array have empty element return list
    //for acceptable empty field do not put here



    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";


    if ($posted_data['designation'] == NULL) {

        $empty_list .= "<li>Désignation</li>";
        $checker = 1;
    }


    if ($posted_data['idcategorie'] == NULL OR ! is_numeric($posted_data['idcategorie'])) {

        $empty_list .= "<li>Catégorie</li>";
        $checker = 1;
    }

    if ($posted_data['iduv'] == NULL OR ! is_numeric($posted_data['iduv'])) {

        $empty_list .= "<li>Unité de vente</li>";
        $checker = 1;
    }

    if ($posted_data['idtype'] == NULL OR ! is_numeric($posted_data['idtype'])) {

        $empty_list .= "<li>Type produit</li>";
        $checker = 1;
    }
    if ($posted_data['id_entrepot'] == NULL) {

        $empty_list .= "<li>Entrepôt</li>";
        $checker = 1;
    }
    

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }


    //End Checker
    //Call Model
    $new_produit = new Mproduit($posted_data);

    $new_produit->id_produit = $posted_data['id'];
    //execute Edit returne false if error
    if ($new_produit->edit_produit()) {
        exit("1#" . $new_produit->log); //Green message
    } else {
        exit("0#" . $new_produit->log); //Red message
    }
    //Call View if no POST
} else {
    view::load('produits', 'editproduit');
}
?>