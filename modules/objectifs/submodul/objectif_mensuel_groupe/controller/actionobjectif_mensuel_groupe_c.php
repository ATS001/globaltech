<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel_groupe
//Created : 21-05-2020
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$objectif_mensuel_groupe = new Mobjectif_mensuel_groupe();
$objectif_mensuel_groupe->id_objectif_mensuel_groupe = Mreq::tp('id');
$objectif_mensuel_groupe->get_objectif_mensuel_groupe();



$action = new TableTools();
$action->line_data = $objectif_mensuel_groupe->objectif_mensuel_groupe_info;
$action->action_line_table('objectif_mensuel_groupe', 'objectif_annuel', $objectif_mensuel_groupe->objectif_mensuel_groupe_info['creusr'], 'deleteobjectif_mensuel_groupe');


echo '</ul>';
