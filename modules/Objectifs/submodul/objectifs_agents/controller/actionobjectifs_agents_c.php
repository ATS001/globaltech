<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs_agents
//Created : 22-09-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectifs_agents = new Mobjectifs_agents();
$objectifs_agents->id_objectifs_agents = Mreq::tp('id');
$objectifs_agents->get_objectifs_agents();



$action = new TableTools();
$action->line_data = $objectifs_agents->objectifs_agents_info;
$action->action_line_table('objectifs_agents', 'objectifs_agents', $objectifs_agents->objectifs_agents_info['creusr'], 'deleteobjectifs_agents');


echo '</ul>';