<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: clients
//Created : 18-07-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$clients = new Mclients();
$clients->id_clients = Mreq::tp('id');
$clients->get_clients();



$action = new TableTools();
$action->line_data = $clients->clients_info;
$action->action_line_table('clients', 'clients', $clients->clients_info['creusr'], 'deleteclients');


echo '</ul>';