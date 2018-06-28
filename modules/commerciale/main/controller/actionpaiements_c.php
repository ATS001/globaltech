<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 02-01-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$paiement = new Mcommission();
$paiement->id_paiement = Mreq::tp('id');
$paiement->get_paiement();

$action = new TableTools();
$action->line_data = $paiement->paiements_info;
$action->action_line_table('paiements', 'compte_commerciale');


echo '</ul>';