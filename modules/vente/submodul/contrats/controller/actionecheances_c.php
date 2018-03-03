<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 26-02-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$contrat = new Mcontrat();
$contrat->id_echeance_contrat= Mreq::tp('id');
$contrat->get_echeance_contrat();



$action = new TableTools();
$action->line_data = $contrat->echeance_contrat_info;
$action->action_line_table('echeances', 'echeances_contrat',$contrat->echeance_contrat_info['creusr'],'');

echo '</ul>';