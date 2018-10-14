<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs_service
//Created : 22-09-2018
//Controller Liste
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
    'header' => 'Objectif à atteindre',
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
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('objectifs_global', 'services');
//Set Jointure
$list_data_table->joint = 'services.id = objectifs_global.service';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'objectifs_global';
//Set Task used for statut line
$list_data_table->task = 'objectifs_service';
//Set File name for export
$list_data_table->file_name = 'objectifs_service';
//Set Title of report
$list_data_table->title_report = 'Liste objectifs_service';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

