
    <?php

    defined('_MEXEC') or die;
    if (MInit::form_verif('addcomplement', false)) {

        $posted_data = array(       
            'designation' => Mreq::tp('designation'),
            'idfacture' => Mreq::tp('idfacture'),
            'montant' => Mreq::tp('montant'),
            'type' => Mreq::tp('type'),
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

       
        $new_complement = new Mfacture($posted_data);
        
        
        //execute Insert returne false if error
        if ($new_complement->save_new_complement()) {

            echo("1#" . $new_complement->log);
        } else {

            echo("0#" . $new_complement->log);
        }
    } else {
        view::load_view('addcomplement');
    }
