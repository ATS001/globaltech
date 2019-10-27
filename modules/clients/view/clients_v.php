<?php
//array colomn
$array_column = array(
	array(
        'column' => 'clients.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'clients.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '13',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'clients.denomination',
        'type'   => '',
        'alias'  => 'denomination',
        'width'  => '30',
        'header' => 'Dénomination',
        'align'  => 'L'
    ),
    array(
        'column' => 'categorie_client.categorie_client',
        'type'   => '',
        'alias'  => 'categorie_client',
        'width'  => '15',
        'header' => 'Catégorie ',
        'align'  => 'C'
    ),
    array(
        'column' => "clients.credat",
        'type'   => 'date',
        'alias'  => 'date_client',
        'width'  => '8',
        'header' => 'Date Création',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '10',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Clients";
$html_data_table->task = 'clients';

$html_data_table->btn_return = array('task' =>'tdb');
$html_data_table->use_filter = true;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>