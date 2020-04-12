<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fencaissements
//Created : 12-04-2020
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$fencaissements = new Mfencaissements();
$fencaissements->id_encaissement = Mreq::tp('id');
$fencaissements->get_encaissement();

$action = new TableTools();
$action->line_data = $fencaissements->encaissement_info;
$action->action_line_table('fencaissement', 'encaissements', $fencaissements->encaissement_info['creusr'], 'deletefencaissement');


echo '</ul>';
