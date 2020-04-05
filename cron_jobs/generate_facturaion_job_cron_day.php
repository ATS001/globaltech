<?php 
$tva = Msetting::get_set('tva');


global $db;

$sql  = "call generate_facturation($tva);";
$sql2 = "call generate_facturation_upgrade($tva);";


if($db->Query($sql) and $db->Query($sql2))
{
   log_cron('Facturation réussite', 'generate');
   
}else{
	log_cron('Erreur génération', 'generate');
}