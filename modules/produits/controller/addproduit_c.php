
    <?php

    defined('_MEXEC') or die;
    if (MInit::form_verif('addproduit', false)) {

        $posted_data = array(
            'ref' => Mreq::tp('ref'),
            'designation' => Mreq::tp('designation'),
           
            'stock_min' => Mreq::tp('stock_min'),
            'idcategorie' => Mreq::tp('idcategorie'),
            'iduv' => Mreq::tp('iduv'),
            'idtype' => Mreq::tp('idtype'),
        );

        //Check if array have empty element return list
        //for acceptable empty field do not put here

        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

        if ($posted_data['ref'] == NULL) {

            $empty_list .= "<li>Référence</li>";
            $checker = 1;
        }
        
         if ($posted_data['designation'] == NULL) {

            $empty_list .= "<li>Désignation</li>";
            $checker = 1;
        }
      
           
        if ($posted_data['idcategorie'] == NULL) {

            $empty_list .= "<li>Catégorie</li>";
            $checker = 1;
        }
        
        if ($posted_data['iduv'] == NULL) {

            $empty_list .= "<li>Unité de vente</li>";
            $checker = 1;
        }
        
        if ($posted_data['idtype'] == NULL) {

            $empty_list .= "<li>Type produit</li>";
            $checker = 1;
        }
        
        

        $empty_list .= "</ul>";
        if ($checker == 1) {
            exit("0#$empty_list");
        }

        //End check empty element


    $new_produit = new Mproduit($posted_data);


        //execute Insert returne false if error
        if ($new_produit->save_new_produit()) {

            echo("1#" . $new_produit->log);
        } else {

            echo("0#" . $new_produit->log);
        }
    } else {
        view::load('produits', 'addproduit');
    }
