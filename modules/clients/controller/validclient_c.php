<?php 
//SYS GLOBAL TECH
// Modul: clients => Controller

$client = new Mclients();
$client->id_client = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$client->get_client())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $client->client_info['etat'];

if($client->valid_client($etat))
{
	exit("1#".$client->log);

}else{
	exit("0#".$client->log);
}