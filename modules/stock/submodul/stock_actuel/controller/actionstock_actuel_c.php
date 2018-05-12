<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: stock_actuel
//Created : 12-05-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$stock_actuel = new Mstock_actuel();
$stock_actuel->id_stock_actuel = Mreq::tp('id');
$stock_actuel->get_stock_actuel();



$action = new TableTools();
$action->line_data = $stock_actuel->stock_actuel_info;
$action->action_line_table('stock_actuel', 'stock', $stock_actuel->stock_actuel_info['creusr'], 'deletestock_actuel');


echo '</ul>';