<?php
//First check target no Hack
if(!defined('_MEXEC'))die();



$array_column = array(
    array(
        'column' => 'objectif_mensuels.id',
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
        'column' => 'objectif_mensuels.description',
        'type'   => '',
        'alias'  => 'description',
        'width'  => '15',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_mensuels.objectif',
        'type'   => 'int',
        'alias'  => 'objectif',
        'width'  => '15',
        'header' => 'Objectif',
        'align'  => 'R'
    ),
    array(
        'column' => 'objectif_mensuels.realise',
        'type'   => 'int',
        'alias'  => 'realise',
        'width'  => '15',
        'header' => 'Réalisation',
        'align'  => 'L'
    ),
    array(
        'column' => 'CONCAT(objectif_mensuels.seuil,\' %\')',
        'type'   => '',
        'alias'  => 'seuil',
        'width'  => '15',
        'header' => 'Réalisation',
        'align'  => 'C'
    ),

    /*array(
        'column' => 'objectif_mensuels.date_s',
        'type'   => 'date',
        'alias'  => 'date_s',
        'width'  => '15',
        'header' => 'Date début',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_mensuels.date_e',
        'type'   => 'date',
        'alias'  => 'date_e',
        'width'  => '15',
        'header' => 'Date fin',
        'align'  => 'L'
    ),*/


    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
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
$list_data_table->tables = array('objectif_mensuels', 'commerciaux');
//Set Jointure
$list_data_table->joint = 'commerciaux.id = objectif_mensuels.id_commercial AND objectif_mensuels.annee = '.Mreq::tp('annee'). ' AND id_commercial = ' .Mreq::tp('id_commercial');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'objectif_mensuels';
//Set Task used for statut line
$list_data_table->task = 'objectif_mensuel';
//Set File name for export
$list_data_table->file_name = 'objectif_mensuel';
//Set Title of report
$list_data_table->title_report = 'Liste objectif_mensuel';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}
