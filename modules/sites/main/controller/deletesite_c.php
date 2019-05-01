<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Controller EXEC Form
$site = new Msites();
$site->id_sites= Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$site->get_sites())
{  
   // returne message error red to commerciale 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $commerciale->commerciale_info['etat'];
//$commerciale->deletecommerciale($etat)
//Execute Validate - delete


if($site->delete_sites())
{
	exit("1#".$site->log);

}else{
	exit("0#".$site->log);
}