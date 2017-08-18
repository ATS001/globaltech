<?php
if(!MInit::crypt_tp('id', null, 'D'))
{
	// returne message error red to client
	exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$gsm_secteur = new Mgsm_secteur();
$gsm_secteur->id_gsm_secteur = Mreq::tp('id');

if($gsm_secteur->delete_gsm_secteur())
{
	exit("1#".$gsm_secteur->log);
	
}else{
	exit("0#".$gsm_secteur->log);
}
?>