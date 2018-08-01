<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller

$contrats_frn = new Mcontrats_fournisseurs();
$contrats_frn->id_contrats_frn = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$contrats_frn->get_contrats_frn())
{  
   // returne message error red to contrats_frn 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $contrats_frn->contrats_frn_info['etat'];

if($contrats_frn->valid_contrats_frn($etat))
{
	exit("1#".$contrats_frn->log);

}else{
	exit("0#".$contrats_frn->log);
}