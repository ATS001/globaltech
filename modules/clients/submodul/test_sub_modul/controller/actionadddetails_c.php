<?php
$id_produit = Mreq::tp('id');
$sql = "SELECT * FROM produits WHERE id = $id_produit LIMIT 0,1";

global $db;

//exit($sqlRec);
if (!$db->Query($sql)) $db->Kill($db->Error()." SQLREC $sql");

$array_produit = $db->RowArray();

$array_product_out = array(
	'prix_base' => '<span class="col-xs-12 help-block red returned_span">Ce Produit était vendu à '.$array_produit['pu'].' FCFA</span>',
	'prix' => $array_produit['pu'],
	'ref' => $array_produit['ref'],

	);

echo json_encode($array_product_out);  // send data as json format