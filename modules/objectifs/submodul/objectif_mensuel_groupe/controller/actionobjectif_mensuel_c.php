<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel
//Created : 11-12-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectif_mensuel = new Mobjectif_mensuel();
$objectif_mensuel->id_objectif_mensuel = Mreq::tp('id');
$objectif_mensuel->get_objectif_mensuel();



$action = new TableTools();
$action->line_data = $objectif_mensuel->objectif_mensuel_info;
$action->action_line_table('objectif_mensuel', 'objectif_mensuels', $objectif_mensuel->objectif_mensuel_info['creusr'], 'deleteobjectif_mensuel');


echo '</ul>';