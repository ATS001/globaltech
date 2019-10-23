<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$visites = new Mvisites();
$visites->id_visites = Mreq::tp('id');
$visites->get_visites();

$action = new TableTools();
$action->line_data = $visites->visites_info;
$action->action_line_table('visites', 'visites', $visites->visites_info['creusr'], 'deletevisites');

echo '</ul>';