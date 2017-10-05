<?php
$array_column = array(
  array(
        'column' => 'd_devis.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.order',
        'type'   => '',
        'alias'  => 'item',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.ref_produit',
        'type'   => '',
        'alias'  => 'ref_produit',
        'width'  => '10',
    ),
  array(
        'column' => 'produits.designation',
        'type'   => '',
        'alias'  => 'designation',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.prix_unitaire',
        'type'   => 'int',
        'alias'  => 'prix_unitaire',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.type_remise',
        'type'   => '',
        'alias'  => 'type_remise',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.remise_valeur',
        'type'   => 'int',
        'alias'  => 'remise_valeur',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.qte',
        'type'   => 'int',
        'alias'  => 'qte',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.total_ht',
        'type'   => 'int',
        'alias'  => 'total_ht',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.total_tva',
        'type'   => 'int',
        'alias'  => 'total_tva',
        'width'  => '10',
    ),
  array(
        'column' => 'd_devis.total_ttc',
        'type'   => 'int',
        'alias'  => 'total_ttc',
        'width'  => '10',
    ),
);
$tkn_frm = Mreq::tp('tkn_frm');
   
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('d_devis', 'produits');
//Set Jointure
$list_data_table->joint = "produits.id = d_devis.id_produit AND d_devis.tkn_frm = '$tkn_frm'";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'devis';
//Set Task used for statut line
$list_data_table->task = 'devis';
//This query no need notif statut
$list_data_table->need_notif = false;
//$list_data_table->debug = true;
//Print JSON DATA
print($list_data_table->Query_maker());
?>
  
