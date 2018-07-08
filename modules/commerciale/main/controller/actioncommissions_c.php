<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 02-01-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$commission = new Mcommission();
$commission->id_commission = Mreq::tp('id');
$commission->get_commission();

$action = new TableTools();
$action->line_data = $commission->commission_info;
//$action->action_line_table('commissions', 'compte_commerciale', $commission->commission_info['creusr'], 'deletecommerciale');
$action->action_line_table('commissions', 'compte_commerciale');


echo '</ul>';