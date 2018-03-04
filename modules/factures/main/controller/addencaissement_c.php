
    <?php
   
    defined('_MEXEC') or die;
    if (MInit::form_verif('addencaissement', false)) {

        $posted_data = array(       
            'designation' => Mreq::tp('designation'),
            'idfacture' => Mreq::tp('idfacture'),
            'mode_payement' => Mreq::tp('mode_payement'),
            'ref_payement' => Mreq::tp('ref_payement'),
            'montant' => Mreq::tp('montant'),
            'pj_id'          => Mreq::tp('pj-id'),
            );
        
        
        //Check if array have empty element return list
        //for acceptable empty field do not put here

        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

           
         
        if($posted_data['designation'] == NULL ){

            $empty_list .= "<li>Désignation</li>";
            $checker = 1;
        }
        
        if($posted_data['mode_payement'] == NULL ){

            $empty_list .= "<li>Mode de payement</li>";
            $checker = 1;
        }
       if( $posted_data['mode_payement'] != 'Espèce'){
        if($posted_data['ref_payement'] == NULL ){

            $empty_list .= "<li>Référence payement</li>";
            $checker = 1;
        }
       }
        
        if($posted_data['idfacture'] == NULL){

            $empty_list .= "<li>Facture</li>";
            $checker = 1;
        }
        
        if ($posted_data['montant'] == NULL) {

            $empty_list .= "<li>Montant</li>";
            $checker = 1;
        }
                   
      
        $empty_list .= "</ul>";
        if ($checker == 1) {
           exit("0#$empty_list");
        }
       
        $new_encaissement = new Mfacture($posted_data);
        $new_encaissement->exige_pj=false;

        $fact = new Mfacture();
        $fact->id_facture = Mreq::tp('idfacture');
        $fact->get_commerciale_devis();
//var_dump(Mreq::tp('idfacture'));
//var_dump($fact->compte_commercial_info['commission']);
        //execute Insert returne false if error
        if ($new_encaissement->save_new_encaissement()) {
            if($fact->compte_commercial_info['commission']!=0){
                
                if($new_encaissement->credit_compte_commerciale()){

                exit("1#" . $new_encaissement->log); //Green message

                }else {

                exit("0#" . $new_encaissement->log); //Red message

                } 
            } 
            exit("1#" . $new_encaissement->log); //Green message
        }else {

            exit("0#" . $new_encaissement->log); //Red message
        }

    } else {
        view::load_view('addencaissement');
    }
