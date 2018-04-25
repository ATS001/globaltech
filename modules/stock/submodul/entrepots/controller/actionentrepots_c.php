<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$entrepots = new Mentrepots();
$entrepots->id_entrepots = Mreq::tp('id');
$entrepots->get_entrepots();



$action = new TableTools();
$action->line_data = $entrepots->entrepots_info;
$action->action_line_table('entrepots', 'entrepots', $entrepots->entrepots_info['creusr'], 'deleteentrepots');


echo '</ul>';