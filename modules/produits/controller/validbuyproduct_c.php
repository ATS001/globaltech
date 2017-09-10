<?php 
//Get all achat info 
 $achat = new Machat();
//Set ID of Module with POST id
 $achat->id_achat = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$achat->get_achat_produit())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $achat->achat_info['etat'];


if($achat->valid_achat_produit($etat))
{
	exit("1#".$achat->log);

}else{
	exit("0#".$achat->log);
}