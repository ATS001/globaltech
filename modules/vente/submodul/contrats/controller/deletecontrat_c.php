
<?php 
	//SYS GLOBAL TECH
	// Modul: contrats => Controller

	//Get contrat info
	$contrat = new Mcontrat();
	$contrat->id_contrat = Mreq::tp('id');


	if(!MInit::crypt_tp('id', null, 'D')or !$contrat->get_contrat())
	{  
	   // returne message error red to contrat 
	   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}


	if($contrat->delete_contrat())
	{
		exit("1#".$contrat->log);

	}else{
		exit("0#".$contrat->log);
	}