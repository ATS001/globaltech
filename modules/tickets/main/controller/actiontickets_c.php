<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$tickets = new Mtickets();
$tickets->id_tickets = Mreq::tp('id');
$tickets->get_tickets();



$action = new TableTools();
$action->line_data = $tickets->tickets_info;
$action->action_line_table('tickets', 'tickets', $tickets->tickets_info['creusr'], 'deletetickets');


echo '</ul>';