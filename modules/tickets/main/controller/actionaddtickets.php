<?php
defined('_MEXEC') or die;
if(!MInit::crypt_tp('exec', null, 'D'))
{ 	
	$id     = Mreq::tp('exec');
	$idc    = MInit::crypt_tp('exec',$id);
	exit('0#<br>L\'action exécutée invalid contactez l\'administrateur yyyyyyy '.$id.'  '.$idc);
}
//Action called from all button of this modul
$action = Mreq::tp('exec');


//Load_categorie by type
if ($action == 'load_select_categ') {
    $where = 'type_produit = ' . MReq::tp('id');
    $table = 'ref_categories_produits';
    $value = 'id';
    $text = 'categorie_produit';

    if ($output = Mform::load_select($table, $value, $text, $where)) {
        echo json_encode($output);
    } else {
        echo json_encode(array('error' => false, 'mess' => 'Pas de catégorie trouvée '));
    }
}

//Load produit by categorie
if ($action == 'load_select_produit') {
    $where = 'idcategorie = ' . MReq::tp('id');
    $table = 'produits';
    $value = 'id';
    $text = 'designation';

    if ($output = Mform::load_select($table, $value, $text, $where)) {
        echo json_encode($output);
    } else {
        echo json_encode(array('error' => false, 'mess' => 'Pas de produit trouvé'));
    }
}