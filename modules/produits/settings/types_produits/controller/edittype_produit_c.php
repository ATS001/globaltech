<?php

if (MInit::form_verif('edittype_produit', false)) {//If form is Posted do Action else rend empty form
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    //Listed data from posted form
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'type_produit' => Mreq::tp('type_produit'),
       
    );

//Check if array have empty element return list
    //for acceptable empty field do not put here



    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    
    if ($posted_data['type_produit'] == NULL) {
        $empty_list .= "<li>Type_produit</li>";
     $checker = 1;
   }
      

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End Checker
    //Call Model
    $new_type_produit = new Mtype_produit($posted_data);
    
    $new_type_produit->id_type_produit = $posted_data['id'];
    //execute Edit returne false if error
    if ($new_type_produit->edit_type_produit()) {
        exit("1#" . $new_type_produit->log); //Green message
    } else {
        exit("0#" . $new_type_produit->log); //Red message
    }
//Call View if no POST
} else {
    view::load('produits/settings/types_produits', 'edittype_produit');
}
?>