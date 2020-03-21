<?php 

$objectif_mensuel = new Mobjectif_mensuel();

if($objectif_mensuel->start_objectifs_first_of_month())
{
   log_cron('Objectif démarré', 'run_obj');
   
}else{
	log_cron('Erreur démarrage Objectif' . $objectif_mensuel->log, 'start_obj');
}

if($objectif_mensuel->auto_save_comission_objectif_mensuel())
{
   log_cron('Objectifs payés', 'pay_obj');
   
}else{
	log_cron('Erreur Paiement Objectif' . $objectif_mensuel->log, 'pay_obj');
}
