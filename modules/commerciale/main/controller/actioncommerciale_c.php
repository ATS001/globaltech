<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$commerciale = new Mcommerciale();
$commerciale->id_commerciale = Mreq::tp('id');
$commerciale->get_commerciale();



$action = new TableTools();
$action->line_data = $commerciale->commerciale_info;
$action->action_line_table('commerciale', 'commerciaux', $commerciale->commerciale_info['creusr'], 'deletecommerciale');


echo '</ul>';