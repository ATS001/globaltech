 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('unites_vente','Liste des unités de vente', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter une unité de vente
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

$form = new Mform('addunite_vente', 'addunite_vente', '', 'unites_vente', '0');

//Unité de vente
$unite_vente_array[]  = array('required', 'true', 'Insérez une unité de vente' );
$form->input('Unité de vente', 'unite_vente', 'text' ,6, null, $unite_vente_array);


//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
