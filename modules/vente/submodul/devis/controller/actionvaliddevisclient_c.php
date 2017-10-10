<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 09-10-2017
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');
$devis->get_devis();



$action = new TableTools();
$action->line_data = $devis->devis_info;
$action->action_line_table('devis', 'devis', $devis->devis_info['creusr'], 'deletedevis');


echo '</ul>';