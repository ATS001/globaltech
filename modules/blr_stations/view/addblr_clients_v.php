<?php

 
 $info_blr_stations = new Mblr_stations();
 $info_blr_stations->id_blr_stations = Mreq::tp('id');
 
 
if(!MInit::crypt_tp('id', null, 'D')  or !$info_blr_stations->get_blr_stations())
 { 	

 	exit('0#'.$info_blr_stations->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
 }

    $id_blr_stations = Mreq::tp('id');
   
?>

 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
		TableTools::btn_add('blr_clients', 'Lite des clients', MInit::crypt_tp('id', $id_blr_stations), $exec = NULL, 'reply'); 

		?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Un client BLR
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

$form = new Mform('addblr_clients', 'addblr_clients', '', 'blr_clients&'.MInit::crypt_tp('id', $id_blr_stations), '1');
$form->input_hidden('station_base',   $id_blr_stations);
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements 1');
//Formulaire
$form->input('Formulaire', 'pj', 'file', 6, null, null);
$form->file_js('pj', 500000, 'pdf');
// nom du site 
$site_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom du site', 'site', 'text', 6, null, $site_array);
//Longitude
$longi_array[]  = array('required', 'true', 'Insérer la langitude' );
$longi_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Longitude', 'longi', 'text', 6, null, $longi_array);

//Latitude
$latit_array[]  = array('required', 'true', 'Insérer la latitude' );
$form->input('Latitude', 'latit', 'text', 6, null, $latit_array);

//Hauteur
$hauteur_array[]  = array('required', 'true', 'Insérer la hauteur' );
$hauteur_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Hauteur', 'hauteur', 'text', 6, null, $hauteur_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Renseignements 2');
//Fréquence
$frequence_array[]  = array('required', 'true', 'Insérer la fréquence' );
$frequence_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Fréquence', 'frequence', 'text', 6, null, $frequence_array);
// Marque 
$marque_array[]  = array('required', 'true', 'Insérer une marque' );
$marque_array[]  = array('minlength', '2', 'La marque doit contenir au moins 2 caractères' );
$form->input('Marque', 'marque', 'text', 6, null, $marque_array);

// Modèle 
$modele_array[]  = array('required', 'true', 'Insérer un modèle' );
$modele_array[]  = array('minlength', '2', 'Le modèle doit contenir au moins 2 caractères' );
$form->input('Modèle', 'modele', 'text', 6, null, $modele_array);
//Editeur de remarques
$form->input_editor('Remarques', 'remarq', 8, $input_value = null, $js_array = null);
//Photos
$form->gallery_bloc(null, null);

//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
