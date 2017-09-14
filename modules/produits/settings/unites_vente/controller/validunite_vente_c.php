	<?php 
	//SYS GLOBAL TECH

	//Get unite_vente info
	$unite_vente = new Munite_vente();
	$unite_vente->id_unite_vente = Mreq::tp('id');

	if(!MInit::crypt_tp('id', null, 'D')or !$unite_vente->get_unite_vente())
	{  
	   // returne message error red to unite de vente
	   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}



	//Execute activation desactivation
	$etat = $unite_vente->unite_vente_info['etat'];

	if($unite_vente->valid_unite_vente($etat))
	{
		exit("1#".$unite_vente->log);

	}else{
		exit("0#".$unite_vente->log);
	}