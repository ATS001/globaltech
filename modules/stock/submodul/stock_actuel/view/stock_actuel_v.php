<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: stock_actuel
//Created : 12-05-2018
//View
//array colomn
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "stock_actuel";
$html_data_table->task = 'stock_actuel';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































