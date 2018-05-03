<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 02-05-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$bl = new Mbl();
$bl->id_bl = Mreq::tp('id');
$bl->get_bl();



$action = new TableTools();
$action->line_data = $bl->bl_info;
$action->action_line_table('bl', 'bl', $bl->bl_info['creusr'], 'deletebl');


echo '</ul>';