<?php
//array colomn
$array_column = array(
	array(
        'column' => 'categorie_client.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'categorie_client.categorie_client',
        'type'   => '',
        'alias'  => 'categorie_client',
        'width'  => '15',
        'header' => 'Catégorie',
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
$list_data_table->tables = array('categorie_client');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'categorie_client';
//Set Task used for statut line
$list_data_table->task = 'categorie_client';
//Set File name for export
$list_data_table->file_name = 'liste_categorie_client';
//Set Title of report
$list_data_table->title_report = 'Liste Catégorie Clients';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
