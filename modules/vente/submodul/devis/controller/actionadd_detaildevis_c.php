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


//Delete line detail_devis
if($action == 'delete')
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{ 	
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur zzzzzzzz');
	}
//Initialise
	$id     = Mreq::tp('id');
	$idc    = MInit::crypt_tp('id',$id);
	$del_detail = new Mdevis();
	if(!$del_detail->Delete_detail_devis($id))
	{
		exit('0#'.$del_detail->log);
	}else{
		exit('1#'.$del_detail->log);
	}

}
//Get prouduit info
if($action == 'produit_info')
{
	$id_produit = Mreq::tp('id');
	$sql = "SELECT * FROM produits WHERE id = $id_produit LIMIT 0,1";

	global $db;

//exit($sqlRec);
	if (!$db->Query($sql)) $db->Kill($db->Error()." SQLREC $sql");

	$array_produit = $db->RowArray();
	$array_product_out = array(
		'prix_base' => '<span class="help-block red returned_span">Ce Produit était vendu à '.$array_produit['pu'].' FCFA</span>',
		'prix'      => $array_produit['pu'],
		'ref'       => $array_produit['ref'],

		);

echo json_encode($array_product_out);  // send data as json format
}

if($action == 'info_client')
{
	$info_client = new Mclients();
	$info_client->id_client = MReq::tp('id');
	if($info_client->get_client())
	{
		$result = json_encode($info_client->client_info);
        echo $result;
	}else{
		exit('Error');
	} 
}

