<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_service
//Created : 03-01-2019
//Controller EXEC Form
$objectif_service = new Mobjectif_service();
$objectif_service->id_objectif_service = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$objectif_service->get_objectif_service())
{  
   // returne message error red to objectif_service 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $objectif_service->objectif_service_info['etat'];
//$objectif_service->debloque_objectif_service($etat)
//Execute Validate - delete


if($objectif_service->debloque_objectif_service())
{
	exit("1#".$objectif_service->log);

}else{
	exit("0#".$objectif_service->log);
}