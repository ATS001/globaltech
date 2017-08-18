<?php 
defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('anonymes', 'Liste des anonymes', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addanonyme', 'addanonyme','', 'anonymes', '1');

$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations permissionaire anonyme ');
//Titre
$titre_array[]  = array('required', 'true', 'Insérer un titre' );
$titre_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Titre', 'titre', 'text' ,6 , null, $titre_array);


//Longitude
$longitude_array[]  = array('required', 'true', 'Insérer la longitude' );
$form->input('Longitude', 'longi', 'text' ,6 , null, $longitude_array);

//Latitude
$latitude_array[]  = array('required', 'true', 'Insérer la latitude' );
$form->input('Latitude', 'latit', 'text' ,6 , null, $latitude_array);

//Technologie
$technologie_array  = array('VSAT' => 'VSAT', 'BLR' => 'BLR','UHF/VHF'=>'UHF/VHF' );
$form->select('Technologie', 'technologie', 3, $technologie_array, Null,'E', $multi = NULL );
/*$technologie_array[]  = array('required', 'true', 'Insérer la technologie' );
$technologie_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Technologie', 'technologie', 'text' ,6 , null, $technologie_array);*/

//Date de la visite
$array_date[]= array('required', 'true', 'Insérer la date de visite');
$form->input_date('Date visite', 'date_visite', 4, date('d-m-Y'), $array_date);


$form->step_end();
$form->step_start(2, 'Remarques et images');

$form->input_editor('Remarques', 'remarque', 8, $input_value = null, $js_array = null);

//$label[] = array('Photo Antenne - Photo Radio - Photo Modem - capture ecran test de débit (via le site www.speedtest.net)');
$form->gallery_bloc(null, null);


$form->step_end();

//Button submit 
$form->button('Enregistrer anonyme');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
