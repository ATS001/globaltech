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
        'column' => "compte_commerciale.type",
        'type' => '',
        'alias' => 'type',
        'width' => '6',
        'header' => 'Type',
        'align' => 'L'
    ),

    array(
        'column' => 'compte_commerciale.credit',
        'type' => '',
        'alias' => 'commission',
        'width' => '10',
        'header' => 'Commission',
        'align' => 'L'
    ),

    array(
        // 'column' => '(SELECT IFNULL(MAX(compte_commerciale.debit),0) FROM compte_commerciale,compte_commerciale crdt WHERE compte_commerciale.id_credit=crdt.id)',
        'column' => '( SELECT  IFNULL (SUM(cc.debit),0) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
                        AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL) ',
        'type' => '',
        'alias' => 'debit',
        'width' => '10',
        'header' => 'Payé',
        'align' => 'L'
    ),

    array(
        //'column' => 'compte_commerciale.credit - (SELECT IFNULL(MAX(compte_commerciale.debit),0) FROM compte_commerciale,compte_commerciale crdt WHERE compte_commerciale.id_credit=crdt.id)',
        'column' => 'compte_commerciale.`credit`- IF( (SELECT COUNT(*) FROM compte_commerciale cpt WHERE cpt.`id_commerciale`=compte_commerciale.`id_commerciale`
  AND cpt.`id_credit`=compte_commerciale.id AND cpt.`id_credit` IS NOT NULL)= 0, 0,  
  ( SELECT SUM(cc.debit) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
  AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL))',
        'type' => '',
        'alias' => 'reste',
        'width' => '10',
        'header' => 'Reste',
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
$list_data_table->joint = "compte_commerciale.`id_credit` IS NULL AND commerciaux.id=compte_commerciale.id_commerciale AND compte_commerciale.id_commerciale = ".Mreq::tp('id_commerciale');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'compte_commerciale';
//Set Task used for statut line
$list_data_table->task = 'commissions';
//Set File name for export
$list_data_table->file_name = 'commissions';
//Set Title of report
$list_data_table->title_report = 'Liste commissions';
//$list_data_table->debug=true;

//Print JSON DATA
if (!$data = $list_data_table->Query_maker()) {
    exit("0#" . $list_data_table->log);
} else {
    echo $data;
}

	

