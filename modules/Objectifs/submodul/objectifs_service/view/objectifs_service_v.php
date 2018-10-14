<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs_service
//Created : 22-09-2018
//View
//array colomn
$array_column = array(
	array(
        'column' => 'objectifs_global.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    //Complete Array fields here
   array(
    'column' => 'objectifs_global.descreption',
    'type'   => '',
    'alias'  => 'descreption',
    'width'  => '15',
    'header' => 'Descreption',
    'align'  => 'L'
),
   array(
    'column' => 'services.service',
    'type'   => '',
    'alias'  => 'service',
    'width'  => '10',
    'header' => 'Service',
    'align'  => 'L'
),
   array(
    'column' => 'objectifs_global.objectif',
    'type'   => 'int',
    'alias'  => 'objectif',
    'width'  => '10',
    'header' => 'Objectif',
    'align'  => 'R'
),
   array(
    'column' => 'objectifs_global.date_debut',
    'type'   => 'date',
    'alias'  => 'date_debut',
    'width'  => '10',
    'header' => 'Date début',
    'align'  => 'L'
),
   array(
    'column' => 'objectifs_global.date_fin',
    'type'   => 'date',
    'alias'  => 'date_fin',
    'width'  => '10',
    'header' => 'Date Fin',
    'align'  => 'L'
),


   array(
    'column' => 'statut',
    'type'   => '',
    'alias'  => 'statut',
    'width'  => '15',
    'header' => 'Statut',
    'align'  => 'L'
),

);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "objectifs_service";
$html_data_table->task = 'objectifs_service';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































