<?php
//Get all Permissionnaire info
 $info_prm= new Mprms();
//Set ID of Module with POST id
 $info_prm->id_prm = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_prm->get_prm())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_prm->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php 
              TableTools::btn_add('prm', 'Liste Permissionnaires', Null, $exec = NULL, 'reply');      
		 ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier Compte Permissionnaire 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_prm->Shw('r_social',1).' '.$info_prm->Shw('sigle',1).' -'.$info_prm->id_prm.'-)';?>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP ; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
			 	
<?php

$form = new Mform('editprm', 'editprm', $info_prm->Shw('id',1), 'prm','1');
$form->input_hidden('id', $info_prm->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements concernant le permissionnaire');
//pj_form
$form->input('Formulaire', 'pj', 'file', 6, 'Formulaire.pdf', null);
$form->file_js('pj', 100000, 'pdf',$info_prm->Shw('pj',1),1);
//Raison social
$rsocial_array[]  = array('required', 'true', 'Insérer Raison Social ' );
$rsocial_array[]  = array('remote', 'r_social#permissionnaires#r_social', 'Ce Nom existe déja' );
$form->input('Raison Social', 'r_social', 'text' ,6 , $info_prm->Shw('r_social',1), $rsocial_array);
//Sigle
$sigle_array[]  = array('required', 'true', 'Insérer Le Sigle ' );
$sigle_array[]  = array('remote', 'sigle#permissionnaires#sigle', 'Ce Sigle existe déja' );
$form->input('Sigle', 'sigle', 'text' ,6 , $info_prm->Shw('sigle',1), $sigle_array);

$categorie_array[]  = array('required', 'true', 'Choisir la catégorie de permissionnaire' );
//Groupe socioeconomique
$form->select_table('Catégorie', 'categorie', 6, 'ref_categ_prm', 'id', 'categorie' , 'categorie', $indx = '------' ,$info_prm->Shw('categorie',1), $multi=NULL, $where=NULL, $categorie_array);
//Secteur
$secteur_array[]  = array('required', 'true', 'Choisir le Secteur Activité ' );
$form->select_table('Secteur D\'Activité', 'secteur_activ', 6, 'ref_secteur_activite', 'id', 'secteur' , 'secteur', $indx = '------' ,$info_prm->Shw('secteur_activ',1), $multi=NULL, $where=NULL, $secteur_array);
//rc
$rc_array[]  = array('required', 'true', 'Insérer N° de registre de commerce' );
$rc_array[]  = array('remote', 'rc#prm_permissionnaires#rc', 'Ce N° de registre de commerce existe déja'); 
$form->input('N° de registre de commerce', 'rc', 'text', 6, $info_prm->Shw('rc',1), $rc_array);
//nif
$nif_array[]  = array('required', 'true', 'Insérer N° Identification Fiscale' );
$nif_array[]  = array('remote', 'nif#prm_permissionnaires#nif', 'Ce N° Identification Fiscale existe déja'); 
$form->input('N° Identification Fiscale', 'nif', 'text', 6, $info_prm->Shw('nif',1), $nif_array);
// Groupe Multi National
$bloc_group_ste[]  = array('Oui' , '1' );
$bloc_group_ste[]  = array('Non' , '2' );
$radio_js_array[]  = array('required', 'true', 'Insérer le Groupe Multi National' );
$form->radio('Groupe Multi National', 'multi_national', $info_prm->Shw('multi_national',1), $bloc_group_ste, $radio_js_array);
//Pays du siège mère
$nation_array[]  = array('required', 'true', 'Choisir le Pays du siège mère' );
$form->select_table('Pays du siège mère', 'pay_siege', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '------' ,$info_prm->Shw('pay_siege',1),$multi=NULL, $where=NULL, $nation_array);
//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Coordonées permissionnaire');
//Adresse
$adresse_array[]  = array('required', 'true', 'Insérer Adresse' );
$form->input('Adresse', 'adresse', 'text', 6, $info_prm->Shw('adresse',1), $adresse_array);
// bp
$form->input('Boite Postal', 'bp', 'text', 6, $info_prm->Shw('bp',1), '');
//ville
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$info_prm->Shw('ville',1),$multi=NULL, $where=NULL, $ville_array);
// email
$mail_array[]  = array('required', 'true', 'Insérer Email ' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 6, $info_prm->Shw('email',1), $mail_array);
// Tél
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, $info_prm->Shw('tel',1), $tel_array);
// fax
$fax_array[]  = array('required', 'true', 'Insérer N° de Fax' );
$fax_array[]  = array('minlength', '8', 'Le N° de Fax doit contenir au moins 8 chiffres' );
$fax_array[]  = array('number', 'true', 'Entrez un N° Fax Valid' );
$form->input('Fax', 'fax', 'text', 6, $info_prm->Shw('fax',1), $fax_array);
//End Step 2
$form->step_end();
//Start Step 3
$form->step_start(3, 'Informations de la personne à contacter');
// nom personne à contacté 
$nomp_array[]  = array('required', 'true', 'Insérer Nom Personne Physique' );
$form->input('Nom & Prénom', 'nom_p', 'text', 6, $info_prm->Shw('nom_p',1), $nomp_array);
//Adresse Personne à contacté
$adressep_array[]  = array('required', 'true', 'Insérer Adresse Personne Physique' );
$form->input('Adresse Personne', 'adresse_p', 'text', 6, $info_prm->Shw('adresse_p',1), $adressep_array);
//Qualite Personne à contacté
$qualite_array[]  = array('required', 'true', 'Insérer Qualite Personne Physique' );
$form->input('Qualite', 'qualite_p', 'text', 6, $info_prm->Shw('qualite_p',1), $qualite_array);
//Date Naissance
//Nationnélité
$nation_array[]  = array('required', 'true', 'Choisir la Nationalité' );
$form->select_table('Nationalité', 'nation_p', 6, 'ref_pays', 'id', 'nation' , 'nation', $indx = '------' ,$info_prm->Shw('nation_p',1),$multi=NULL, $where=NULL, $nation_array);
//tel personne à contacté
$telp_array[]  = array('required', 'true', 'Insérer N° de téléphone Personne Physique' );
$telp_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$telp_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel_p', 'text', 6, $info_prm->Shw('tel_p',1), $telp_array);
// email personne à contacté
$mailp_array[]  = array('required', 'true', 'Insérer Email  Personne Physique' );
$mailp_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email Personnel ', 'email_p', 'text', 6, $info_prm->Shw('email_p',1), $mailp_array);
//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>




