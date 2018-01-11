<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: client_temp
//Created : 07-01-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$client_temp = new Mclient_temp();
$client_temp->id_client_temp = Mreq::tp('id');
$client_temp->get_client_temp();



$action = new TableTools();
$action->line_data = $client_temp->client_temp_info;
$action->action_line_table('client_temp', 'clients_temp', $client_temp->client_temp_info['creusr'], 'deleteclient_temp');


echo '</ul>';