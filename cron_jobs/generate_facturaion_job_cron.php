<?php 
$tva = Mcfg::get('tva');


global $db;

$sql = "call generate_fact($tva);";


if($db->Query($sql))
{
   log_cron('Facturation réussite', 'generate');
   
}else{
	log_cron('Erreur génération', 'generate');
}

