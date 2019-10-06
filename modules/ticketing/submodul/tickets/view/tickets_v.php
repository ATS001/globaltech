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
        'width' => '4',
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
        'column' => 'sites.reference',
        'type' => '',
        'alias' => 'site',
        'width' => '6',
        'header' => 'Site',
        'align' => 'C'
    ),
    array(
        'column' => 'tickets.credat',
        'type' => 'date',
        'alias' => 'credat',
        'width' => '8',
        'header' => 'Date création',
        'align' => 'C'
    ),
    /*
    array(
        'column' => 'tickets.date_previs',
        'type' => 'date',
        'alias' => 'date_previs',
        'width' => '10',
        'header' => 'Date prévisionnelle',
        'align' => 'C'
    ),
     * 
     */
   array(
        'column' => '(CASE WHEN tickets.`etat` <> 3 
THEN IFNULL(DATEDIFF(DATE(tickets.`date_affectation`),DATE(NOW())),0) 
WHEN tickets.`etat` = 3 
THEN IFNULL(DATEDIFF(DATE(tickets.`date_affectation`),DATE(tickets.`date_realis`)),0) END) ',
        'type' => '',
        'alias' => 'nbr',
        'width' => '8',
        'header' => 'Nbr jours',
        'align' => 'C'
    ),
     
    array(
        'column' => 'CONCAT(users_sys.fnom," ",users_sys.lnom)',
        'type' => '',
        'alias' => 'idtechnicien',
        'width' => '13',
        'header' => 'Technicien',
        'align' => 'L'
    ),
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '7',
        'header' => 'Statut',
        'align' => 'C'
    ),
);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Ticket";
$html_data_table->task = 'tickets';

if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}


















































