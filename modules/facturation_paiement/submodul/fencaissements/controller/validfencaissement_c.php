<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fencaissements
//Created : 12-04-2020
//Controller EXEC Form
$fencaissements = new Mfencaissements();
$fencaissements->id_encaissement = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$fencaissements->get_encaissement())
{
   // returne message error red to fencaissements
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $fencaissements->fencaissements_info['etat'];
//$fencaissements->validfencaissement($etat)
//Execute Validate - delete


if($fencaissements->valid_encaissement())
{
	exit("1#".$fencaissements->log);

}else{
	exit("0#".$fencaissements->log);
}
