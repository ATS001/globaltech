<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 17-04-2018
//Controller EXEC Form
$tickets = new Mtickets();
$tickets->id_action_ticket = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D'))
{  
   // returne message error red to tickets 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

if($tickets->deleteactionticket())
{
	exit("1#".$tickets->log);

}else{
	exit("0#".$tickets->log);
}