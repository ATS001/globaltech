<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_commercial
//Created : 01-11-2018
//Controller EXEC Form
$objectif_commercial = new Mobjectif_commercial();
$objectif_commercial->id_objectif_commercial = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$objectif_commercial->get_objectif_commercial())
{  
   // returne message error red to objectif_commercial 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $objectif_commercial->objectif_commercial_info['etat'];
//$objectif_commercial->valid_objectif_commercial($etat)
//Execute Validate - delete


if($objectif_commercial->valid_objectif_commercial())
{
	exit("1#".$objectif_commercial->log);

}else{
	exit("0#".$objectif_commercial->log);
}