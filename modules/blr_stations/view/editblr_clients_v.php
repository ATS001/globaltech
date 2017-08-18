
<?php
//Get all client info
 $blr_clients_info= new Mblr_clients();
//Set ID of cllient with POST id
 $blr_clients_info->id_blr_clients = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$blr_clients_info->get_blr_clients())
 { 	
 	//returne message error red to client 
 	exit('3#'.$blr_clients_info->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 
	$id_blr_stations   = $blr_clients_info->blr_clients_info['station_base'];
         
     
?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
                
                TableTools::btn_add('blr_clients', 'Liste des clients',  MInit::crypt_tp('id',$blr_clients_info->Shw('station_base',1)), $exec = NULL, 'reply'); 
                //btn_add($app, $text=NULL, $add_set=NULL, 1, 'reply')
                //TableTools::btn_back('blr_clients','Liste des clients', 'id='.
		 ?>					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$blr_clients_info->Shw('id',1).' -'.$blr_clients_info->id_blr_clients.'-)' ;?>
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

$form = new Mform('editblr_clients', 'editblr_clients',$blr_clients_info->Shw('id',1), 'blr_clients&'.MInit::crypt_tp('id',$blr_clients_info->Shw('station_base',1)), 1);
$form->input_hidden('id', $blr_clients_info->Shw('id',1));
$form->input_hidden('station_base', $blr_clients_info->Shw('station_base',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements 1');
//Formulaire
$form->input('Formulaire', 'pj', 'file', 6, 'Formulaire.pdf', null);
$form->file_js('pj', 500000, 'pdf', $blr_clients_info->Shw('pj',1), 1);
// nom du site 
$site_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom du site', 'site', 'text', 6, $blr_clients_info->Shw('site',1), $site_array);
//Longitude
$longi_array[]  = array('required', 'true', 'Insérer la langitude' );
$longi_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Longitude', 'longi', 'text', 6, $blr_clients_info->Shw('longi',1), $longi_array);

//Latitude
$latit_array[]  = array('required', 'true', 'Insérer la latitude' );
$form->input('Latitude', 'latit', 'text', 6, $blr_clients_info->Shw('latit',1), $latit_array);

//Hauteur
$hauteur_array[]  = array('required', 'true', 'Insérer la hauteur' );
$hauteur_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Hauteur', 'hauteur', 'text', 6, $blr_clients_info->Shw('hauteur',1), $hauteur_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Renseignements 2');
//Fréquence
$frequence_array[]  = array('required', 'true', 'Insérer la fréquence' );
$frequence_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Fréquences', 'frequence', 'text', 6, $blr_clients_info->Shw('frequence',1), $frequence_array);
// Marque 
$marque_array[]  = array('required', 'true', 'Insérer une marque' );
$marque_array[]  = array('minlength', '2', 'La marque doit contenir au moins 2 caractères' );
$form->input('Marque', 'marque', 'text', 6, $blr_clients_info->Shw('marque',1), $marque_array);

// Modèle 
$modele_array[]  = array('required', 'true', 'Insérer un modèle' );
$modele_array[]  = array('minlength', '2', 'Le modèle doit contenir au moins 2 caractères' );
$form->input('Modèle', 'modele', 'text', 6, $blr_clients_info->Shw('modele',1), $modele_array);
//Editeur de remarques
$form->input_editor('Remarques', 'remarq', 8, $blr_clients_info->Shw('remarq',1), $js_array = null);
//Photos
$form->gallery_bloc(null, null, $blr_clients_info->Shw('pj_images',1));

//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
