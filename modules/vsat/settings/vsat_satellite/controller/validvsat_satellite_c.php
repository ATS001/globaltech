<?php 
if(!MInit::crypt_tp('id', null, 'D'))
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$satellite = new Msatellite();
$satellite->id_satellite = Mreq::tp('id');

if($satellite->valid_satellite())
{
	exit("1#".$satellite->log);

}else{
	exit("0#".$satellite->log);
}