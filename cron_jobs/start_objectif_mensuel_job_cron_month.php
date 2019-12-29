<?php 

$objectif_mensuel = new Mobjectif_mensuel();
$commission = new Mcommission();

if($objectif_mensuel->start_objectifs_first_of_month())
{
   log_cron('Objectif démarré', 'run_obj');
   if($commission->calculerCommissionCommerciale())
   {
       log_cron('Commission calculée', 'calcul_commission');
   }else
   {
       log_cron('Calcule commission échoué', 'calcul_commission_echoue');
   }
   
}else{
	log_cron('Erreur démarrage Objectif' . $objectif_mensuel->log, 'start_obj');
}

