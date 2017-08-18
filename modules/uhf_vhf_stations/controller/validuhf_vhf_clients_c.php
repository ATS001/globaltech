<?php 
//Get all compte info 
 $info_uhf_vhf_clients = new Muhf_vhf_clients();
//Set ID of Module with POST id
 $info_uhf_vhf_clients->id_uhf_vhf_clients = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_uhf_vhf_clients->get_uhf_vhf_clients())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_uhf_vhf_clients->uhf_vhf_clients_info['etat'];

if($info_uhf_vhf_clients->valid_uhf_vhf_clients($etat))
{
	exit("1#".$info_uhf_vhf_clients->log);

}else{
	exit("0#".$info_uhf_vhf_clients->log);
}