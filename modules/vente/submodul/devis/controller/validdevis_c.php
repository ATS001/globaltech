<?php 

$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$devis->get_devis())
{  
   // returne message error red to devis 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $devis->devis_info['etat'];

if($devis->valid_devis($etat))
{
	exit("1#".$devis->log);

}else{
	exit("0#".$devis->log);
}