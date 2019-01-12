<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_commercial
//Created : 01-11-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectif_commercial = new Mobjectif_commercial();
$objectif_commercial->id_objectif_commercial = Mreq::tp('id');
if(!$objectif_commercial->get_objectif_commercial()){
	exit('Erreur Système, Contactez Administrateur');
}



$action = new TableTools();
$action->line_data = $objectif_commercial->objectif_commercial_info;
$action->action_line_table('objectif_commercial', 'objectif_commercial', $objectif_commercial->objectif_commercial_info['creusr'], 'deleteobjectif_commercial');


echo '</ul>';