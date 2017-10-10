<?php
//array colomn
$array_column = array(
	array(
        'column' => 'ref_unites_vente.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'ref_unites_vente.unite_vente',
        'type'   => '',
        'alias'  => 'unite_vente',
        'width'  => '15',
        'header' => 'Unité de vente',
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
$list_data_table->tables = array('ref_unites_vente');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'ref_unites_vente';
//Set Task used for statut line
$list_data_table->task = 'unites_vente';
//Set File name for export
$list_data_table->file_name = 'liste_unite_ventes';
//Set Title of report
$list_data_table->title_report = 'Liste des unités de vente';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
