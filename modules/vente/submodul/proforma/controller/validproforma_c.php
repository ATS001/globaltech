<?php 

$proforma = new Mproforma();
$proforma->id_proforma = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$proforma->get_proforma())
{  
   // returne message error red to proforma 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $proforma->proforma_info['etat'];

if($proforma->valid_proforma($etat))
{
	exit("1#".$proforma->log);

}else{
	exit("0#".$proforma->log);
}