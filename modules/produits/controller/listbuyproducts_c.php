<?php
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
    array(
        'column' => 'produits.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'stock.qte',
        'type'   => '',
        'alias'  => 'qte',
        'width'  => '10',
        'header' => 'Quantité',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'stock.prix_achat',
        'type'   => '',
        'alias'  => 'pa',
        'width'  => '10',
        'header' => 'Prix achat',
        'align'  => 'L'
    ),
    array(
        'column' => 'stock.prix_vente',
        'type'   => '',
        'alias'  => 'pv',
        'width'  => '15',
        'header' => 'Prix vente',
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
$list_data_table->tables = array('stock', 'produits');
//Set Jointure
$list_data_table->joint = 'stock.idproduit=produits.id and stock.mouvement= "E" AND stock.idproduit ='.Mreq::tp('id');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'stock';
//Set Task used for statut line
$list_data_table->task = 'buyproducts';
//Set File name for export
$list_data_table->file_name = 'liste_buyproducts';
//Set Title of report
$list_data_table->title_report = 'Liste achats';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
