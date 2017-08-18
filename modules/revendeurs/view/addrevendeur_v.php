 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('revendeurs','Liste Revendeurs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Revendeur
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

$form = new Mform('addrevendeur', 'addrevendeur', '', 'revendeurs', '1');
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements concernant le revendeur');

//pj_form
$form->input('Demande d\'agrément', 'pj', 'file', 6, null, null);
$form->file_js('pj', 1000000, 'pdf');

//image
$form->input('Logo de la société', 'pj_image', 'file', 6, null, null);
$form->file_js('pj_image', 1000000, 'image');

//Dénomination

$denomination_array[]  = array('required', 'true', 'Insérer la Raison Sociale Revendeur' );
$denomination_array[]  = array('remote', 'denomination#revendeurs#denomination', 'Cette Raison Sociale existe déja' );
$form->input('Raison Sociale', 'denomination', 'text' ,9 , null, $denomination_array);



//Pièce d'identité

$piece_identite_array[]  = array('required', 'true', 'Insérer le numéro du Registre de Commerce' );
$piece_identite_array[]  = array('remote', 'piece_identite#revendeurs#piece_identite', 'Ce numéro du Registre de Commerce existe déja' );
$form->input('Registre de Commerce', 'piece_identite', 'text' ,9 , null, $piece_identite_array);


//Numéro d'agrément
$num_agrement_array[]  = array('required', 'true', 'Insérer le numéro Agrément' );
$num_agrement_array[]  = array('remote', 'num_agrement#revendeurs#num_agrement', 'Ce numéro Agrément existe déja' );
$form->input('Numéro d\'agrément', 'num_agrement', 'text' ,9 , null, $num_agrement_array);
//qualification
$qualification_array[]  = array('required', 'true', 'Insérer Secteur Activité' );
$form->input('Secteur d\'activité', 'qualification', 'text', 9, null, $qualification_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Coordonées Revendeur');

//ville
$ville_array[]  = array('required', 'true', 'Choisir la ville Revendeur' );
$form->select_table('Ville', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $ville_array);

//Adresse
$adresse_array[]  = array('required', 'true', 'Insérer Adresse Revendeur' );
$form->input('Adresse', 'adresse', 'text', 9, null, $adresse_array);

// email
$mail_array[]  = array('required', 'true', 'Insérer Email ' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 9, null, $mail_array);
// Tél
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 9, null, $tel_array);
// fax
$fax_array[]  = array('required', 'true', 'Insérer N° de Fax' );
$fax_array[]  = array('minlength', '8', 'Le N° de Fax doit contenir au moins 8 chiffres' );
$fax_array[]  = array('number', 'true', 'Entrez un N° Fax Valid' );
$form->input('Fax', 'fax', 'text', 9, null, $fax_array);
//End Step 2
$form->step_end();
//Start Step 3
$form->step_start(3, 'Activités Revendeur');

//Groupe  VSAT
$vsat_array[]  = array('OUI', '1' );
$vsat_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente VSAT');
$form->radio('Vente VSAT', 'vsat', '2', $vsat_array, $radio_js_array);

//Groupe  UHF/VHF
$uhf_vhf_array[]  = array('OUI', '1' );
$uhf_vhf_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente UHF/VHF');
$form->radio('Vente UHF/VHF', 'uhf_vhf', '2', $uhf_vhf_array, $radio_js_array);

//Groupe  GSM
$gsm_array[]  = array('OUI', '1' );
$gsm_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente GSM');
$form->radio('Vente GSM', 'gsm', '2', $gsm_array, $radio_js_array);

//Groupe  BLR
$blr_array[]  = array('OUI', '1' );
$blr_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente BLR');
$form->radio('Vente BLR', 'blr', '2', $blr_array, $radio_js_array);

//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
