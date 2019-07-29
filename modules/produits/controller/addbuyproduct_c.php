
    <?php

    defined('_MEXEC') or die;
    if (MInit::form_verif('addbuyproduct', false)) {

        $posted_data = array(       
            'qte' => Mreq::tp('qte'),
            'prix_achat' => Mreq::tp('prix_achat'),
            'prix_vente' => Mreq::tp('prix_vente'),
            'idproduit' => Mreq::tp('idproduit'),
            'date_achat' => Mreq::tp('date_achat'),
            'date_validite' => Mreq::tp('date_validite'),
            'pj_id' => Mreq::tp('pj-id')
        );
        
        $produit = new Mproduit();
        $produit->id_produit = Mreq::tp('idproduit');
        $produit->get_produit();
        

        //Check if array have empty element return list
        //for acceptable empty field do not put here

        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
        
        $empty_list_prix = "** Attention ** \n<ul>";
           
         
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
        
       if($posted_data['prix_vente'] < $posted_data['prix_achat']){

            $empty_list_prix .= "<li>Le prix de vente ne doit pas être inférieur au prix d'achat </li>";
            $checker = 2;
        } 
        if($posted_data['pj_id'] == NULL AND $produit->produit_info['exige-sn'] == "Oui"){

            $empty_list .= "<li>Fichier des numéros de séries </li>";
            $checker = 1;
        }
        
        

        $empty_list .= "</ul>";
        if ($checker == 1) {
            exit("0#$empty_list");
        }
        
        $empty_list_prix .= "</ul>";
        if ($checker == 2) {
            exit("0#$empty_list_prix");
        }

       
        $new_achat = new Machat($posted_data);
        
        
        //execute Insert returne false if error
        if ($new_achat->save_new_achat_produit($posted_data['pj_id'])) {

            echo("1#" . $new_achat->log);
        } else {

            echo("0#" . $new_achat->log);
        }
    } else {
        view::load_view('addbuyproduct');
    }
