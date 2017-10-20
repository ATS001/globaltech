	 <div class="pull-right tableTools-container">
		<div class="btn-group btn-overlap">
						
			<?php TableTools::btn_add('categories_produits','Liste des catégories', Null, $exec = NULL, 'reply'); ?>
						
		</div>
	</div>
	<div class="page-header">
		<h1>
			Ajouter une catégorie
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

	$form = new Mform('addcategorie_produit', 'addcategorie_produit', '', 'categories_produits', '0');

        //Type de produit
        $type_array[] = array('required', 'true', 'Choisir un type');
        $form->select_table('Type produit', 'type_produit', 6, 'ref_types_produits', 'id', 'type_produit', 'type_produit', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat= 1', $type_array);

	//Catégorie de produit
	$cat_prod_array[]  = array('required', 'true', 'Insérez une catégorie' );
	$cat_prod_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
	$form->input('Catégorie produit', 'categorie_produit', 'text' ,6, null, $cat_prod_array);

	$form->button('Enregistrer la catégorie');

	//Form render
	$form->render();
	?>
				</div>
			</div>
		</div>
	</div>
