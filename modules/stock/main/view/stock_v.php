<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: stock
//Created : 25-04-2018
//View
//array colomn
$array_column = array(
	array(
        'column' => 'stock.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    //Complete Array fields here
    	array(
                            'column' => 'stock.idproduit',
                            'type'   => '',
                            'alias'  => 'idproduit',
                            'width'  => '15',
                            'header' => 'idproduit',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'stock.qte',
                            'type'   => '',
                            'alias'  => 'qte',
                            'width'  => '15',
                            'header' => 'qte',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'stock.prix_achat',
                            'type'   => '',
                            'alias'  => 'prix_achat',
                            'width'  => '15',
                            'header' => 'prix_achat',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'stock.prix_vente',
                            'type'   => '',
                            'alias'  => 'prix_vente',
                            'width'  => '15',
                            'header' => 'prix_vente',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'stock.date_achat',
                            'type'   => '',
                            'alias'  => 'date_achat',
                            'width'  => '15',
                            'header' => 'date_achat',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'stock.date_validite',
                            'type'   => '',
                            'alias'  => 'date_validite',
                            'width'  => '15',
                            'header' => 'date_validite',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'stock.mouvement',
                            'type'   => '',
                            'alias'  => 'mouvement',
                            'width'  => '15',
                            'header' => 'E/S/R',
                            'align'  => 'L'
                        ),

    
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "stock";
$html_data_table->task = 'stock';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































