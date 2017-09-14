<?php
if(MInit::form_verif('editdevis', false))
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{  
   // returne message error red to client 
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	
	$posted_data = array(

		'id'                => Mreq::tp('id') ,
		'id_client'         => Mreq::tp('id_client') ,
		'tva'               => Mreq::tp('tva') ,
		'tkn_frm'           => Mreq::tp('tkn_frm') ,
		'reference'         => Mreq::tp('reference') ,
		'checker_reference' => Mreq::tp('checker_reference') ,
		'date_devis'        => Mreq::tp('date_devis') ,
		'type_remise'       => Mreq::tp('type_remise') ,
		'valeur_remise'     => Mreq::tp('remise_montant') ,
		'totalht'           => Mreq::tp('totalht') ,
		'totalttc'          => Mreq::tp('totalttc') ,
		'totaltva'          => Mreq::tp('totaltva') ,
		'claus_comercial'   => Mreq::tp('claus_comercial')

		);


  //Check if array have empty element return list
  //for acceptable empty field do not put here  

	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	if($posted_data['checker_reference'] != MInit::cryptage($posted_data['reference'],1)){

		$empty_list .= "<li>La réference est invalide</li>";
		$checker = 1;
	}
	if($posted_data['id_client'] == NULL){

		$empty_list .= "<li>Client</li>";
		$checker = 1;
	}
	if($posted_data['date_devis'] == NULL){

		$empty_list .= "<li>Date devis</li>";
		$checker = 1;
	}
	if($posted_data['type_remise'] == NULL OR !in_array($posted_data['type_remise'],  array( 'P','M' ))){

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
	}
	if($posted_data['claus_comercial'] == NULL){

		$empty_list .= "<li>Clauses commerciales</li>";
		$checker = 1;
	}
    /*if($posted_data['service'] == NULL){

      $empty_list .= "<li>Service</li>";
      $checker = 1;
    }
    */
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
    	exit("0#$empty_list");
    }

    

  //End check empty element


    $exist_devis = new  Mdevis($posted_data);
    $exist_devis->id_devis = $posted_data['id'];


  //execute Insert returne false if error
    if($exist_devis->edit_exist_devis()){

    	exit("1#".$exist_devis->log);
    }else{

    	exit("0#".$exist_devis->log);
    }


} else {
	view::load_view('editdevis');
}






?>