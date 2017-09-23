<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => View
defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('type_echeance', 'Liste des types échéance', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addtype_echeance', 'addtype_echeance','',  'type_echeance', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations Type Echéance');
//Titre bloc 
//$form->bloc_title('Informations type_echeance');
//type_echeance
$type_echeance_array[]  = array('required', 'true', 'Insérer le Type Echéance' );
$type_echeance_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$type_echeance_array[]  = array('remote', 'type_echeance#ref_type_echeance#type_echeance', 'Ce Type Echéance existe déja' );
$form->input('Type Echéance', 'type_echeance', 'text' ,6 , null, $type_echeance_array);

$form->step_end();
//Button submit 
$form->button('Enregistrer le type');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
