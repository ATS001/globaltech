<?php
//array colomn
$array_column = array(
	array(
        'column' => 'factures.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'factures.ref',
        'type'   => '',
        'alias'  => 'ref',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'factures.total_paye',
        'type'   => '',
        'alias'  => 'tp',
        'width'  => '10',
        'header' => 'Totale payé',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'factures.tva',
        'type'   => '',
        'alias'  => 'tva',
        'width'  => '10',
        'header' => 'TVA',
        'align'  => 'L'
    ),
    array(
        'column' => 'factures.client',
        'type'   => '',
        'alias'  => 'clt',
        'width'  => '15',
        'header' => 'Client',
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
$list_data_table->tables = array('factures');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'factures';
//Set Task used for statut line
$list_data_table->task = 'factures';
//Set File name for export
$list_data_table->file_name = 'liste_factures';
//Set Title of report
$list_data_table->title_report = 'Liste factures';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
