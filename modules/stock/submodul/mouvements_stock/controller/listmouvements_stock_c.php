<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: mouvements_stock
//Created : 26-04-2018
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
        'width'  => '14',
        'header' => 'Entrepôt',
        'align'  => 'L'
    ),

    array(
                            'column' => 'reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '11',
                            'header' => 'Référence',
                            'align'  => 'L'
                        ),
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
                            'header' => 'Quantité',
                            'align'  => 'C'
                        ),
    array(
                            'column' => 'DATE',
                            'type'   => 'date',
                            'alias'  => 'DATE',
                            'width'  => '5',
                            'header' => 'Date',
                            'align'  => 'C'
                        ),
    array(
                            'column' => 'source',
                            'type'   => '',
                            'alias'  => 'source',
                            'width'  => '15',
                            'header' => 'Source',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'mouvement',
                            'type'   => '',
                            'alias'  => 'mouvement',
                            'width'  => '4',
                            'header' => 'Mouvement',
                            'align'  => 'C'
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
$list_data_table->task = 'mouvements_stock';
//Set File name for export
$list_data_table->file_name = 'mouvements_stock';
//Set Title of report
$list_data_table->title_report = 'Mouvements Stock';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

