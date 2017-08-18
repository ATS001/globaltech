<?php 
defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('vsat_satellite', 'Liste des satellites', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
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
//
$form = new Mform('addvsat_satellite', 'addvsat_satellite','', 'vsat_satellite', '0');

//Nom Satellite
$satellite_array[]  = array('required', 'true', 'Insérer un satellite' );
$satellite_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Satellite', 'satellite', 'text' ,6 , null, $satellite_array);


//Nom Position orbitale
$position_orbitale_array[]  = array('required', 'true', 'Insérer la position orbitale' );
$form->input('Position Orbitale', 'position_orbitale', 'text' ,6 , null, $position_orbitale_array);

//Contractor
$contractor_array[]  = array('required', 'true', 'Insérer la position orbitale' );
$contractor_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Fournisseur', 'contractor', 'text' ,6 , null, $contractor_array);


//Pays opérateur
$pay_operator_array[]  = array('required', 'true', 'Choisir un pays' );
$pay_operator_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Pays opérateur', 'pay_operator', 'text' ,6 ,null, $pay_operator_array);


//Button submit 
$form->button('Enregistrer satellite');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
