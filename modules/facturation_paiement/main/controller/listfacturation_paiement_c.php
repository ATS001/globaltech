<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: facturation_paiement
//Created : 12-04-2020
//Controller Liste
$array_column = array(
	array(
        'column' => 'factures,encaissement.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     
     
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
$list_data_table->tables = array('factures,encaissement');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'factures,encaissement';
//Set Task used for statut line
$list_data_table->task = 'facturation_paiement';
//Set File name for export
$list_data_table->file_name = 'facturation_paiement';
//Set Title of report
$list_data_table->title_report = 'Liste facturation_paiement';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

