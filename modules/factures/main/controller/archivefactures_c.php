<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: factures
//Created : 01-08-2018
//Controller EXEC Form
$factures = new Mfacture();
$factures->id_facture = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$factures->get_facture())
{  
   // returne message error red to factures 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $factures->factures_info['etat'];
//$factures->archivefacture($etat)
//Execute Validate - delete


if($factures->archivefactures())
{
	exit("1#".$factures->log);

}else{
	exit("0#".$factures->log);
}