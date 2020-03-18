<?php 

$objectif_mensuel = new Mobjectif_mensuel();

if($objectif_mensuel->start_objectifs_first_of_month())
{
   log_cron('Objectif démarré', 'run_obj');
   
}else{
	log_cron('Erreur démarrage Objectif' . $objectif_mensuel->log, 'start_obj');
}

