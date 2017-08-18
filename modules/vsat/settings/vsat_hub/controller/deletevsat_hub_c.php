<?php 
if(!MInit::crypt_tp('id', null, 'D'))
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$vsat_hub = new Mvsat_hub();
$vsat_hub->id_vsat_hub = Mreq::tp('id');

if($vsat_hub->delete_vsat_hub())
{
	exit("1#".$vsat_hub->log);

}else{
	exit("0#".$vsat_hub->log);
}