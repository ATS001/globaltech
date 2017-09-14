 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('types_produits','Liste des types', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un type
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

$form = new Mform('addtype_produit', 'addtype_produit', '', 'types_produits', '0');

//Type de produit
$type_prod_array[]  = array('required', 'true', 'Insérez un type' );
$form->input('Type', 'type_produit', 'text' ,6, null, $type_prod_array);

$form->button('Enregistrer le type');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
