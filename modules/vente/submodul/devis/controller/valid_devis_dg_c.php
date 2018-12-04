<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 04-12-2018
//Controller EXEC Form
$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$devis->get_devis())
{  
   // returne message error red to devis 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $devis->devis_info['etat'];
//$devis->valid_devis_dg($etat)
//Execute Validate - delete


if($devis->valid_devis(0))
{
	exit("1#".$devis->log);

}else{
	exit("0#".$devis->log);
}