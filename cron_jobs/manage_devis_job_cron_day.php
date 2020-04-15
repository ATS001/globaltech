<?php 

global $db;

$etat_expir  = Msetting::get_set('etat_devis', 'devis_expir');
$etat_archiv = Msetting::get_set('etat_devis', 'devis_archive');

if($etat_expir == null OR $etat_archiv == null)
{
	log_cron('Paramètres manquant', 'manage_devis');

}else{

	$sql = "call manage_devis($etat_expir, $etat_archiv);";
	if($db->Query($sql))
	{
		log_cron('Traitement réussi', 'manage_devis');

	}else{
		log_cron('Erreur Opération '.$sql, 'manage_devis');
	}

}




