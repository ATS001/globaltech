<?php 
$tva = Msetting::get_set('tva');


global $db;

$sql = "call generate_facturation($tva);";


if($db->Query($sql))
{
   log_cron('Facturation réussite', 'generate');
   
}else{
	log_cron('Erreur génération', 'generate');
}

