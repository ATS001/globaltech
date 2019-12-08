<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: proforma
//Created : 29-07-2018
//Controller EXEC Form
$proforma = new Mproforma();
$proforma->id_proforma = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$proforma->get_proforma())
{  
   // returne message error red to proforma 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $proforma->proforma_info['etat'];
//$proforma->archiveproforma($etat)
//Execute Validate - delete


if($proforma->archiveproforma())
{
	exit("1#".$proforma->log);

}else{
	exit("0#".$proforma->log);
}