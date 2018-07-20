<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticketing
//Created : 18-07-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$ticketing = new Mticketing();
$ticketing->id_ticketing = Mreq::tp('id');
$ticketing->get_ticketing();



$action = new TableTools();
$action->line_data = $ticketing->ticketing_info;
$action->action_line_table('ticketing', 'tickets', $ticketing->ticketing_info['creusr'], 'deleteticketing');


echo '</ul>';