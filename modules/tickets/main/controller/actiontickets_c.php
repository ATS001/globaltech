<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();

echo '<ul class="dropdown-menu dropdown-menu-right">';
$tickets = new Mtickets();
$tickets->id_tickets = Mreq::tp('id');
$tickets->get_tickets();

$action = new TableTools();
$action->line_data = $tickets->tickets_info;
$action->action_line_table('tickets', 'tickets', $tickets->tickets_info['creusr'], 'deletetickets');
//var_dump($action);

echo '</ul>';