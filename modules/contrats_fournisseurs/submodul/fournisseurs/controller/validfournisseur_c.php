<?php 
//SYS GLOBAL TECH
// Modul: fournisseurs => Controller

$fournisseur = new Mfournisseurs();
$fournisseur->id_fournisseur = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$fournisseur->get_fournisseur())
{  
   // returne message error red to fournisseur 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $fournisseur->fournisseur_info['etat'];

if($fournisseur->valid_fournisseur($etat))
{
	exit("1#".$fournisseur->log);

}else{
	exit("0#".$fournisseur->log);
}