<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => View
 defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('categorie_client', 'Liste des Catégories Clients', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addcategorie_client', 'addcategorie_client','',  'categorie_client', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations catégorie client');
//Titre bloc 
//$form->bloc_title('Informations pays');
//pays
$cat_array[]  = array('required', 'true', 'Insérer la catégorie' );
$cat_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$cat_array[]  = array('remote', 'categorie_client#categorie_client#categorie_client', 'Cette catégorie existe déja' );
$form->input('Catégorie Client', 'categorie_client', 'text' ,6 , null, $cat_array);


$form->step_end();
//Button submit 
$form->button('Enregistrer la catégorie');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
