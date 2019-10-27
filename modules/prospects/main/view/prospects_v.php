<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 19-10-2019
//View
//array colomn
$array_column = array(
    array(
        'column' => 'prospects.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'Id',
        'align'  => 'C'
        ),
    array(
        'column' => 'reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Réference',
        'align'  => 'C'
        ),
    array(
        'column' => "concat(commerciaux.prenom,' ',commerciaux.nom)",
        'type'   => '',
        'alias'  => 'ID_COMMERCIAL',
        'width'  => '20',
        'header' => 'Commercial',
        'align'  => 'L'
        ),
    array(
        'column' => 'prospects.Raison_Sociale',
        'type'   => '',
        'alias'  => 'Raison_Sociale',
        'width'  => '20',
        'header' => 'Raison Sociale',
        'align'  => 'L'
        ),
    array(
        'column' => 'categorie_client.categorie_client',
        'type'   => '',
        'alias'  => 'OFFRE',
        'width'  => '15',
        'header' => 'Offre',
        'align'  => 'C'
        ),
    array(
        'column' => 'prospects.credat',
        'type'   => 'date',
        'alias'  => 'date_prospect',
        'width'  => '10',
        'header' => 'Date Création',
        'align'  => 'C'
        ),
    array(
        'column' => 'prospects.CA_PONDERE',
        'type'   => '',
        'alias'  => 'CA_PONDERE',
        'width'  => '10',
        'header' => 'CA Pondéré',
        'align'  => 'R'
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
$html_data_table->title_module = "Prospects";
$html_data_table->task = 'prospects';
$html_data_table->btn_return = array('task' =>'tdb');
$html_data_table->use_filter = true;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}