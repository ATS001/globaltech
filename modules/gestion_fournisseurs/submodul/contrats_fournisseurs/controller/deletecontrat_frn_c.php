<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller

//Get Contrat fournisseur info
$contrats_frn = new Mcontrats_fournisseurs();
$contrats_frn->id_contrats_frn = Mreq::tp('id');


if(!MInit::crypt_tp('id', null, 'D')or !$contrats_frn->get_contrats_frn())
{  
   // returne message error red to contrats_frn 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


if($contrats_frn->delete_contrats_frn())
{
	exit("1#".$contrats_frn->log);

}else{
	exit("0#".$contrats_frn->log);
}