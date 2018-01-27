<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Controller Liste
$array_column = array(
    array(
        'column' => 'commerciaux.id',
        'type' => '',
        'alias' => 'id',
        'width' => '5',
        'header' => 'ID',
        'align' => 'C'
    ),
    //Complete Array fields here
    array(
        'column' => 'commerciaux.nom',
        'type' => '',
        'alias' => 'nom',
        'width' => '10',
        'header' => 'Nom',
        'align' => 'L'
    ),
    array(
        'column' => 'commerciaux.prenom',
        'type' => '',
        'alias' => 'prenom',
        'width' => '10',
        'header' => 'Prénom',
        'align' => 'L'
    ),
    array(
        'column' => 'commerciaux.is_glbt',
        'type' => '',
        'alias' => 'is_glbt',
        'width' => '10',
        'header' => 'Interne',
        'align' => 'L'
    ),
    array(
        'column' => 'commerciaux.cin',
        'type' => '',
        'alias' => 'cin',
        'width' => '10',
        'header' => 'CIN',
        'align' => 'L'
    ),
    array(
        'column' => 'commerciaux.rib',
        'type' => '',
        'alias' => 'rib',
        'width' => '10',
        'header' => 'RIB',
        'align' => 'L'
    ),
    array(
        'column' => 'commerciaux.tel',
        'type' => '',
        'alias' => 'tel',
        'width' => '10',
        'header' => 'Téléphone',
        'align' => 'L'
    ),
    array(
        'column' => 'commerciaux.email',
        'type' => '',
        'alias' => 'email',
        'width' => '10',
        'header' => 'E-mail',
        'align' => 'L'
    ),


    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '10',
        'header' => 'Statut',
        'align' => 'L'
    ),

);
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('commerciaux');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'commerciaux';
//Set Task used for statut line
$list_data_table->task = 'commerciale';
//Set File name for export
$list_data_table->file_name = 'commerciale';
//Set Title of report
$list_data_table->title_report = 'Liste commerciale';
//Print JSON DATA
if (!$data = $list_data_table->Query_maker()) {
    exit("0#" . $list_data_table->log);
} else {
    echo $data;
}

	

