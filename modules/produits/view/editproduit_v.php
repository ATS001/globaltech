<?php 
defined('_MEXEC') or die;

//Get all compte info 
 $info_produit = new Mproduit();
//Set ID of Module with POST id
 $info_produit->id_produit = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_produit->get_produit())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_produit->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('produits','Liste des produits', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier un produit
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php

$form = new Mform('editproduit', 'editproduit',$info_produit->Shw('id',1), 'produits', '0');
$form->input_hidden('id', $info_produit->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));



//Référence
$ref_array[]  = array('required', 'true', 'Insérez une référence' );
$ref_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Référence', 'ref', 'text' ,6,$info_produit->Shw('ref',1), $ref_array);

//Désignation
$designation_array[]  = array('required', 'true', 'Insérez une désignation' );
$designation_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Désignation', 'designation', 'text' ,6,$info_produit->Shw('designation',1), $designation_array);

//Prix unitaire
$pu_array[]  = array('required', 'true', 'Insérez le prix unitaire' );
$pu_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Prix unitaire', 'pu', 'text' ,6,$info_produit->Shw('pu',1), $pu_array);

//stock minimale
$stock_min_array[]  = array('required', 'true', 'Insérez le stock minimale' );
$stock_min_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Stock minimale', 'stock_min', 'text' ,6, $info_produit->Shw('stock_min',1), $stock_min_array);


//Catégorie
$cat_array[]  = array('required', 'true', 'Choisir une catégorie');
$form->select_table('Catégorie', 'idcategorie', 6, 'ref_categories_produits', 'id', 'categorie_produit' , 'categorie_produit', $indx = '------' ,$info_produit->Shw('idcategorie',1),$multi=NULL, $where=NULL, $cat_array);

//Unité de vente
$uv_array[]  = array('required', 'true', 'Choisir une unité de vente');
$form->select_table('Unité de vente', 'iduv', 6, 'ref_unites_vente', 'id', 'unite_vente' , 'unite_vente', $indx = '------' ,$info_produit->Shw('iduv',1),$multi=NULL, $where=NULL, $uv_array);

//Type de produit
$type_array[]  = array('required', 'true', 'Choisir un type');
$form->select_table('Type', 'idtype', 6, 'ref_types_produits', 'id', 'type_produit' , 'type_produit', $indx = '------' ,$info_produit->Shw('idtype',1),$multi=NULL, $where=NULL, $type_array);

$form->button('Modifier le produit');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
