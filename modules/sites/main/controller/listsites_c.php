<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: sites
//Created : 17-02-2019
//Controller Liste
$array_column = array(
	array(
        'column' => 'sites.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '2',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'sites.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '4',
        'header' => 'Reference',
        'align'  => 'C'
    ),

		array(
        'column' => 'sites.site_name',
        'type'   => '',
        'alias'  => 'site_name',
        'width'  => '13',
        'header' => 'Nom site',
        'align'  => 'L'
    ),

    array(
        'column' => 'sites.type_site',
        'type'   => '',
        'alias'  => 'type_site',
        'width'  => '3',
        'header' => 'Type',
        'align'  => 'C'
    ),
    array(
        'column' => 'clients.denomination',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '20',
        'header' => 'Client',
        'align'  => 'C'
    ),
    array(
        'column' => 'sites.date_mes',
        'type'   => 'date',
        'alias'  => 'date_mes',
        'width'  => '4',
        'header' => 'Date MES',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '4',
        'header' => 'Statut',
        'align'  => 'C'
    ),

 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('sites ,clients ');
//Set Jointure
$list_data_table->joint = 'sites.id_client = clients.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'sites';
//Set Task used for statut line
$list_data_table->task = 'sites';
//Set File name for export
$list_data_table->file_name = 'sites';
//Set Title of report
$list_data_table->title_report = 'Liste sites';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}
