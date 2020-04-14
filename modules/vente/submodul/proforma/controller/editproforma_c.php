<?php
if(MInit::form_verif('editproforma', false))
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{  
   // returne message error red to client 
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	
	$posted_data = array(
		
		'id'                  => Mreq::tp('id') ,
		'id_client'           => Mreq::tp('id_client') ,
		'tva'                 => Mreq::tp('tva') ,
		'tkn_frm'             => Mreq::tp('tkn_frm') ,
		'vie'                 => Mreq::tp('vie') ,
		'date_proforma'       => Mreq::tp('date_proforma') ,
		/* 'type_remise'      => Mreq::tp('type_remise') ,
		'valeur_remise'       => Mreq::tp('remise_montant') ,
		'totalht'             => Mreq::tp('totalht') ,
		'totalttc'            => Mreq::tp('totalttc') ,
		'totaltva'            => Mreq::tp('totaltva') ,*/
		'claus_comercial'     => Mreq::tp('claus_comercial'),
		'id_commercial'       => Mreq::tp('id_commercial'),
		/*'commission'        => Mreq::tp('commission'),
		'type_commission'     => Mreq::tp('type_commission'),
		'total_commission'    => Mreq::tp('total_commission')*/
		'id_commercial_ex'    => Mreq::tp('id_commercial_ex'),
		'commission_ex'       => Mreq::tp('commission_ex'),
		'total_commission_ex' => Mreq::tp('total_commission_ex'),
		'type_commission_ex'  => Mreq::tp('type_commission_ex'),
		
	);


  //Check if array have empty element return list
  //for acceptable empty field do not put here  

	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	/*if($posted_data['checker_reference'] != MInit::cryptage($posted_data['reference'],1)){

		$empty_list .= "<li>La réference est invalide</li>";
		$checker = 1;
	}*/
	if($posted_data['id_client'] == NULL){

		$empty_list .= "<li>Client</li>";
		$checker = 1;
	}
	if($posted_data['date_proforma'] == NULL){

		$empty_list .= "<li>Date proforma</li>";
		$checker = 1;
	}
	if($posted_data['tva'] == NULL OR !in_array($posted_data['tva'],  array( 'O','N' ))){

      $empty_list .= "<li>Type remise est incorrecte</li>";
      $checker = 1;
    }
    if($posted_data['vie'] == NULL OR !in_array($posted_data['vie'],  array( '30','60', '90', '180', '365' ))){

      $empty_list .= "<li>Durée de validité</li>";
      $checker = 1;
    }
	/*if($posted_data['type_remise'] == NULL OR !in_array($posted_data['type_remise'],  array( 'P','M' ))){

		$empty_list .= "<li>Type remise est incorrecte</li>";
		$checker = 1;
	}
	if($posted_data['totalttc'] == NULL){

		$empty_list .= "<li>Total TTC</li>";
		$checker = 1;
	}
	if($posted_data['totaltva'] == NULL){

		$empty_list .= "<li>Total TVA</li>";
		$checker = 1;
	}
	if($posted_data['totalht'] == NULL){

		$empty_list .= "<li>Total HT</li>";
		$checker = 1;
	}*/
	if($posted_data['claus_comercial'] == NULL){

		$empty_list .= "<li>Clauses commerciales</li>";
		$checker = 1;
	}
    /*if($posted_data['service'] == NULL){

      $empty_list .= "<li>Service</li>";
      $checker = 1;
    }
    */
    /*if($posted_data['type_commission_ex'] == NULL OR !in_array($posted_data['type_commission'],  array( 'C','S' ))){

      $empty_list .= "<li>Type commission extern est incorrecte</li>";
      $checker = 1;
    }*/

  if($posted_data['id_commercial'] == NULL){

      $empty_list .= "<li>Commercial</li>";
      $checker = 1;
    }
    if($posted_data['commission_ex'] == NULL OR !is_numeric($posted_data['commission_ex']) ){

      $empty_list .= "<li>Commission Externe</li>";
      $checker = 1;
    }
    /*$set_comission = Msetting::get_set('plafond_comission');
    if($posted_data['commission'] > $set_comission ){

      $empty_list .= "<li>Commission ne dois pas dépasser $set_comission</li>";
      $checker = 1;
    }*/
    
/*    if($posted_data['total_commission'] == NULL OR !is_numeric($posted_data['total_commission']) ){

      $empty_list .= "<li>Total Commission</li>";
      $checker = 1;
    }
*/

    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
    	exit("0#$empty_list");
    }

    

  //End check empty element


    $exist_proforma = new  Mproforma($posted_data);
    $exist_proforma->id_proforma = $posted_data['id'];


  //execute Insert returne false if error
    if($exist_proforma->edit_exist_proforma()){

    	exit("1#".$exist_proforma->log);
    }else{

    	exit("0#".$exist_proforma->log);
    }


} else {
	view::load_view('editproforma');
}






?>