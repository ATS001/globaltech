<?php

if (MInit::form_verif('editunite_vente', false)) {//If form is Posted do Action else rend empty form
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    //Listed data from posted form
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'unite_vente' => Mreq::tp('unite_vente'),
       
    );

//Check if array have empty element return list
    //for acceptable empty field do not put here



    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    
    if ($posted_data['unite_vente'] == NULL) {
        $empty_list .= "<li>Unité de vente</li>";
     $checker = 1;
   }
      

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End Checker
    //Call Model
    $new_unite_vente = new Munite_vente($posted_data);
    
    $new_unite_vente->id_unite_vente = $posted_data['id'];
    //execute Edit returne false if error
    if ($new_unite_vente->edit_unite_vente()) {
        exit("1#" . $new_unite_vente->log); //Green message
    } else {
        exit("0#" . $new_unite_vente->log); //Red message
    }
//Call View if no POST
} else {
    view::load('produits/settings/unites_vente', 'editunite_vente');
}
?>