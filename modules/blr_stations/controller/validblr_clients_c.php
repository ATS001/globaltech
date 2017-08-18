<?php 
//Get all compte info 
 $info_blr_clients = new Mblr_clients();
//Set ID of Module with POST id
 $info_blr_clients->id_blr_clients = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_blr_clients->get_blr_clients())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_blr_clients->blr_clients_info['etat'];

if($info_blr_clients->valid_blr_clients($etat))
{
	exit("1#".$info_blr_clients->log);

}else{
	exit("0#".$info_blr_clients->log);
}