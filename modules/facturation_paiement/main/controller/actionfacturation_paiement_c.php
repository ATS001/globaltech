<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: facturation_paiement
//Created : 12-04-2020
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$facturation_paiement = new Mfacturation_paiement();
$facturation_paiement->id_facturation_paiement = Mreq::tp('id');
$facturation_paiement->get_facturation_paiement();



$action = new TableTools();
$action->line_data = $facturation_paiement->facturation_paiement_info;
$action->action_line_table('facturation_paiement', 'factures,encaissement', $facturation_paiement->facturation_paiement_info['creusr'], 'deletefacturation_paiement');


echo '</ul>';