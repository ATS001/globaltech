<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 02-01-2018
//Controller Liste

$array_column = array(
    array(
        'column' => 'compte_commerciale.id',
        'type' => '',
        'alias' => 'id',
        'width' => '5',
        'header' => 'ID',
        'align' => 'C'
    ),
    //Complete Array fields here
    array(
        'column' => "CONCAT(commerciaux.nom,' ',commerciaux.prenom)",
        'type' => '',
        'alias' => 'nom',
        'width' => '15',
        'header' => 'Commerciale',
        'align' => 'L'
    ),

    array(
        'column' => "compte_commerciale.objet",
        'type' => '',
        'alias' => 'obj',
        'width' => '15',
        'header' => 'Objet',
        'align' => 'L'
    ),

    array(
        'column' => "compte_commerciale.methode_payement",
        'type' => '',
        'alias' => 'methode',
        'width' => '6',
        'header' => 'Méthode paiement',
        'align' => 'L'
    ),

    array(
        'column' => 'compte_commerciale.debit',
        'type' => '',
        'alias' => 'debit',
        'width' => '10',
        'header' => 'Montant',
        'align' => 'L'
    ),

    array(
        'column' => 'compte_commerciale.date_debit',
        'type' => 'date',
        'alias' => 'date_debit',
        'width' => '10',
        'header' => 'Date',
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
$list_data_table->tables = array('compte_commerciale,commerciaux');
//Set Jointure
$list_data_table->joint = "compte_commerciale.id_commerciale=commerciaux.id AND compte_commerciale.id_credit = ".Mreq::tp('id_commission');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'compte_commerciale';
//Set Task used for statut line
$list_data_table->task = 'paiements';
//Set File name for export
$list_data_table->file_name = 'paiements';
//Set Title of report
$list_data_table->title_report = 'Liste paiements';

//Print JSON DATA
if (!$data = $list_data_table->Query_maker()) {
    exit("0#" . $list_data_table->log);
} else {
    echo $data;
}

	

