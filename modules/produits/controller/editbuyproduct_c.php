<<<<<<< HEAD
<?php

if (MInit::form_verif('editbuyproduct', false)) {//If form is Posted do Action else rend empty form
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    //Listed data from posted form
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'qte' => Mreq::tp('qte'),
        'prix_achat' => Mreq::tp('prix_achat'),
        'prix_vente' => Mreq::tp('prix_vente'),
        'idproduit' => Mreq::tp('idproduit'),
        'date_achat' => Mreq::tp('date_achat'),
        'date_validite' => Mreq::tp('date_validite'),
       
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

   
    
     if ($posted_data['qte'] == NULL OR !is_numeric($posted_data['qte'])){

        $empty_list .= "<li>Quantité</li>";
        $checker = 1;
    }

    if($posted_data['prix_achat'] == NULL OR !is_numeric($posted_data['prix_achat'])){

        $empty_list .= "<li>Prix d'achat</li>";
        $checker = 1;
    }
    
    if($posted_data['prix_vente'] == NULL OR !is_numeric($posted_data['prix_vente'])){

        $empty_list .= "<li>prix de vente</li>";
        $checker = 1;
    }
    
    if ($posted_data['idproduit'] == NULL) {

        $empty_list .= "<li>Produit</li>";
        $checker = 1;
    }
    
    if ($posted_data['date_achat'] == NULL) {

        $empty_list .= "<li>Date d'achat</li>";
        $checker = 1;
    }
    
    if ($posted_data['date_validite'] == NULL) {

        $empty_list .= "<li>date fin de validité</li>";
        $checker = 1;
    }
    
    

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

   
    //End Checker
    //Call Model
    $new_achat = new Machat($posted_data);
    
    $new_achat->id_achat = $posted_data['id'];
    //execute Edit returne false if error
    if ($new_achat->edit_achat_produit()) {
        exit("1#" . $new_achat->log); //Green message
    } else {
        exit("0#" . $new_achat->log); //Red message
    }
//Call View if no POST
} else {
    view::load('produits', 'editbuyproduct');
}
?>
=======
    <?php

    if (MInit::form_verif('editbuyproduct', false)) {//If form is Posted do Action else rend empty form
        //Check if id is been the correct id compared with idc
        if (!MInit::crypt_tp('id', null, 'D')) {
            //returne message error red to client 
            exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
        }
        //Listed data from posted form
        $posted_data = array(
            'id' => Mreq::tp('id'),
            'qte' => Mreq::tp('qte'),
            'prix_achat' => Mreq::tp('prix_achat'),
            'prix_vente' => Mreq::tp('prix_vente'),
            'idproduit' => Mreq::tp('idproduit'),
            'date_achat' => Mreq::tp('date_achat'),
            'date_validite' => Mreq::tp('date_validite'),
           
        );


        //Check if array have empty element return list
        //for acceptable empty field do not put here

        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

       
        
         if ($posted_data['qte'] == NULL OR !is_numeric($posted_data['qte'])){

            $empty_list .= "<li>Quantité</li>";
            $checker = 1;
        }

        if($posted_data['prix_achat'] == NULL OR !is_numeric($posted_data['prix_achat'])){

            $empty_list .= "<li>Prix d'achat</li>";
            $checker = 1;
        }
        
        if($posted_data['prix_vente'] == NULL OR !is_numeric($posted_data['prix_vente'])){

            $empty_list .= "<li>prix de vente</li>";
            $checker = 1;
        }
        
        if ($posted_data['idproduit'] == NULL) {

            $empty_list .= "<li>Produit</li>";
            $checker = 1;
        }
        
        if ($posted_data['date_achat'] == NULL) {

            $empty_list .= "<li>Date d'achat</li>";
            $checker = 1;
        }
        
        if ($posted_data['date_validite'] == NULL) {

            $empty_list .= "<li>date fin de validité</li>";
            $checker = 1;
        }
        
        

        $empty_list .= "</ul>";
        if ($checker == 1) {
            exit("0#$empty_list");
        }

       
        //End Checker
        //Call Model
        $new_achat = new Machat($posted_data);
        
        $new_achat->id_achat = $posted_data['id'];
        //execute Edit returne false if error
        if ($new_achat->edit_achat_produit()) {
            exit("1#" . $new_achat->log); //Green message
        } else {
            exit("0#" . $new_achat->log); //Red message
        }
    //Call View if no POST
    } else {
        view::load('produits', 'editbuyproduct');
    }
    ?>
>>>>>>> refs/remotes/origin/ayoub
