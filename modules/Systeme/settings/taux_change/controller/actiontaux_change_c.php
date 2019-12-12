<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: taux_change
//Created : 11-12-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$taux_change = new Mtaux_change();
$taux_change->id_taux_change = Mreq::tp('id');
$taux_change->get_taux_change();



$action = new TableTools();
$action->line_data = $taux_change->taux_change_info;
$action->action_line_table('taux_change', 'sys_taux_change', $taux_change->taux_change_info['creusr']);


echo '</ul>';