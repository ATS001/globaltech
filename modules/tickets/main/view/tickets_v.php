<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View
//array colomn
$array_column = array(
    array(
        'column' => 'tickets.id',
        'type' => '',
        'alias' => 'id',
        'width' => '5',
        'header' => 'ID',
        'align' => 'C'
    ),
    //Complete Array fields here
    array(
        'column' => 'clients.denomination',
        'type' => '',
        'alias' => 'client',
        'width' => '15',
        'header' => 'Client',
        'align' => 'L'
    ),
    array(
        'column' => 'tickets.projet',
        'type' => '',
        'alias' => 'projet',
        'width' => '15',
        'header' => 'Projet',
        'align' => 'L'
    ),
    array(
        'column' => 'tickets.date_previs',
        'type' => 'date',
        'alias' => 'date_previs',
        'width' => '10',
        'header' => 'Date prévisionnelle',
        'align' => 'L'
    ),
   array(
        'column' => 'DATEDIFF(DATE(NOW()),DATE(tickets.credat))',
        'type' => '',
        'alias' => 'nbr',
        'width' => '10',
        'header' => 'Nbr jours',
        'align' => 'C'
    ),
    array(
        'column' => 'CONCAT(user_sys.fnom," ",user_sys.lnom)',
        'type' => '',
        'alias' => 'idtechnicien',
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
$html_data_table->title_module = "tickets";
$html_data_table->task = 'tickets';

if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}


















































