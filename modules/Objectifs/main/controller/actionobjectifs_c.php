<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: Objectifs
//Created : 22-09-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$Objectifs = new MObjectifs();
$Objectifs->id_Objectifs = Mreq::tp('id');
$Objectifs->get_Objectifs();



$action = new TableTools();
$action->line_data = $Objectifs->Objectifs_info;
$action->action_line_table('Objectifs', 'objectifs', $Objectifs->Objectifs_info['creusr'], 'deleteObjectifs');


echo '</ul>';