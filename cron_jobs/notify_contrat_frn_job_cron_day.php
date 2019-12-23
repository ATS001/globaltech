<?php 

global $db;

$sql = "call notify_contrat();";


if($db->Query($sql))
{
   log_cron('Contrat notifié', 'notify_contrat_frn');
   
}else{
	log_cron('Erreur notification', 'notify_contrat_frn');
}