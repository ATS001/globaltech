<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: gestion_fournisseurs
//Created : 22-07-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$gestion_fournisseurs = new Mgestion_fournisseurs();
$gestion_fournisseurs->id_gestion_fournisseurs = Mreq::tp('id');
$gestion_fournisseurs->get_gestion_fournisseurs();



$action = new TableTools();
$action->line_data = $gestion_fournisseurs->gestion_fournisseurs_info;
$action->action_line_table('gestion_fournisseurs', 'fournisseurs', $gestion_fournisseurs->gestion_fournisseurs_info['creusr'], 'deletegestion_fournisseurs');


echo '</ul>';