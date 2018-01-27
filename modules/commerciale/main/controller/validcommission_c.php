<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 10-01-2018
//Controller EXEC Form
$commission = new Mcommission();
$commission->id_commission = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$commission->get_commission())
{  
   // returne message error red to commerciale 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
$etat = Msetting::get_set('commission', 'attente_payement');



if($commission->valid_commission($etat))
{
	exit("1#".$commission->log);

}else{
	exit("0#".$commission->log);
}