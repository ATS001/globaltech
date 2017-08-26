<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => Controller

//Get categorie_client info
$categorie_client = new Mcategorie_client();
$categorie_client->id_categorie_client = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$categorie_client->get_categorie_client())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


if($categorie_client->delete_categorie_client())
{
	exit("1#".$categorie_client->log);

}else{
	exit("0#".$categorie_client->log);
}