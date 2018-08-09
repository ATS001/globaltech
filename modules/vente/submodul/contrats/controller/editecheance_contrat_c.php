<?php
  
defined('_MEXEC') or die;

if (MInit::form_verif('editecheance_contrat', false)) {

    if (!MInit::crypt_tp('id', null, 'D')) {
        // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
      
  
    $posted_data = array(
        'id'             => Mreq::tp('id'),
        'tkn_frm'        => Mreq::tp('tkn_frm'),
        'checker_tkn_frm'=> Mreq::tp('checker_tkn_frm'),
        //'idcontrat' => Mreq::tp('idcontrat'),
        'date_echeance'  => Mreq::tp('date_echeance'),
        'montant'        => Mreq::tp('montant') ,
        'commentaire'    => Mreq::tp('commentaire'),
    );

    $date_fin=Mreq::tp('dat_fn');
    $date_effet=Mreq::tp('dat_ef');
    $dev=Mreq::tp('dev');
    
    $echeance=new Mcontrat();
    $date_echeance=$echeance->verif_date_echeance($posted_data['tkn_frm'],date('Y-m-d',strtotime($posted_data['date_echeance'])),Mreq::tp('id'));
    

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if ($posted_data['checker_tkn_frm'] != MInit::cryptage($posted_data['tkn_frm'], 1)) {

        $empty_list .= "<li>Le token Form est invalid</li>";
        $checker = 1;
    }
//    if ($posted_data['idcontrat'] == NULL) {
//
//        $empty_list .= "<li>Contrat</li>";
//        $checker = 1;
//    }
    if ($posted_data['date_echeance'] == NULL) {

        $empty_list .= "<li>Date d\'échéance</li>";
        $checker = 1;
    }
    if($posted_data['montant'] == NULL OR $posted_data['montant'] == '0'){

        $empty_list .= "<li>Montant TTC à facturer</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    if(date('Y-m-d', strtotime($posted_data['date_echeance'])) > date('Y-m-d', strtotime($date_fin))  or date('Y-m-d', strtotime($posted_data['date_echeance'])) < date('Y-m-d', strtotime($date_effet)) )
    {

            $date_ech = "<ul>La date d'échéance doit être supérieur de la date d'effet et  inférieur de la date de fin !!!</ul>" ;
            $checker = 2;
    }

    if($checker == 2)
    {
            exit("0#$date_ech");
    }

    if( $date_echeance == TRUE)
    {
            $date= "<ul>Date d'échéance existe déjà</ul>";
            $checker = 3;
    }
    if($checker == 3)
    {
            exit("0#$date");
    }
    //End check empty element


        $exist_echeance = new Mcontrat($posted_data);
        $exist_echeance->id_echeance_contrat = $posted_data['id'];
        $exist_echeance->get_echeance_contrat();
        $mnt_before_update=$exist_echeance->echeance_contrat_info['montant'];
        //var_dump($mnt_before_update);

        $mnt_devis=$dev;
        //var_dump($mnt_devis);

        $new_contrat2 = new Mcontrat($posted_data);
        $new_contrat2->get_total_echeances(Mreq::tp('tkn_frm'));
        $tot_ech=$new_contrat2->Shw_type('montant_total',1);
        //var_dump($tot_ech);

        $mnt_ech= $tot_ech - $mnt_before_update + $posted_data['montant'];
        //var_dump($mnt_ech);
        

        if ($mnt_devis < $mnt_ech) {
            $montant_echeance1 = "<ul>Le montant total du devis est inférieur au montant total des échéances, Il faut minimiser le montant !!!</ul>";
            $checker = 4;
        }

        if ($checker == 4) {
            exit("0#$montant_echeance1");
        }




    

    //execute Insert returne false if error
    if ($exist_echeance->edit_echeance($posted_data['tkn_frm'])) {

        exit("1#" . $exist_echeance->log);
    } else {

        exit("0#" . $exist_echeance->log);
    }
}

    view::load_view('editecheance_contrat');

