<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 19-10-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$prospects = new Mprospects();
$prospects->id_prospect = Mreq::tp('id');
$prospects->get_prospect();

$etat_archive = Msetting::get_set('archive_prospects');
$action = new TableTools();
$action->line_data = $prospects->prospect_info;
$action->action_line_table('prospects', 'prospects', $prospects->prospect_info['creusr'], 'deleteprospect',$etat_archive);


echo '</ul>';