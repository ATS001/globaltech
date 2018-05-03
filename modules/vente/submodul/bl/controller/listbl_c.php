<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 02-05-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'bl.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
  array(
    'column' => 'bl.reference',
    'type'   => '',
    'alias'  => 'reference',
    'width'  => '15',
    'header' => 'reference',
    'align'  => 'L'
),
  array(
    'column' => 'bl.client',
    'type'   => '',
    'alias'  => 'client',
    'width'  => '15',
    'header' => 'Client',
    'align'  => 'L'
),
  array(
    'column' => 'devis.reference',
    'type'   => '',
    'alias'  => 'iddevis',
    'width'  => '15',
    'header' => 'Devis',
    'align'  => 'L'
),
  array(
    'column' => 'bl.date_bl',
    'type'   => 'date',
    'alias'  => 'date_bl',
    'width'  => '15',
    'header' => 'Date BL',
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
$list_data_table->tables = array('bl','devis');
//Set Jointure
$list_data_table->joint = 'bl.iddevis = devis.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'bl';
//Set Task used for statut line
$list_data_table->task = 'bl';
//Set File name for export
$list_data_table->file_name = 'bl';
//Set Title of report
$list_data_table->title_report = 'Liste bl';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



