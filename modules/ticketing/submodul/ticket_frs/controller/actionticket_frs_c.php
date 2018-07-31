<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$ticket_frs = new Mticket_frs();
$ticket_frs->id_tickets = Mreq::tp('id');
$ticket_frs->get_ticket_frs();


$action = new TableTools();
$action->line_data = $ticket_frs->tickets_info;
$action->action_line_table('ticket_frs', 'tickets_fournisseurs', $ticket_frs->tickets_info['creusr'], 'deleteticket_frs');


echo '</ul>';