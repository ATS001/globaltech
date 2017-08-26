<?php 
//Get all produit info 
 $produit = new Mproduit();
//Set ID of Module with POST id
 $produit->id_produit = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$produit->get_produit())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $produit->produit_info['etat'];


if($produit->valid_produit($etat))
{
	exit("1#".$produit->log);

}else{
	exit("0#".$produit->log);
}