<?php
//array colomn
$array_column = array(
	array(
        'column' => 'ref_types_produits.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'ref_types_produits.type_produit',
        'type'   => '',
        'alias'  => 'type_produit',
        'width'  => '15',
        'header' => 'Type Produit',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('ref_types_produits');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'ref_types_produits';
//Set Task used for statut line
$list_data_table->task = 'types_produits';
//Set File name for export
$list_data_table->file_name = 'liste_types_produits';
//Set Title of report
$list_data_table->title_report = 'Liste Types Produits';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
