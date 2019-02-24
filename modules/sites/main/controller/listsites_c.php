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
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'sites.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '12',
        'header' => 'Reference',
        'align'  => 'L'
    ),
    array(
        'column' => 'sites.type_site',
        'type'   => '',
        'alias'  => 'type_site',
        'width'  => '6',
        'header' => 'Type',
        'align'  => 'C'
    ),
    array(
        'column' => 'clients.denomination',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '15',
        'header' => 'Client',
        'align'  => 'C'
    ),
    array(
        'column' => 'sites.date_mes',
        'type'   => 'date',
        'alias'  => 'date_mes',
        'width'  => '10',
        'header' => 'Date MES',
        'align'  => 'C'
    ),
    array(
        'column' => 'sites.modem',
        'type'   => '',
        'alias'  => 'modem',
        'width'  => '10',
        'header' => 'Modem',
        'align'  => 'L'
    ),
    array(
        'column' => 'sites.sn_modem',
        'type'   => '',
        'alias'  => 'sn_modem',
        'width'  => '10',
        'header' => 'SN Modem',
        'align'  => 'L'
    ),
       
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '12',
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

	

