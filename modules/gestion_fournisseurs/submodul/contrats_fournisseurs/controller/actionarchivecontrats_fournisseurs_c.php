<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs
//Created : 01-08-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$contrats_fournisseurs = new Mcontrats_fournisseurs();
$contrats_fournisseurs->id_contrats_fournisseurs = Mreq::tp('id');
$contrats_fournisseurs->get_contrats_fournisseurs();



$action = new TableTools();
$action->line_data = $contrats_fournisseurs->contrats_fournisseurs_info;
$action->action_line_table('contrats_fournisseurs', 'contrats_frn', $contrats_fournisseurs->contrats_fournisseurs_info['creusr'], 'deletecontrats_fournisseurs');


echo '</ul>';