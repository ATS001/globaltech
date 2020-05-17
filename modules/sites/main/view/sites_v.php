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
