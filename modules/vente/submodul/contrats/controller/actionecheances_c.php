<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 26-02-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$contrats = new Mcontrats();
$contrats->id_contrats = Mreq::tp('id');
$contrats->get_contrats();



$action = new TableTools();
$action->line_data = $contrats->contrats_info;
$action->action_line_table('contrats', 'contrats', $contrats->contrats_info['creusr'], 'deletecontrats');


echo '</ul>';