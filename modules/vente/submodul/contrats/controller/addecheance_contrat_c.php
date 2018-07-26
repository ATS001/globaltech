<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('addecheance_contrat',false))
{
	$posted_data = array(
		'tkn_frm'         => Mreq::tp('tkn_frm') ,
		'date_echeance'   => Mreq::tp('date_echeance') ,
		'montant'  		  => Mreq::tp('montant') ,
		'commentaire'     => Mreq::tp('commentaire') ,
		);

    $date_fin=Mreq::tp('dat_fn');
    $date_effet=Mreq::tp('dat_ef');
    $dev=Mreq::tp('devis');
    //var_dump($dev);

    $echeance=new Mcontrat();
    $date_echeance=$echeance->verif_date_echeance_add(date('Y-m-d',strtotime($posted_data['date_echeance'])));
    
    
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	if($posted_data['date_echeance'] == NULL){

		$empty_list .= "<li>Date d'échéance</li>";
		$checker = 1;
	}
       
	if($posted_data['montant'] == NULL OR $posted_data['montant'] == '0'){

		$empty_list .= "<li>Montant TTC à facturer</li>";
		$checker = 1; 
	}

	$empty_list.= "</ul>";

	if($checker == 1)
	{
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


        $new_contrat1 = new Mcontrat($posted_data);
        $new_contrat1->get_total_devis($dev);
        //var_dump($new_contrat1->Shw_type('totalttc',1));

        $new_contrat2 = new Mcontrat($posted_data);
        $new_contrat2->get_total_echeances(Mreq::tp('tkn_frm'));
        //var_dump($new_contrat2->Shw_type('montant_total',1));

        if ($new_contrat1->Shw_type('totalttc', 1) <= $new_contrat2->Shw_type('montant_total', 1)) {
            $montant_echeance1 = "<ul>Le montant total du devis est inférieur au montant total des échéances, Il faut minimiser le montant !!!</ul>";
            $checker = 4;
        }

        if ($checker == 4) {
            exit("0#$montant_echeance1");
        }


	$new_echeance = new Mcontrat($posted_data);



  //execute Insert returne false if error
	if($new_echeance->save_new_echeance($posted_data['tkn_frm'])){

		exit("1#".$new_echeance->log);
	}else{

		exit("0#".$new_devis_d->log);
	}
	
}

view::load_view('addecheance_contrat');