<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 02-05-2018
//View
//array colomn
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
    'header' => 'Reference',
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "bl";
$html_data_table->task = 'bl';
$html_data_table->btn_add_check=TRUE;

$html_data_table->btn_return = array('task' =>'tdb');
$html_data_table->use_filter = true;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































