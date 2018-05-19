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
        'column' => 'entrepot',
        'type'   => '',
        'alias'  => 'entrepot',
        'width'  => '15',
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

     //Complete Array fields here
    array(
                            'column' => 'designation',
                            'type'   => '',
                            'alias'  => 'designation',
                            'width'  => '30',
                            'header' => 'Désignation',
                            'align'  => 'L'
                        ),
         //Complete Array fields here
    array(
                            'column' => 'seuil',
                            'type'   => '',
                            'alias'  => 'seuil',
                            'width'  => '5',
                            'header' => 'Seuil Min',
                            'align'  => 'C'
                        ),
    array(
                            'column' => 'qte_stock',
                            'type'   => '',
                            'alias'  => 'qte_stock',
                            'width'  => '5',
                            'header' => 'Quantité Disponible',
                            'align'  => 'C'
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