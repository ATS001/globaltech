<?php 

$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$devis->get_devis())
{  
   // returne message error red to devis 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


if($devis->valid_devis(0))
{
	exit("1#".$devis->log);

}else{
	exit("0#".$devis->log);
}