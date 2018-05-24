<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: mouvements_stock
//Created : 26-04-2018
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->need_notif=false;
$html_data_table->title_module = "mouvements_stock";
$html_data_table->task = 'mouvements_stock';
$html_data_table->btn_action = false;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}