<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 01-11-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectifs = new Mobjectifs();
$objectifs->id_objectifs = Mreq::tp('id');
$objectifs->get_objectifs();



$action = new TableTools();
$action->line_data = $objectifs->objectifs_info;
$action->action_line_table('objectifs', 'objectifs', $objectifs->objectifs_info['creusr'], 'deleteobjectifs');


echo '</ul>';