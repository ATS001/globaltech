<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//View
//array colomn
$array_column = array(
    array(
        'column' => 'tickets_fournisseurs.id',
        'type' => '',
        'alias' => 'id',
        'width' => '5',
        'header' => 'ID',
        'align' => 'C'
    ),
    //Complete Array fields here
    array(
        'column' => 'fournisseurs.denomination',
        'type' => '',
        'alias' => 'id_fournisseur',
        'width' => '15',
        'header' => 'Fournisseur',
        'align' => 'L'
    ),
    array(
        'column' => 'tickets_fournisseurs.date_incident',
        'type' => 'date',
        'alias' => 'date_incident',
        'width' => '10',
        'header' => 'Date incident',
        'align' => 'C'
    ),
    array(
        'column' => 'tickets_fournisseurs.nature_incident',
        'type' => '',
        'alias' => 'nature_incident',
        'width' => '15',
        'header' => 'Nature incident',
        'align' => 'L'
    ),
    array(
        'column' => 'CONCAT(users_sys.fnom," ",users_sys.lnom)',
        'type' => '',
        'alias' => 'id_technicien',
        'width' => '15',
        'header' => 'Technicien',
        'align' => 'L'
    ),
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '10',
        'header' => 'Statut',
        'align' => 'C'
    ),
);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Ticket Fournisseur";
$html_data_table->task = 'ticket_frs';
$html_data_table->debug = true;

if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}


















































