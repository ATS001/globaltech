<?php 

global $db;

$sql = "call notify_contrat();";


if($db->Query($sql))
{
   log_cron('Contrat notifié', 'notify');
   
}else{
	log_cron('Erreur notification', 'notify');
}