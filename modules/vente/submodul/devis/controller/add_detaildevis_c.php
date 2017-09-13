<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('add_detaildevis',false))
{
	$posted_data = array(
		'tkn_frm'         => Mreq::tp('tkn_frm') ,
		'id_produit'      => Mreq::tp('id_produit') ,
		'ref_produit'     => Mreq::tp('ref_produit') ,
		'qte'             => Mreq::tp('qte') ,
		'prix_unitaire'   => Mreq::tp('prix_unitaire') ,
		'tva'             => 'O' ,
		'type_remise_d'   => Mreq::tp('type_remise_d') ,
		'remise_valeur_d' => Mreq::tp('remise_valeur_d') ,
		'total_ht'        => Mreq::tp('total_ht') ,
		'total_ttc'       => Mreq::tp('total_ttc') ,
		'total_tva'       => Mreq::tp('total_tva') ,
		);

	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
	if($posted_data['id_produit'] == NULL){

		$empty_list .= "<li>Produit / Service</li>";
		$checker = 1;
	}
	if($posted_data['qte'] == NULL){

		$empty_list .= "<li>Quantité</li>";
		$checker = 1;
	}
	if($posted_data['prix_unitaire'] == NULL OR $posted_data['prix_unitaire'] == '0' ){

		$empty_list .= "<li>Prix unitaire</li>";
		$checker = 1;
	}
	if($posted_data['type_remise_d'] == NULL OR !in_array($posted_data['type_remise_d'],  array( 'P','M' ))){

		$empty_list .= "<li>Type de remise</li>";
		$checker = 1;
	}


	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}


	  //End check empty element


	$new_devis_d = new  Mdevis($posted_data);



  //execute Insert returne false if error
	if($new_devis_d->save_new_details_devis($posted_data['tkn_frm'])){

		exit("1#".$new_devis_d->log."#".$new_devis_d->sum_total_ht);
	}else{

		exit("0#".$new_devis_d->log);
	}
	
}

view::load_view('add_detaildevis');