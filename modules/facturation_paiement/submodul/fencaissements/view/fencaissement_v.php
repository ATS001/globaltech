<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fencaissements
//Created : 12-04-2020
//View
//array colomn
$array_column = array(
    array(
        'column' => 'encaissements.id',
        'type' => '',
        'alias' => 'id',
        'width' => '5',
        'header' => 'ID',
        'align' => 'C'
    ),
    array(
        'column' => 'encaissements.reference',
        'type' => '',
        'alias' => 'reference',
        'width' => '11',
        'header' => 'Référence',
        'align' => 'L'
    ),
    array(
        'column' => 'factures.client',
        'type' => '',
        'alias' => 'client',
        'width' => '14',
        'header' => 'Client',
        'align' => 'L'
    ),
    array(
        'column' => 'encaissements.designation',
        'type' => '',
        'alias' => 'des',
        'width' => '13',
        'header' => 'Désignation',
        'align' => 'L'
    ),
    array(
        'column' => 'factures.reference',
        'type' => '',
        'alias' => 'ref',
        'width' => '13',
        'header' => 'Facture',
        'align' => 'L'
    ),
    array(
        'column' => "REPLACE(FORMAT(encaissements.montant,0),',',' ')",
        'type' => '',
        'alias' => 'mt',
        'width' => '8',
        'header' => 'Montant',
        'align' => 'R'
    ),
    array(
        'column' => 'encaissements.date_encaissement',
        'type' => 'date',
        'alias' => 'date',
        'width' => '8',
        'header' => 'Date',
        'align' => 'C'
    ),
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '7',
        'header' => 'Statut',
        'align' => 'C'
    )
);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "fencaissement";
$html_data_table->task = 'fencaissement';
$html_data_table->btn_add_check=TRUE;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
