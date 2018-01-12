<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 02-01-2018
//View

$commerciale = new Mcommerciale();
//Set ID of Module with POST id
$commerciale->id_commerciale = Mreq::tp('id');
$commerciale->get_commerciale();
$id = Mreq::tp('id');

//array colomn
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
        'column' => 'compte_commerciale.credit',
        'type' => '',
        'alias' => 'commission',
        'width' => '15',
        'header' => 'Montant Commission',
        'align' => 'L'
    ),

    array(
        // 'column' => '(SELECT IFNULL(MAX(compte_commerciale.debit),0) FROM compte_commerciale,compte_commerciale crdt WHERE compte_commerciale.id_credit=crdt.id)',
        'column' => '( SELECT  IFNULL (SUM(cc.debit),0) FROM compte_commerciale cc WHERE cc.`id_commerciale`=compte_commerciale.`id_commerciale`
                        AND cc.`id_credit`=compte_commerciale.id AND cc.`id_credit` IS NOT NULL) ',
        'type' => '',
        'alias' => 'debit',
        'width' => '15',
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
        'width' => '15',
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "commissions";
$html_data_table->task = 'commissions';

//si t as besoin d'envoyer data ajoute key data Ã  Array ex: 'data' => 'id=$id'
$html_data_table->btn_return = array('task' => 'commerciale', 'title' => 'Retour liste commerciaux');
$html_data_table->task = 'commissions';
$html_data_table->js_extra_data = "id_commerciale=$id";
$html_data_table->btn_add_data = MInit::crypt_tp('id_commerciale',$id);


if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}


















































