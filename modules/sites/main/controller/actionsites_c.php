<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: sites
//Created : 17-02-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$sites = new Msites();
$sites->id_sites = Mreq::tp('id');
$sites->get_sites();

$action = new TableTools();
$action->line_data = $sites->sites_info;
$action->action_line_table('sites', 'sites', $sites->sites_info['creusr'], 'deletesite');


echo '</ul>';