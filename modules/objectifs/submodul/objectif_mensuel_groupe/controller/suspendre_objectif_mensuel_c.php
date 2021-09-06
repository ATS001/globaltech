<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel
//Created : 27-12-2019
//Controller EXEC Form
$objectif_mensuel = new Mobjectif_mensuel();
$objectif_mensuel->id_objectif_mensuel = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$objectif_mensuel->get_objectif_mensuel())
{
   // returne message error red to objectif_mensuel
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $objectif_mensuel->objectif_mensuel_info['etat'];
//$objectif_mensuel->suspendre_objectif_mensuel($etat)
//Execute Validate - delete
//if($objectif_mensuel->suspendre_objectif_mensuel())


if($objectif_mensuel->auto_save_comission_objectif_mensuel())

{
	exit("1#".$objectif_mensuel->log);

}else{
	exit("0#".$objectif_mensuel->log);
}
