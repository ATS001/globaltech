<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: sites
//Created : 17-02-2019
//View
//array colomn
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "sites";
$html_data_table->task = 'sites';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































