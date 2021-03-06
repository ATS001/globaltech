<?php
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
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('produits', 'ref_types_produits','ref_categories_produits,entrepots');
//Set Jointure
$list_data_table->joint = 'produits.idtype=ref_types_produits.id AND produits.idcategorie=ref_categories_produits.id and entrepots.id=produits.id_entrepot';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'produits';
//Set Task used for statut line
$list_data_table->task = 'produits';
//Set File name for export
$list_data_table->file_name = 'liste_produits';
//Set Title of report
$list_data_table->title_report = 'Liste produits';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
