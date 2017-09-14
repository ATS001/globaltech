<?php 

	//Get categorie_produit info

	$categorie_produit = new Mcategorie_produit();
	$categorie_produit->id_categorie_produit = Mreq::tp('id');

	if(!MInit::crypt_tp('id', null, 'D')or !$categorie_produit->get_categorie_produit())
	{  
	   // returne message error red to categorie
	   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}



	//Execute activation desactivation
	$etat = $categorie_produit->categorie_produit_info['etat'];

	if($categorie_produit->valid_categorie_produit($etat))
	{
		exit("1#".$categorie_produit->log);

	}else{
		exit("0#".$categorie_produit->log);
	}