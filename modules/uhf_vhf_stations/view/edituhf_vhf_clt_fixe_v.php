<?php

 
 $info_uhf_vhf_clients = new Muhf_vhf_clients();
 $info_uhf_vhf_clients->id_uhf_vhf_clients = Mreq::tp('id');
 
 
if(!MInit::crypt_tp('id', null, 'D')  or !$info_uhf_vhf_clients->get_uhf_vhf_clients())
 { 	

 	exit('0#'.$info_uhf_vhf_clients->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
 }

    $id_uhf_vhf_stations = Mreq::tp('id');
   
?>

 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
		TableTools::btn_add('uhf_vhf_clients', 'Lite des clients', MInit::crypt_tp('id', $info_uhf_vhf_clients->Shw('station_base',1)), $exec = NULL, 'reply'); 

		?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier Station Fixe
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
                <?php echo ' ('.$info_uhf_vhf_clients->Shw('id',1).' -'.$info_uhf_vhf_clients->id_uhf_vhf_clients.'-)' ;?>
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

$form = new Mform('edituhf_vhf_clt_fixe', 'edituhf_vhf_clt_fixe', $info_uhf_vhf_clients->Shw('id',1),'uhf_vhf_clients&'.MInit::crypt_tp('id',$info_uhf_vhf_clients->Shw('station_base',1)), '1');
$form->input_hidden('id', $info_uhf_vhf_clients->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements 1');


//Longitude
$longi_array[]  = array('required', 'true', 'Insérer la langitude' );
$longi_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Longitude', 'longi', 'text', 6,$info_uhf_vhf_clients->Shw('longi',1) , $longi_array);

//Latitude
$latit_array[]  = array('required', 'true', 'Insérer la latitude' );
$form->input('Latitude', 'latit', 'text', 6, $info_uhf_vhf_clients->Shw('latit',1), $latit_array);

//Numero de serie
$num_serie_array[]  = array('required', 'true', 'Insérer le numéro de serie' );
$form->input('N° Serie','num_serie', 'text', 6, $info_uhf_vhf_clients->Shw('num_serie',1), $num_serie_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Renseignements 2');

// Marque 
$marque_array[]  = array('required', 'true', 'Insérer une marque' );
$marque_array[]  = array('minlength', '2', 'La marque doit contenir au moins 2 caractères' );
$form->input('Marque', 'marque', 'text', 6, $info_uhf_vhf_clients->Shw('marque',1), $marque_array);

// Modèle 
$modele_array[]  = array('required', 'true', 'Insérer un modèle' );
$modele_array[]  = array('minlength', '2', 'Le modèle doit contenir au moins 2 caractères' );
$form->input('Modèle', 'modele', 'text', 6, $info_uhf_vhf_clients->Shw('modele',1), $modele_array);

//Active
$active_array  = array('En Marche' => 'En Marche', 'En Arrêt' => 'En Arrêt' );
$form->select('Active', 'active', 3, $active_array,NULL,$info_uhf_vhf_clients->Shw('active',1), $multi = NULL );

//Photos
$form->gallery_bloc(null, null,$info_uhf_vhf_clients->Shw('pj_images',1));

//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
