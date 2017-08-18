
<?php
//Get all station info
 $uhf_vhf_stations_info= new Muhf_vhf_stations();
//Set ID of Module with POST id
 $uhf_vhf_stations_info->id_uhf_vhf_stations = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$uhf_vhf_stations_info->get_uhf_vhf_stations())
 { 	
 	//returne message error red to client 
 	exit('3#'.$uhf_vhf_stations_info->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 
?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('uhf_vhf_stations', 'Liste des stations', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$uhf_vhf_stations_info->Shw('site',1).' -'.$uhf_vhf_stations_info->id_uhf_vhf_stations.'-)' ;?>
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

$form = new Mform('edituhf_vhf_stations', 'edituhf_vhf_stations',$uhf_vhf_stations_info->Shw('id',1), 'uhf_vhf_stations', '1');
$form->input_hidden('id', $uhf_vhf_stations_info->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements 1');


//Formulaire
$form->input('Formulaire', 'pj', 'file', 6, 'Formulaire.pdf', null);
$form->file_js('pj', 500000, 'pdf', $uhf_vhf_stations_info->Shw('pj',1), 1);


//Raison social
$prm_array[]  = array('required', 'true', 'Choisir la raison social');
$form->select_table('Permissionnaire', 'prm', 6, 'permissionnaires', 'id', 'r_social' , 'r_social', $indx = '------' ,$uhf_vhf_stations_info->Shw('prm',1),$multi=NULL, $where=NULL, $prm_array);

// nom du site 
$site_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom du site', 'site', 'text', 6, $uhf_vhf_stations_info->Shw('site',1), $site_array);

//Date de la visite
$array_date[]= array('required', 'true', 'Insérer la date de visite');
$form->input_date('Date visite', 'date_visite', 4, $uhf_vhf_stations_info->Shw('date_visite',1), $array_date);


//ville
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$uhf_vhf_stations_info->Shw('ville',1),$multi=NULL, $where=NULL, $ville_array);
//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Renseignements 2');

// Modèle 
$modele_array[]  = array('required', 'true', 'Insérer un modèle' );
$modele_array[]  = array('minlength', '2', 'Le modèle doit contenir au moins 2 caractères' );
$form->input('Modèle', 'modele', 'text', 6, $uhf_vhf_stations_info->Shw('modele',1), $modele_array);

//Longitude
$longi_array[]  = array('required', 'true', 'Insérer la longitude' );
$longi_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Longitude', 'longi', 'text', 6, $uhf_vhf_stations_info->Shw('longi',1), $longi_array);


//Latitude
$latit_array[]  = array('required', 'true', 'Insérer la latitude' );
$latit_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Latitude', 'latit', 'text', 6, $uhf_vhf_stations_info->Shw('latit',1), $latit_array);



//hauteur
$hauteur_array[]  = array('required', 'true', 'Insérer la hauteur' );
$hauteur_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Hauteur', 'hauteur', 'text', 6, $uhf_vhf_stations_info->Shw('hauteur',1), $hauteur_array);



//End Step 2
$form->step_end();
//Start Step 3
$form->step_start(3, 'Renseignements 3');


//Puissance
$puissance_array[]  = array('required', 'true', 'Insérer la puissance' );
$puissance_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Puissance', 'puissance', 'text', 6, $uhf_vhf_stations_info->Shw('puissance',1), $puissance_array);

//Fréquence
$frequence_array[]  = array('required', 'true', 'Insérer la fréquence' );
$frequence_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Fréquence', 'frequence', 'text', 6, $uhf_vhf_stations_info->Shw('frequence',1), $frequence_array);

//Modulation
$modulation_array[]  = array('required', 'true', 'Insérer la modulation' );
$form->input('Modulation', 'modulation', 'text', 6, $uhf_vhf_stations_info->Shw('modulation',1), $modulation_array);

//Nombre de station client
$pa_array[]  = array('required', 'true', 'Insérer la portée ' );
$pa_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Portée antenne', 'porte_antenne', 'text', 6, $uhf_vhf_stations_info->Shw('porte_antenne',1), $pa_array);


//Type de station
$arch_array  = array('UHF' => 'UHF', 'VHF' => 'VHF' );
$form->select('Type de station', 'type_station', 3, $arch_array, Null,$uhf_vhf_stations_info->Shw('type_station',1), $multi = NULL );


//Photos
$form->gallery_bloc(null, null, $uhf_vhf_stations_info->Shw('pj_images',1));


//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
