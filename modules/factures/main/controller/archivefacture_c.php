	<?php 
	//Get all achat info 
	 $facture = new Mfacture();
	//Set ID of Module with POST id
	 $facture->id_facture = Mreq::tp('id');

	if(!MInit::crypt_tp('id', null, 'D') or !$facture->get_facture())
	{  
	   // returne message error red to client 
	   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	//Execute activation desactivation
	//$etat = $facture->facture_info['etat'];


	if($facture->archiver_facture())
	{
		exit("1#".$facture->log);

	}else{
		exit("0#".$facture->log);
	}