<?php 

global $db;

$sql = "call notify_contrat_frn();";


if($db->Query($sql))
{
   log_cron('Contrat fournisseur notifié', 'notify');
   
}else{
	log_cron('Erreur notification', 'notify');
}