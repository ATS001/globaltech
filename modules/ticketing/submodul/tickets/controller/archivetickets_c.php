<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 01-08-2018
//Controller EXEC Form
$tickets = new Mtickets();
$tickets->id_tickets = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$tickets->get_tickets())
{  
   // returne message error red to tickets 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $tickets->tickets_info['etat'];
//$tickets->archivetickets($etat)
//Execute Validate - delete


if($tickets->archivetickets())
{
	exit("1#".$tickets->log);

}else{
	exit("0#".$tickets->log);
}