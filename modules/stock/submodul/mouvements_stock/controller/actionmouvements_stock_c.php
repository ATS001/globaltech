<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: mouvements_stock
//Created : 26-04-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$mouvements_stock = new Mmouvements_stock();
$mouvements_stock->id_mouvements_stock = Mreq::tp('id');
$mouvements_stock->get_mouvements_stock();



$action = new TableTools();
$action->line_data = $mouvements_stock->mouvements_stock_info;
$action->action_line_table('mouvements_stock', 'stock', $mouvements_stock->mouvements_stock_info['creusr'], 'deletemouvements_stock');


echo '</ul>';