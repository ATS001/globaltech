<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: mouvements_stock
//Created : 26-04-2018
//Controller Liste
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
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('stock');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'stock';
//Set Task used for statut line
$list_data_table->task = 'mouvements_stock';
//Set File name for export
$list_data_table->file_name = 'mouvements_stock';
//Set Title of report
$list_data_table->title_report = 'Liste mouvements_stock';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

