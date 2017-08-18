<?php 
defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('vsat_hub', 'Liste des hubs', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addvsat_hub', 'addvsat_hub','', 'vsat_hub', '0');

//Nom opérateur
$operateur_array[]  = array('required', 'true', 'Insérer un opérateur' );
$operateur_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Opérateur', 'operateur', 'text' ,6 , null, $operateur_array);

//Pays de l'opérateur
$pays_hub_array[]  = array('required', 'true', 'Choisir le pays');
$form->select_table("Pays", 'pays_hub', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '---------' ,$selected = NULL, $multi = NULL, $where = NULL, $pays_hub_array);

//Ville
$ville_hub_array[]  = array('required', 'true', 'Insérer une ville');
$ville_hub_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Ville', 'ville_hub', 'text' ,6 , null, $ville_hub_array);

//Email
$email_hub_array[]  = array('required', 'true', 'Insérer un émail' );
$email_hub_array[]  = array('minlength', '6', 'Minimum 6 caractères' );
$form->input('E-mail', 'email_hub', 'text' ,6 , null, $email_hub_array);




//Button submit 
$form->button('Enregistrer Hub');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
