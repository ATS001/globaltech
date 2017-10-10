<?php

    if (MInit::form_verif('editecategorie_produit', false)) {//If form is Posted do Action else rend empty form
        //Check if id is been the correct id compared with idc
        if (!MInit::crypt_tp('id', null, 'D')) {
            //returne message error red to client 
            exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
        }
        //Listed data from posted form
        $posted_data = array(
            'id' => Mreq::tp('id'),
            'categorie_produit' => Mreq::tp('categorie_produit'),
           
        );

    //Check if array have empty element return list
        //for acceptable empty field do not put here



        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
        
        if ($posted_data['categorie_produit'] == NULL) {
            $empty_list .= "<li>Catégorie de produit</li>";
         $checker = 1;
       }
          

        $empty_list .= "</ul>";
        if ($checker == 1) {
            exit("0#$empty_list");
        }

        //End Checker
        //Call Model
        $new_categorie_produit = new Mcategorie_produit($posted_data);
        
        $new_categorie_produit->id_categorie_produit = $posted_data['id'];
        //execute Edit returne false if error
        if ($new_categorie_produit->edit_categorie_produit()) {
            exit("1#" . $new_categorie_produit->log); //Green message
        } else {
            exit("0#" . $new_categorie_produit->log); //Red message
        }
    //Call View if no POST
    } else {
        view::load_view('editecategorie_produit');

    }
?>