<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();

echo '<ul class="dropdown-menu dropdown-menu-right">';
$tickets = new Mtickets();
$tickets->id_tickets = Mreq::tp('id');
var_dump($tickets->id_tickets);
$tickets->get_tickets();
//var_dump($tickets->tickets_info);

$action = new TableTools();
$action->line_data = $tickets->tickets_info;
$action->action_line_table('tickets', 'tickets', $tickets->tickets_info['creusr'], 'deletetickets');
//var_dump($action);

echo '</ul>';