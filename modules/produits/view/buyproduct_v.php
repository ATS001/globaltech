<?php
//First check target no Hack
if (!defined('_MEXEC')) die();

$produit = new Mproduit();
//Set ID of Module with POST id
$produit->id_produit = Mreq::tp('id');
$produit->get_produit();
$id = Mreq::tp('id');

//array colomn
$array_column = array(
	array(
        'column' => 'stock.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'produits.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'stock.qte',
        'type'   => '',
        'alias'  => 'qte',
        'width'  => '10',
        'header' => 'Quantité',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'stock.prix_achat',
        'type'   => '',
        'alias'  => 'pa',
        'width'  => '10',
        'header' => 'Prix achat',
        'align'  => 'L'
    ),
    array(
        'column' => 'stock.prix_vente',
        'type'   => '',
        'alias'  => 'pv',
        'width'  => '15',
        'header' => 'Prix vente',
        'align'  => 'C'
    ),
    array(
        'column' => 'stock.date_achat',
        'type'   => 'date',
        'alias'  => 'da',
        'width'  => '15',
        'header' => 'Date achat',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );

//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Achat";
$html_data_table->task = 'buyproduct';

//si t as besoin d'envoyer data ajoute key data Ã  Array ex: 'data' => 'id=$id'
$html_data_table->btn_return = array('task' => 'produits', 'title' => 'Retour liste produits');
$html_data_table->task = 'buyproduct';
$html_data_table->js_extra_data = "id_produit=$id";
$html_data_table->btn_add_data = MInit::crypt_tp('id_produit', $id);

if (!$data = $html_data_table->table_html()) {
    exit("0#" . $html_data_table->log);
} else {
    echo $data;
}
