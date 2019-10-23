<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//View
//array colomn
$array_column = array(
    array(
        'column' => 'visites.id',
        'type' => '',
        'alias' => 'id',
        'width' => '3',
        'header' => 'ID',
        'align' => 'C'
    ),
    //Complete Array fields here
    array(
        'column' => 'visites.commerciale',
        'type' => '',
        'alias' => 'commerciale',
        'width' => '14',
        'header' => 'Commerciale',
        'align' => 'L'
    ),
    array(
        'column' => 'visites.raison_sociale',
        'type' => '',
        'alias' => 'raison_sociale',
        'width' => '14',
        'header' => 'Raison sociale',
        'align' => 'L'
    ),
    array(
        'column' => 'visites.nature_visite',
        'type' => '',
        'alias' => 'nature_visite',
        'width' => '10',
        'header' => 'Client / Prospect',
        'align' => 'C'
    ),
    array(
        'column' => 'visites.date_visite',
        'type' => 'date',
        'alias' => 'date_visite',
        'width' => '8',
        'header' => 'Date Visite',
        'align' => 'C'
    ),
    array(
        'column' => 'visites.interlocuteur',
        'type' => '',
        'alias' => 'interlocuteur',
        'width' => '14',
        'header' => 'Interlocuteur',
        'align' => 'L'
    ),
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '5',
        'header' => 'Statut',
        'align' => 'C'
    ),
);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "visites";
$html_data_table->task = 'visites';

if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}


















































