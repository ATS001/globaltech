<?php

 
 $info_uhf_vhf_stations = new Muhf_vhf_stations();
 $info_uhf_vhf_stations->id_uhf_vhf_stations = Mreq::tp('id');
 
 
if(!MInit::crypt_tp('id', null, 'D')  or !$info_uhf_vhf_stations->get_uhf_vhf_stations())
 { 	

 	exit('0#'.$info_uhf_vhf_stations->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
 }

    $id_uhf_vhf_stations = Mreq::tp('id');
   
?>

 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
		TableTools::btn_add('uhf_vhf_clients', 'Lite des clients', MInit::crypt_tp('id', $id_uhf_vhf_stations), $exec = NULL, 'reply'); 

		?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter une station Fixe
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

$form = new Mform('adduhf_vhf_clt_fixe', 'adduhf_vhf_clt_fixe', '', 'uhf_vhf_clients&'.MInit::crypt_tp('id', $id_uhf_vhf_stations), '1');
$form->input_hidden('station_base',   $id_uhf_vhf_stations);
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements 1');


//Longitude
$longi_array[]  = array('required', 'true', 'Insérer la langitude' );
$longi_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Longitude', 'longi', 'text', 6, null, $longi_array);

//Latitude
$latit_array[]  = array('required', 'true', 'Insérer la latitude' );
$form->input('Latitude', 'latit', 'text', 6, null, $latit_array);

//Numero de serie
$num_serie_array[]  = array('required', 'true', 'Insérer le numéro de serie' );
$form->input('N° Serie','num_serie', 'text', 6, null, $num_serie_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Renseignements 2');

// Marque 
$marque_array[]  = array('required', 'true', 'Insérer une marque' );
$marque_array[]  = array('minlength', '2', 'La marque doit contenir au moins 2 caractères' );
$form->input('Marque', 'marque', 'text', 6, null, $marque_array);

// Modèle 
$modele_array[]  = array('required', 'true', 'Insérer un modèle' );
$modele_array[]  = array('minlength', '2', 'Le modèle doit contenir au moins 2 caractères' );
$form->input('Modèle', 'modele', 'text', 6, null, $modele_array);

//Active
$active_array  = array('En Marche' => 'En Marche', 'En Arrêt' => 'En Arrêt' );
$form->select('Active', 'active', 3, $active_array, Null,'En Marche', $multi = NULL );

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
