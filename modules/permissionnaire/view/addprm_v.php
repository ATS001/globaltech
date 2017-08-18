 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('prm','Liste Permissionnaires', Null, NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Permissionnaire
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

$form = new Mform('addprm', 'addprm', '', 'prm', '1');
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements concernant le permissionnaire');
//pj_form
$form->input('Formulaire', 'pj', 'file', 6, null, null);
$form->file_js('pj', 1000000, 'pdf');
//Raison social
$rsocial_array[]  = array('required', 'true', 'Insérer Raison Social ' );
$rsocial_array[]  = array('remote', 'r_social#permissionnaires#r_social', 'Ce Nom existe déja' );
$form->input('Raison Social', 'r_social', 'text' ,6 , null, $rsocial_array);
//Sigle
$sigle_array[]  = array('required', 'true', 'Insérer Le Sigle ' );
$sigle_array[]  = array('remote', 'sigle#permissionnaires#sigle', 'Ce Sigle existe déja' );
$form->input('Sigle', 'sigle', 'text' ,6 , null, $sigle_array);

$categorie_array[]  = array('required', 'true', 'Choisir la catégorie de permissionnaire' );
//Groupe socioeconomique
$form->select_table('Catégorie', 'categorie', 6, 'ref_categ_prm', 'id', 'categorie' , 'categorie', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $categorie_array);
//Secteur
$secteur_array[]  = array('required', 'true', 'Choisir le Secteur Activité ' );
$form->select_table('Secteur D\'Activité', 'secteur_activ', 6, 'ref_secteur_activite', 'id', 'secteur' , 'secteur', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $secteur_array);
//rc
$rc_array[]  = array('required', 'true', 'Insérer N° de registre de commerce' );
$rc_array[]  = array('remote', 'rc#prm_permissionnaires#rc', 'Ce N° de registre de commerce existe déja'); 
$form->input('N° de registre de commerce', 'rc', 'text', 6, null, $rc_array);
//nif
$nif_array[]  = array('required', 'true', 'Insérer N° Identification Fiscale' );
$nif_array[]  = array('remote', 'nif#prm_permissionnaires#nif', 'Ce N° Identification Fiscale existe déja'); 
$form->input('N° Identification Fiscale', 'nif', 'text', 6, null, $nif_array);
// Groupe Multi National
$bloc_group_ste[]  = array('Oui' , '1' );
$bloc_group_ste[]  = array('Non' , '2' );
$radio_js_array[]  = array('required', 'true', 'Insérer le Groupe Multi National' );
$form->radio('Groupe Multi National', 'multi_national', '2', $bloc_group_ste, $radio_js_array);
//Pays du siège mère
$nation_array[]  = array('required', 'true', 'Choisir le Pays du siège mère' );
$form->select_table('Pays du siège mère', 'pay_siege', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '------' ,242,$multi=NULL, $where=NULL, $nation_array);
//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Coordonées permissionnaire');

//Adresse
$adresse_array[]  = array('required', 'true', 'Insérer Adresse' );
$form->input('Adresse', 'adresse', 'text', 6, null, $adresse_array);
// bp
$form->input('Boite Postal', 'bp', 'text', 6, null, '');
//ville
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $ville_array);

// email
$mail_array[]  = array('required', 'true', 'Insérer Email ' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 6, null, $mail_array);
// Tél
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, null, $tel_array);
// fax
$fax_array[]  = array('required', 'true', 'Insérer N° de Fax' );
$fax_array[]  = array('minlength', '8', 'Le N° de Fax doit contenir au moins 8 chiffres' );
$fax_array[]  = array('number', 'true', 'Entrez un N° Fax Valid' );
$form->input('Fax', 'fax', 'text', 6, null, $fax_array);
//End Step 2
$form->step_end();
//Start Step 3
$form->step_start(3, 'Informations de la personne à contacter');
// nom personne à contacté 
$nomp_array[]  = array('required', 'true', 'Insérer Nom Personne Physique' );
$form->input('Nom & Prénom', 'nom_p', 'text', 6, null, $nomp_array);
//Adresse Personne à contacté
$adressep_array[]  = array('required', 'true', 'Insérer Adresse Personne Physique' );
$form->input('Adresse Personne', 'adresse_p', 'text', 6, null, $adressep_array);
//Qualite Personne à contacté
$qualite_array[]  = array('required', 'true', 'Insérer Qualite Personne Physique' );
$form->input('Qualite', 'qualite_p', 'text', 6, null, $qualite_array);
//Date Naissance
//Nationnélité
$nation_array[]  = array('required', 'true', 'Choisir la Nationalité' );
$form->select_table('Nationalité', 'nation_p', 6, 'ref_pays', 'id', 'nation' , 'nation', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $nation_array);
//tel personne à contacté
$telp_array[]  = array('required', 'true', 'Insérer N° de téléphone Personne Physique' );
$telp_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$telp_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel_p', 'text', 6, null, $telp_array);
// email personne à contacté
$mailp_array[]  = array('required', 'true', 'Insérer Email  Personne Physique' );
$mailp_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email Personnel ', 'email_p', 'text', 6, null, $mailp_array);
//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
