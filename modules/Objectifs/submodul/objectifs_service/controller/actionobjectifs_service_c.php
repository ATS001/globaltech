<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs_service
//Created : 22-09-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectifs_service = new Mobjectifs_service();
$objectifs_service->id_objectifs_service = Mreq::tp('id');
$objectifs_service->get_objectifs_service();



$action = new TableTools();
$action->line_data = $objectifs_service->objectifs_service_info;
$action->action_line_table('objectifs_service', 'objectifs_global', $objectifs_service->objectifs_service_info['creusr'], 'deleteobjectifs_service');


echo '</ul>';