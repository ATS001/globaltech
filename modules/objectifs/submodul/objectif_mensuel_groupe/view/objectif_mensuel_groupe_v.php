<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel
//Created : 01-11-2018
//View
//array colomn
$array_column = array(
    array(
        'column' => 'objectif_annuel.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)',
        'type'   => '',
        'alias'  => 'commercial',
        'width'  => '15',
        'header' => 'Commercial',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_annuel.description',
        'type'   => '',
        'alias'  => 'description',
        'width'  => '15',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
       'column' => 'objectif_annuel.annee',
       'type'   => '',
       'alias'  => 'annee',
       'width'  => '4',
       'header' => 'Année',
       'align'  => 'C'
   ),
    array(
       'column' => 'statut',
       'type'   => '',
       'alias'  => 'statut',
       'width'  => '4',
       'header' => 'Statut',
       'align'  => 'C'
   ),
);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Objectifs par Commercial";
$html_data_table->task = 'objectif_mensuel_groupe';
$html_data_table->task_add = 'addobjectifgroupe';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
