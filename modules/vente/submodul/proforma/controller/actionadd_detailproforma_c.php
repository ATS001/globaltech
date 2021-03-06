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


//Delete line detail_proforma
if($action == 'delete')
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{ 	
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
	}
//Initialise
	$id     = Mreq::tp('id');
	$idc    = MInit::crypt_tp('id',$id);
	$del_detail = new Mproforma();
	if(!$del_detail->Delete_detail_proforma($id))
	{
		exit('0#'.$del_detail->log);
	}else{
		exit('1#'.$del_detail->log);
	}

}

//Get prouduit info
if($action == 'produit_info')
{
	$product = new Mdevis();
	$id_produit = Mreq::tp('id');
	if($product->get_info_produit($id_produit ))
	{ 
		//send data as json format
		echo json_encode($product->arr_prduit);
	}
     
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

//update tva for lines 

if($action == 'set_tva')
{
	$set_tva = new Mproforma();
	$arr_return = $set_tva->set_tva_for_detail_on_change_main_tva(MReq::tp('tkn_frm'), MReq::tp('tva'));
	if($set_tva->error == true)
	{
		$result = json_encode($arr_return);
		echo $result;
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Adaptation TVA non réussie '.$set_tva->log ));
	}
}

//update commission for lines 

if($action == 'set_commission')
{
	$set_commission = new Mproforma();
	$arr_return = $set_commission->set_commission_for_detail_on_change_main_commission(MReq::tp('tkn_frm'), MReq::tp('commission'), MReq::tp('type_commission'));
	if($set_commission->error == true)
	{
		$result = json_encode($arr_return);
		echo $result;
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Adaptation Commission non réussie '.$set_commission->log ));
	}
}

//Load_categorie by type
if($action == 'load_select_categ')
{
	$where = 'type_produit = '.MReq::tp('id');
	$table = 'ref_categories_produits';
	$value = 'id';
	$text  = 'categorie_produit';
	
	if($output = Mform::load_select($table, $value, $text, $where)){
		echo json_encode($output);
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Pas de catégorie trouvée ' ));
	}
}

//Load produit by categorie
if($action == 'load_select_produit')
{
	$where = 'idcategorie = '.MReq::tp('id');
	$table = 'produits';
	$value = 'id';
	$text  = 'designation';
	
	if($output = Mform::load_select($table, $value, $text, $where)){
		echo json_encode($output);
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Pas de produit trouvé' ));
	}
	
	
}
