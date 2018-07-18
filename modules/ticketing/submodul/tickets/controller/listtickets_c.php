<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//Controller Liste
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
        'column' => 'tickets.credat',
        'type' => 'date',
        'alias' => 'credat',
        'width' => '10',
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
THEN IFNULL(DATEDIFF(DATE(tickets.`date_previs`),DATE(NOW())),0) 
WHEN tickets.`etat` = 3 
THEN IFNULL(DATEDIFF(DATE(tickets.`date_previs`),DATE(tickets.`date_realis`)),0) END) ',
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
        'width' => '15',
        'header' => 'Technicien',
        'align' => 'L'
    ),
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '8',
        'header' => 'Statut',
        'align' => 'C'
    ),
);
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('tickets LEFT JOIN users_sys ON users_sys.id=tickets.id_technicien LEFT JOIN clients ON clients.id=tickets.id_client');

$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'tickets';
//Set Task used for statut line
$list_data_table->task = 'tickets';
//Set File name for export
$list_data_table->file_name = 'tickets';
//Set Title of report
$list_data_table->title_report = 'Liste tickets';
//Print JSON DATA
if (!$data = $list_data_table->Query_maker()) {
    exit("0#" . $list_data_table->log);
} else {
    echo $data;
}

	

