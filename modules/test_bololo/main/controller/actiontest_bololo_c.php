<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: test_bololo
//Created : 29-10-2017
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$test_bololo = new Mtest_bololo();
$test_bololo->id_test_bololo = Mreq::tp('id');
$test_bololo->get_test_bololo();



$action = new TableTools();
$action->line_data = $test_bololo->test_bololo_info;
$action->action_line_table('test_bololo', 'test_bololo', $test_bololo->test_bololo_info['creusr'], 'deletetest_bololo');


echo '</ul>';