<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: stock_actuel
//Created : 12-05-2018
//Controller Liste
$array_column = array(
    array(
        'column' => 'id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),

    array(
        'column' => 'entrepot',
        'type'   => '',
        'alias'  => 'entrepot',
        'width'  => '15',
        'header' => 'Entrepôt',
        'align'  => 'L'
    ),

     //Complete Array fields here
    array(
                            'column' => 'designation',
                            'type'   => '',
                            'alias'  => 'designation',
                            'width'  => '30',
                            'header' => 'Désignation',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'qte',
                            'type'   => '',
                            'alias'  => 'qte',
                            'width'  => '5',
                            'header' => 'Quantité Disponible',
                            'align'  => 'R'
                        )

 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('mouvements_stock');
//Set Jointure
$list_data_table->joint = '';
//Notif
$list_data_table->need_notif= false;
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'mouvements_stock';
//Set Task used for statut line
$list_data_table->task = 'stock_actuel';
//Set File name for export
$list_data_table->file_name = 'stock_actuel';
//Set Title of report
$list_data_table->title_report = 'Stock Disponible';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

