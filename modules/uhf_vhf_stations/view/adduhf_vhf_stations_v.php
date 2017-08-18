 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_back('uhf_vhf_stations','Liste des stations',NULL); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Une Station 
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

$form = new Mform('adduhf_vhf_stations', 'adduhf_vhf_stations', '', 'uhf_vhf_stations', '1');
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements 1');


//Formulaire
$form->input('Formulaire', 'pj', 'file', 6, null, null);
$form->file_js('pj', 500000, 'pdf');


//Raison social
$prm_array[]  = array('required', 'true', 'Choisir la raison social');
$form->select_table('Permissionnaire', 'prm', 6, 'permissionnaires', 'id', 'r_social' , 'r_social', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $prm_array);

// nom du site 
$site_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom du site', 'site', 'text', 6, null, $site_array);


//Date de la visite
$array_date[]= array('required', 'true', 'Insérer la date de visite');
$form->input_date('Date visite', 'date_visite', 4, date('d-m-Y'), $array_date);


//ville
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $ville_array);
//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Renseignements 2');

// Modèle 
$modele_array[]  = array('required', 'true', 'Insérer un modèle' );
$modele_array[]  = array('minlength', '2', 'Le modèle doit contenir au moins 2 caractères' );
$form->input('Modèle', 'modele', 'text', 6, null, $modele_array);

//Longitude
$longi_array[]  = array('required', 'true', 'Insérer la longitude' );
$longi_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Longitude', 'longi', 'text', 6, null, $longi_array);


//Latitude
$latit_array[]  = array('required', 'true', 'Insérer la latitude' );
$latit_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Latitude', 'latit', 'text', 6, null, $latit_array);

//hauteur
$hauteur_array[]  = array('required', 'true', 'Insérer la hauteur' );
$hauteur_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Hauteur', 'hauteur', 'text', 6, null, $hauteur_array);


//End Step 2
$form->step_end();
//Start Step 3
$form->step_start(3, 'Renseignements 3');


//Puissance
$puissance_array[]  = array('required', 'true', 'Insérer la puissance' );
$puissance_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Puissance', 'puissance', 'text', 6, null, $puissance_array);
$form->input_tag('puissance');
//Fréquence
$frequence_array[]  = array('required', 'true', 'Insérer la fréquence (Khz)' );
$frequence_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Fréquence (Khz)', 'frequence', 'text', 6, null, $frequence_array);
$form->input_tag('frequence');
//Modulation
$modulation_array[]  = array('required', 'true', 'Insérer la modulation' );
$form->input('Modulation', 'modulation', 'text', 6, null, $modulation_array);

//Nombre de station client
$pa_array[]  = array('required', 'true', 'Insérer la portée ' );
$pa_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Portée antenne', 'porte_antenne', 'text', 6, null, $pa_array);


//Type de station
$arch_array  = array('UHF' => 'UHF', 'VHF' => 'VHF' , 'HF' => 'HF' );
$form->select('Type de station', 'type_station', 3, $arch_array, Null,'P2P', $multi = NULL );




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
