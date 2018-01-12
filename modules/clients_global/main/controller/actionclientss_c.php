<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: clients_global
//Created : 07-01-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$clients_global = new Mclients_global();
$clients_global->id_clients_global = Mreq::tp('id');
$clients_global->get_clients_global();



$action = new TableTools();
$action->line_data = $clients_global->clients_global_info;
$action->action_line_table('clients_global', 'clientsss', $clients_global->clients_global_info['creusr'], 'deleteclients_global');


echo '</ul>';