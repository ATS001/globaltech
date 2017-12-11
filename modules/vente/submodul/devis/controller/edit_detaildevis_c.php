<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('edit_detaildevis',false))
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{  
   // returne message error red to client 
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	$posted_data = array(
		'id'              => Mreq::tp('id') ,
		'tkn_frm'         => Mreq::tp('tkn_frm') ,
		'checker_tkn_frm' => Mreq::tp('checker_tkn_frm') ,
		'id_produit'      => Mreq::tp('id_produit') ,
		'ref_produit'     => Mreq::tp('ref_produit') ,
		'qte'             => Mreq::tp('qte') ,
		'prix_unitaire'   => Mreq::tp('prix_unitaire') ,
		'tva_d'           => Mreq::tp('tva_d') , 
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
	if($posted_data['qte'] == NULL OR !is_numeric($posted_data['qte'])){

		$empty_list .= "<li>Quantité</li>";
		$checker = 1;
	}
	if($posted_data['total_tva'] == NULL OR !is_numeric($posted_data['total_tva'])){

		$empty_list .= "<li>Total TVA</li>";
		$checker = 1;
	}
	if($posted_data['remise_valeur_d'] == NULL OR !is_numeric($posted_data['remise_valeur_d'])){

		$empty_list .= "<li>Valeur Remise</li>";
		$checker = 1;
	}
	if($posted_data['total_ht'] == NULL OR !is_numeric($posted_data['total_ht']) OR $posted_data['total_ht'] == 0){

      $empty_list .= "<li>Total HT</li>";
      $checker = 1;
    }
    if($posted_data['total_ttc'] == NULL OR !is_numeric($posted_data['total_ttc']) OR $posted_data['total_ttc'] == 0){

      $empty_list .= "<li>Total TTC</li>";
      $checker = 1;
    }
	if($posted_data['prix_unitaire'] == NULL OR $posted_data['prix_unitaire'] == '0' OR !is_numeric($posted_data['prix_unitaire'])){

		$empty_list .= "<li>Prix unitaire</li>";
		$checker = 1;
	}
	if($posted_data['type_remise_d'] == NULL OR !in_array($posted_data['type_remise_d'],  array( 'P','M' ))){

		$empty_list .= "<li>Type de remise</li>";
		$checker = 1;
	}
	if($posted_data['tva_d'] == NULL OR !in_array($posted_data['tva_d'],  array( 'O','N' ))){

		$empty_list .= "<li>TVA non valide</li>";
		$checker = 1;
	}


	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}


	  //End check empty element


	$exist_devis_d = new  Mdevis($posted_data);
    $exist_devis_d->id_devis_d = $posted_data['id'];


  //execute Insert returne false if error
	if($exist_devis_d->edit_exist_details_devis($posted_data['tkn_frm'])){

		exit("1#".$exist_devis_d->log."#".$exist_devis_d->sum_total_ht);
	}else{

		exit("0#".$exist_devis_d->log);
	}
	
}

view::load_view('edit_detaildevis');