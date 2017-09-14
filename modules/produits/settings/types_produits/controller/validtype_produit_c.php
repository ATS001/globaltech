<?php 

	//Get type_produit info
	$type_produit = new Mtype_produit();
	$type_produit->id_type_produit = Mreq::tp('id');

	if(!MInit::crypt_tp('id', null, 'D')or !$type_produit->get_type_produit())
	{  
	   // returne message error red to type 
	   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}



	//Execute activation desactivation
	$etat = $type_produit->type_produit_info['etat'];

	if($type_produit->valid_type_produit($etat))
	{
		exit("1#".$type_produit->log);

	}else{
		exit("0#".$type_produit->log);
	}