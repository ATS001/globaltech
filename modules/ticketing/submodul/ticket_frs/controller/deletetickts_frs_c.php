<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//Controller EXEC Form
$ticket_frs = new Mticket_frs();
$ticket_frs->id_ticket_frs = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$ticket_frs->get_ticket_frs())
{  
   // returne message error red to ticket_frs 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $ticket_frs->ticket_frs_info['etat'];
//$ticket_frs->deletetickts_frs($etat)
//Execute Validate - delete


if($ticket_frs->deletetickts_frs())
{
	exit("1#".$ticket_frs->log);

}else{
	exit("0#".$ticket_frs->log);
}