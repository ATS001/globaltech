<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
//array colomn
$array_column = array(
	array(
        'column' => 'produits.id',
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
        'column' => 'ref_types_produits.type_produit',
        'type'   => '',
        'alias'  => 'type',
        'width'  => '10',
        'header' => 'Type',
        'align'  => 'L'
    ),
     array(
        'column' => 'ref_categories_produits.categorie_produit',
        'type'   => '',
        'alias'  => 'cat_prod',
        'width'  => '10',
        'header' => 'Catégorie produit',
        'align'  => 'L'
    ),
   array(
        'column' => 'entrepots.libelle',
        'type'   => '',
        'alias'  => 'entrepot',
        'width'  => '10',
        'header' => 'Entrepôt',
        'align'  => 'L'
    ),
    array(
        'column' => 'produits.designation',
        'type'   => '',
        'alias'  => 'des',
        'width'  => '15',
        'header' => 'Désignation',
        'align'  => 'L'
    ),
    array(
        'column' => '(SELECT qte_act FROM qte_actuel WHERE id_produit = produits.id)',
        'type'   => '',
        'alias'  => 'stmin',
        'width'  => '10',
        'header' => 'Stock',
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
$html_data_table->title_module = "produits";
$html_data_table->task = 'produits';



if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
