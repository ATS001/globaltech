<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: stock_actuel
//Created : 12-05-2018
//Controller Liste
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
    array(
                            'column' => 'Concat("<span class=\"badge badge-info\">",seuil,"</span>")',
                            'type'   => '',
                            'alias'  => 'seuil',
                            'width'  => '5',
                            'header' => 'Seuil Min',
                            'align'  => 'C'
                        ),
    array(
                            'column' => 'IF(qte_stock < seuil,Concat("<span class=\"badge badge-danger\">",qte_stock,"</span>"),IF(qte_stock > seuil,Concat("<span class=\"badge badge-success\">",qte_stock,"</span>"),Concat("<span class=\"badge badge-warning\">",qte_stock,"</span>")))',
                            'type'   => '',
                            'alias'  => 'qte_stock',
                            'width'  => '5',
                            'header' => 'Quantité Disponible',
                            'align'  => 'C'
                        )

 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('stock_actuel');
//Set Jointure
$list_data_table->joint = '1=1';
//Notif
$list_data_table->need_notif= false;
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'stock_actuel';
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

	

