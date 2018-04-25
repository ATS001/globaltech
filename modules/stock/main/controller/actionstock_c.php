<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: stock
//Created : 25-04-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$stock = new Mstock();
$stock->id_stock = Mreq::tp('id');
$stock->get_stock();



$action = new TableTools();
$action->line_data = $stock->stock_info;
$action->action_line_table('stock', 'stock', $stock->stock_info['creusr'], 'deletestock');


echo '</ul>';