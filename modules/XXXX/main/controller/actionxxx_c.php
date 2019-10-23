<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: XXXX
//Created : 19-10-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$XXXX = new MXXXX();
$XXXX->id_XXXX = Mreq::tp('id');
$XXXX->get_XXXX();



$action = new TableTools();
$action->line_data = $XXXX->XXXX_info;
$action->action_line_table('XXXX', 'visites', $XXXX->XXXX_info['creusr'], 'deleteXXXX');


echo '</ul>';