<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//Controller Liste
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
        'column' => 'CONCAT(commerciaux.nom," ",commerciaux.prenom)',
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
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('visites','commerciaux');
//Set Jointure
$list_data_table->joint = 'visites.commerciale = commerciaux.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'visites';
//Set Task used for statut line
$list_data_table->task = 'visites';
//Set File name for export
$list_data_table->file_name = 'visites';
//Set Title of report
$list_data_table->title_report = 'Liste visites';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

