<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Ticket Fournisseur";
$html_data_table->task = 'ticket_frs';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































