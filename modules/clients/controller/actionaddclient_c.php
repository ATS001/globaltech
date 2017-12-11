<?php
defined('_MEXEC') or die;
if(!MInit::crypt_tp('exec', null, 'D'))
{ 	
	$id     = Mreq::tp('exec');
	$idc    = MInit::crypt_tp('exec',$id);
	exit('0#<br>L\'action exécutée invalid contactez l\'administrateur '.$id.'  '.$idc);
}
//Action called from all button of this modul
$action = Mreq::tp('exec');


//Load_categorie by type
if($action == 'load_select_ville')
{
	$where = 'd.id_region=r.id and v.id_departement=d.id and r.id_pays='.MReq::tp('id');
	//$where = 'v.id_departement='.MReq::tp('id');
	//var_dump(MReq::tp('id'));
	$table = 'ref_ville v, ref_departement d, ref_region r';
	$value = 'v.id';
	$text  = 'v.ville';
	
	if($output = Mform::load_select($table, $value, $text, $where)){
		echo json_encode($output);
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Pas de ville trouvée ' ));
	}
}