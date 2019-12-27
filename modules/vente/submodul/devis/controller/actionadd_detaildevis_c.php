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


//Delete line detail_devis
if($action == 'delete')
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{ 	
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
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
	$product = new Mdevis();
	$id_produit = Mreq::tp('id');
	if($product->get_info_produit($id_produit ))
	{ 
		//send data as json format
		echo json_encode($product->arr_prduit);
	}
     
}
//Get info commercial
if($action == 'info_commercial')
{
	$commercial = new Mcommerciale();
	$commercial->id_commerciale = Mreq::tp('id');
	if($commercial->get_commerciale())
	{ 
		$plafond_remise = $commercial->g('id_service') == 7 ? Msetting::get_set('plafond_remise_commercial') : 10; 
		//send data as json format
		$arr_return = array('remise' => $plafond_remise);
		echo json_encode($arr_return);
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Problème récuperation plafond remise'));
	}     
}
//Get info client
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

//Update prices

if($action == 'prices_update_on_devise_change')
{

//var_dump('OLD: '.MReq::tp('old_client'));
//var_dump('NEW: '.MReq::tp('id_client'));
//var_dump(MReq::tp('id_client'));
	$taux_devise= null;

	//Get Ste Devise 
	$info_ste = new MSte_info();
	$info_ste->id_ste = 1;
	$info_ste->get_ste_info();
	$ste_devise = $info_ste->ste_info['ste_id_devise'];
	//var_dump('Devise Societe: '.$ste_devise);

	$info_client = new Mclients();
	$info_client->id_client = MReq::tp('id_client');
	$info_client->get_client();
	$client_devise = $info_client->client_info['id_devise'];
	$client_banque = $info_client->client_info['id_banque'];
    //var_dump('Devise Client: '.$client_devise);
    //var_dump('Client'. $info_client->client_info['id']);

	$old_client = new Mclients();
	$old_client->id_client = MReq::tp('old_client');
	$old_client->get_client();
	$old_client_devise = $old_client->client_info['id_devise'];

	if ($client_devise  != $old_client_devise){
		if ($ste_devise != $client_devise){                      
			$taux_change = new Mtaux_change();
			$taux_change->get_taux_change_by_devise($client_devise);
			$taux_devise= $taux_change->taux_change_devise['conversion'];
			//var_dump($taux_change); 
			//var_dump($taux_devise); 
    	}

		$devis = new Mdevis();
		$arr_return = $devis->prices_update_on_devise_change(MReq::tp('tkn_frm'), $taux_devise);
		if($devis->error == true)
		{ if(MReq::tp('edit')==1)
	      {
			if($devis -> devis_update_on_client_change(Mreq::tp('id'), Mreq::tp('id_client'), $client_devise, $client_banque, Mreq::tp('tva'), Mreq::tp('totalht'), Mreq::tp('totalttc'),Mreq::tp('totaltva')))   
            {
                    $result = json_encode($arr_return);
					echo $result;
			}else{
					echo json_encode(array('error' => false, 'mess' => 'Problème modification du client !!!'.$devis->log));
			}
		  }else{
		  	$result = json_encode($arr_return);
					echo $result;
		  }

		}else{
					echo json_encode(array('error' => false, 'mess' => 'Problème adaptation taux de change !!!'.$devis->log));
		}
	}
}

//update tva for lines 

if($action == 'set_tva')
{
	$set_tva = new Mdevis();
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

	$set_commission = new Mdevis();
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

	$etat_produit_p  = Msetting::get_set('etat_produit', 'produit_valide_p');
	$etat_produit_ap = Msetting::get_set('etat_produit', 'produit_valide_ap');
	$etat_en_stock   = Msetting::get_set('etat_produit', 'en_stock');
    $etat_stock_faible = Msetting::get_set('etat_produit', 'stock_faible');
    $etat_stock_epuise = Msetting::get_set('etat_produit', 'stock_epuise');
    $where = ' etat in('.$etat_produit_p.','.$etat_produit_ap.','.$etat_en_stock.','.$etat_stock_faible.','.$etat_stock_epuise.') AND idcategorie = '.MReq::tp('id');

	
	$table = 'produits';
	$value = 'id';
	$text  = 'designation';
	
	if($output = Mform::load_select($table, $value, $text, $where)){
		echo json_encode($output);
	}else{
		echo json_encode(array('error' => false, 'mess' => 'Pas de produit trouvé' ));
	}	
}