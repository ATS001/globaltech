<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_service
//Created : 01-11-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectif_service = new Mobjectif_service();
$objectif_service->id_objectif_service = Mreq::tp('id');
$objectif_service->get_objectif_service();



$action = new TableTools();
$action->line_data = $objectif_service->objectif_service_info;
$action->action_line_table('objectif_service', 'objectif_service', $objectif_service->objectif_service_info['creusr'], 'deleteobjectif_service');


echo '</ul>';