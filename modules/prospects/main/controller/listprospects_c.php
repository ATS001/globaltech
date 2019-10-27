<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospect
//Created : 19-10-2019
//Controller Liste
$array_column = array(
    array(
        'column' => 'prospects.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'Id',
        'align'  => 'C'
        ),
    array(
        'column' => 'reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Réference',
        'align'  => 'C'
        ),
    array(
        'column' => "concat(commerciaux.prenom,' ',commerciaux.nom)",
        'type'   => '',
        'alias'  => 'ID_COMMERCIAL',
        'width'  => '20',
        'header' => 'Commercial',
        'align'  => 'L'
        ),
    array(
        'column' => 'prospects.Raison_Sociale',
        'type'   => '',
        'alias'  => 'Raison_Sociale',
        'width'  => '20',
        'header' => 'Raison Sociale',
        'align'  => 'L'
        ),
    array(
        'column' => 'categorie_client.categorie_client',
        'type'   => '',
        'alias'  => 'OFFRE',
        'width'  => '15',
        'header' => 'Offre',
        'align'  => 'C'
        ),
    array(
        'column' => "prospects.credat",
        'type'   => 'date',
        'alias'  => 'date_prospect',
        'width'  => '10',
        'header' => 'Date Création',
        'align'  => 'C'
        ),
    array(
        'column' => 'prospects.CA_PONDERE',
        'type'   => '',
        'alias'  => 'CA_PONDERE',
        'width'  => '10',
        'header' => 'CA Pondéré',
        'align'  => 'R'
        ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '10',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );

//Show line for owner
$only_owner = null;
$id_service = session::get('service');
if($id_service == 7)
{
    $only_owner = ' AND prospects.creusr = '.session::get('userid');
}

//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('prospects, categorie_client,commerciaux');
//Set Jointure
$list_data_table->joint = 'prospects.offre = categorie_client.id and prospects.id_commercial = commerciaux.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'prospects';
//Set Task used for statut line
$list_data_table->task = 'prospects';
//Set File name for export
$list_data_table->file_name = 'prospects';
//Set Title of report
$list_data_table->title_report = 'Liste des prospects';

//Set Fliter setting
$list_data_table->data_filter = array('id' => array('int','5'), 'Raison_Sociale' => array('text','9'), 'date_prospect' => array('date','5'), 'ID_COMMERCIAL' => array('text','5') );

//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}