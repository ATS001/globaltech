<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_service
//Created : 01-11-2018
//Controller Liste
$array_column = array(
    array(
        'column' => 'objectif_service.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    
    array(
        'column' => 'objectif_service.description',
        'type'   => '',
        'alias'  => 'description',
        'width'  => '15',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_service.objectif',
        'type'   => 'int',
        'alias'  => 'objectif',
        'width'  => '15',
        'header' => 'Objectif',
        'align'  => 'R'
    ),
    array(
        'column' => 'objectif_service.realise',
        'type'   => 'int',
        'alias'  => 'realise',
        'width'  => '15',
        'header' => 'Réalisation',
        'align'  => 'L'
    ),
    array(
        'column' => 'services.service',
        'type'   => '',
        'alias'  => 'id_service',
        'width'  => '15',
        'header' => 'Service',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_service.date_s',
        'type'   => 'date',
        'alias'  => 'date_s',
        'width'  => '15',
        'header' => 'Date début',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_service.date_e',
        'type'   => 'date',
        'alias'  => 'date_e',
        'width'  => '15',
        'header' => 'Date fin',
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
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('objectif_service', 'services');
//Set Jointure
$list_data_table->joint = 'services.id = objectif_service.id_service';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'objectif_service';
//Set Task used for statut line
$list_data_table->task = 'objectif_service';
//Set File name for export
$list_data_table->file_name = 'objectif_service';
//Set Title of report
$list_data_table->title_report = 'Liste objectif_service';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

