	<?php
	//Get all categorie info
	 $info_categorie_produit= new Mcategorie_produit();
	//Set ID of Module with POST id
	 $info_categorie_produit->id_categorie_produit = Mreq::tp('id');
	//Check if Post ID <==> Post idc or get_prm return false. 
	 if(!MInit::crypt_tp('id', null, 'D')  or !$info_categorie_produit->get_categorie_produit())
	 { 	
	 	//returne message error red to client 
	 	exit('3#'.$info_categorie_produit->log .'<br>Les informations sont erronées contactez l\'administrateur');
	 }
	 

	 ?>

	<div class="pull-right tableTools-container">
		<div class="btn-group btn-overlap">
						
			
			<?php 
	              TableTools::btn_add('categories_produits', 'Liste des catégories', Null, $exec = NULL, 'reply');      
			 ?>			
		</div>
	</div>
	<div class="page-header">
		<h1>
			Modifier catégorie de produit 
			<small>
				<i class="ace-icon fa fa-aechongle-double-right"></i>
			</small>
			<?php echo ' ('.$info_categorie_produit->Shw('categorie_produit',1).' -'.$info_categorie_produit->id_categorie_produit.'-)';?>
		</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<div class="clearfix">
				
			</div>
			<div class="table-header">
				Formulaire: "<?php echo ACTIV_APP ; ?>"

			</div>
			<div class="widget-content">
				<div class="widget-box">
				 	
	<?php

	$form = new Mform('editecategorie_produit', 'editecategorie_produit', $info_categorie_produit->Shw('id',1), 'categories_produits','0');
	$form->input_hidden('id', $info_categorie_produit->Shw('id',1));
	$form->input_hidden('idc', Mreq::tp('idc'));
	$form->input_hidden('idh', Mreq::tp('idh'));

        //Type de produit
        $type_array[] = array('required', 'true', 'Choisir un type');
        $form->select_table('Type produit', 'type_produit', 6, 'ref_types_produits', 'id', 'type_produit', 'type_produit', $indx = '------',$info_categorie_produit->Shw('type_produit',1), $multi = NULL, $where = 'etat= 1', $type_array);

	//Catégorie de produit
	$cat_prod_array[]  = array('required', 'true', 'Insérez une catégorie' );
	$cat_prod_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
	$form->input('Catégorie produit', 'categorie_produit', 'text' ,6, $info_categorie_produit->Shw('categorie_produit',1), $cat_prod_array);

	$form->button('Modifier la catégorie');

	//Form render
	$form->render();
	?>
				</div>
			</div>
		</div>
	</div>




