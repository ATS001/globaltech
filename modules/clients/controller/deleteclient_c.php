<?php 
//SYS GLOBAL TECH
// Modul: clients => Controller

//Get client info
$client = new Mclients();
$client->id_client = Mreq::tp('id');


if(!MInit::crypt_tp('id', null, 'D')or !$client->get_client())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


if($client->delete_client())
{
	exit("1#".$client->log);

}else{
	exit("0#".$client->log);
}