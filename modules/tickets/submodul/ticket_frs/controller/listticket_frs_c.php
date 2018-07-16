<?php

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//Controller Liste
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
        'column' => 'tickets_fournisseurs.id_fournisseur',
        'type' => '',
        'alias' => 'id_fournisseur',
        'width' => '15',
        'header' => 'Fournisseur',
        'align' => 'L'
    ),
    array(
        'column' => 'tickets_fournisseurs.date_incident',
        'type' => 'Date',
        'alias' => 'date_incident',
        'width' => '15',
        'header' => 'Date incident',
        'align' => 'L'
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
        'column' => 'tickets_fournisseurs.id_technicien',
        'type' => '',
        'alias' => 'id_technicien',
        'width' => '15',
        'header' => 'Technicien',
        'align' => 'L'
    ),
    array(
        'column' => 'tickets_fournisseurs.date_affectation',
        'type' => '',
        'alias' => 'date_affectation',
        'width' => '15',
        'header' => 'Date affectation',
        'align' => 'L'
    ),
       
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '15',
        'header' => 'Statut',
        'align' => 'L'
    ),
);
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('tickets_fournisseurs');
//Set Jointure
$list_data_table->tables = array('tickets_fournisseurs LEFT JOIN users_sys ON users_sys.id=tickets_fournisseurs.id_technicien '
                                                    . 'LEFT JOIN fournisseurs ON fournisseurs.id=tickets_fournisseurs.id_fournisseur');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'tickets_fournisseurs';
//Set Task used for statut line
$list_data_table->task = 'ticket_frs';
//Set File name for export
$list_data_table->file_name = 'ticket_frs';
//Set Title of report
$list_data_table->title_report = 'Liste ticket_frs';
//Print JSON DATA
if (!$data = $list_data_table->Query_maker()) {
    exit("0#" . $list_data_table->log);
} else {
    echo $data;
}

	

