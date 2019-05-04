<?php
//array colomn
$array_column = array(
    array(
        'column' => 'contrats.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '14',
        'header' => 'Référence',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.reference',
        'type'   => '',
        'alias'  => 'devis',
        'width'  => '14',
        'header' => 'Devis',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.date_contrat',
        'type'   => 'date',
        'alias'  => 'date_contrat',
        'width'  => '8',
        'header' => 'Date Contrat',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT(clients.reference, " - ",clients.denomination)',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '20',
        'header' => 'client',
        'align'  => 'L'
    ),
    array(
        'column' => 'contrats.date_effet',
        'type'   => 'date',
        'alias'  => 'date_effet',
        'width'  => '8',
        'header' => 'Date Début',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.date_fin',
        'type'   => 'date',
        'alias'  => 'date_fin',
        'width'  => '8',
        'header' => 'Date Fin',
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
$html_data_table->title_module = "Abonnement";
$html_data_table->task = 'contrats';


if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
