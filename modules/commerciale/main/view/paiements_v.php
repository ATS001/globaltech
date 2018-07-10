<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 23-06-2018
//View

$commission = new Mcommission();
//Set ID of Module with POST id
$commission->id_commission = Mreq::tp('id');
$commission->get_commission();

$id_commission = Mreq::tp('id');
$id_commerciale=$commission->commission_info['id_commerciale'];


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
        'width' => '10',
        'header' => 'Méthode paiement',
        'align' => 'C'
    ),

    array(
        'column' => 'compte_commerciale.debit',
        'type' => '',
        'alias' => 'debit',
        'width' => '10',
        'header' => 'Montant',
        'align' => 'R'
    ),

    array(
        'column' => 'compte_commerciale.date_debit',
        'type' => 'date',
        'alias' => 'date_debit',
        'width' => '10',
        'header' => 'Date',
        'align' => 'C'
    ),    
 
    array(
        'column' => 'statut',
        'type' => '',
        'alias' => 'statut',
        'width' => '10',
        'header' => 'Statut',
        'align' => 'C'
    ),

);

//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Paiement";
$html_data_table->task = 'paiements';


//si t as besoin d'envoyer data ajoute key data Ã  Array ex: 'data' => 'id=$id'
$html_data_table->btn_return = array('task' => 'commissions', 'title' => 'Retour liste commissions','data' => MInit::crypt_tp('id', $id_commerciale));
$html_data_table->task = 'paiements';
$html_data_table->js_extra_data = "id_commission=$id_commission";
$html_data_table->btn_add_data = MInit::crypt_tp('id_commission', $id_commission);

if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}

















































