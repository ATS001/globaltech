<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel
//Created : 01-11-2018
//Controller Liste
$array_column = array(
    array(
        'column' => 'objectif_annuel.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)',
        'type'   => '',
        'alias'  => 'commercial',
        'width'  => '15',
        'header' => 'Commercial',
        'align'  => 'L'
    ),
    array(
          'column' => 'objectif_annuel.description',
        'type'   => '',
        'alias'  => 'description',
        'width'  => '15',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
       'column' => 'objectif_annuel.annee',
       'type'   => '',
       'alias'  => 'annee',
       'width'  => '4',
       'header' => 'Année',
       'align'  => 'C'
   ),
    array(
       'column' => 'statut',
       'type'   => '',
       'alias'  => 'statut',
       'width'  => '4',
       'header' => 'Statut',
       'align'  => 'C'
   ),
);
//Check if is commercial show only his lines
$only_my_lines = null;
if(session::get('service') == 7){
    $only_my_lines = 'AND commerciaux.id_user_sys = '.session::get('userid');
}
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('objectif_annuel', 'commerciaux');
//Set Jointure
$list_data_table->joint = 'commerciaux.id = objectif_annuel.id_commercial '.$only_my_lines;
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'objectif_annuel';
//Set Task used for statut line
$list_data_table->task = 'objectif_mensuel_groupe';
//Set File name for export
$list_data_table->file_name = 'objectif_mensuel_groupe';
//Set Title of report
$list_data_table->title_report = 'Liste objectif_annuel';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}
