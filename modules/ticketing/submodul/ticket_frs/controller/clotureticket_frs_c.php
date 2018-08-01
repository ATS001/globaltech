<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 22-04-2018
//Controller EXEC Form
$tickets = new Mticket_frs();
$tickets->id_tickets = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$tickets->get_ticket_frs())
{  
   // returne message error red to tickets 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

    
//Etat for validate row
$etat = Msetting::get_set('etat_ticket', 'ticket_cloturer');


if($tickets->clotureticket_frs($etat))
{
	exit("1#".$tickets->log);

}else{
	exit("0#".$tickets->log);
}