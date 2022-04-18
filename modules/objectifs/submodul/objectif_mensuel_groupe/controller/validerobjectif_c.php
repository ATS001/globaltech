<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel_groupe
//Created : 24-05-2020
//Controller EXEC Form
$objectif_mensuel_groupe = new Mobjectif_mensuel_groupe();
$objectif_mensuel_groupe->id_objectif_mensuel_groupe = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$objectif_mensuel_groupe->get_objectif_mensuel_groupe())
{
   // returne message error red to objectif_mensuel_groupe
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $objectif_mensuel_groupe->objectif_mensuel_groupe_info['etat'];
//$objectif_mensuel_groupe->validerobjectif($etat)
//Execute Validate - delete


if($objectif_mensuel_groupe->valid_objectif_mensuel_groupe(0,Mreq::tp('id')))
{
	exit("1#".$objectif_mensuel_groupe->log);

}else{
	exit("0#".$objectif_mensuel_groupe->log);
}
