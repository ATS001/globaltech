<?php 
//SYS GLOBAL TECH
// Modul: contrats => Controller


$contrat = new Mcontrat();
$contrat->id_contrat = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$contrat->get_contrat())
{  
   // returne message error red to contrat 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $contrat->contrat_info['etat'];

if($contrat->valid_contrats($etat))
{
	exit("1#".$contrat->log);

}else{
	exit("0#".$contrat->log);
}