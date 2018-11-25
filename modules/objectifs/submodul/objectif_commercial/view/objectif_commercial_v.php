<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_commercial
//Created : 01-11-2018
//View
//array colomn
$array_column = array(
    array(
        'column' => 'objectif_commercial.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '3',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'objectif_commercial.description',
        'type'   => '',
        'alias'  => 'designation',
        'width'  => '15',
        'header' => 'Désignation',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_commercial.objectif',
        'type'   => '',
        'alias'  => 'objectif',
        'width'  => '5',
        'header' => 'Objectif',
        'align'  => 'R'
    ),
    array(
        'column' => 'objectif_commercial.realise',
        'type'   => '',
        'alias'  => 'realise',
        'width'  => '5',
        'header' => 'Réalisation',
        'align'  => 'R'
    ),
    array(
        'column' => 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)',
        'type'   => '',
        'alias'  => 'commercial',
        'width'  => '10',
        'header' => 'Commercial',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_commercial.date_s',
        'type'   => 'date',
        'alias'  => 'date_s',
        'width'  => '7',
        'header' => 'Date début',
        'align'  => 'C'
    ),
    array(
        'column' => 'objectif_commercial.date_e',
        'type'   => 'date',
        'alias'  => 'date_e',
        'width'  => '7',
        'header' => 'Date fin',
        'align'  => 'C'
    ),


    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '12',
        'header' => 'Statut',
        'align'  => 'L'
    ),

);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Objectifs par Commercial";
$html_data_table->task = 'objectif_commercial';
$html_data_table->task_add = 'add_objectif_commercial';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































