<?php 
//SYS GLOBAL TECH
// Modul: clients => View
 defined('_MEXEC') or die; 
 //Get all compte info 
 $info_client = new Mclients();
//Set ID of Module with POST id
 $info_client->id_client = Mreq::tp('id');
 //var_dump(Mreq::tp('id'));

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_client->get_client())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('clients', 'Liste des Clients', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le client 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_client->Shw('denomination',1).' -'.$info_client->Shw('code',1).'-)' ;
		
		?>
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
$form = new Mform('editclient', 'editclient',$info_client->id,  'clients', '1');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;

$form->input_hidden('id', $info_client->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Start Step 1
$form->step_start(1, 'Renseignements client');
//Code
$code_array[]  = array('required', 'true', 'Insérer le code ' );
$code_array[]  = array('remote', 'code#clients#code', 'Ce Code Client existe déja' );
$form->input('Code Client', 'code', 'text' ,6 , $info_client->Shw('code',1), $code_array);

//Denomination
$denomination_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$denomination_array[]  = array('required', 'true', 'Insérer La Dénomination' );
$form->input('Dénomination', 'denomination', 'text' ,6 , $info_client->Shw('denomination',1), $denomination_array);

//Catégorie client
$cat_array[]  = array('required', 'true', 'Sélectionnez la catégorie' );
$form->select_table('Catégorie Client', 'id_categorie', 6, 'categorie_client', 'id', 'categorie_client' , 'categorie_client', $indx = '*****' ,
	$selected=$info_client->Shw('id_categorie',1),$multi=NULL, $where='etat=1', $cat_array);

//Raison social
//$rsocial_array[]  = array('required', 'true', 'Insérer Raison Social ' );
$rsocial_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$rsocial_array[]  = array('remote', 'r_social#clients#r_social', 'Cette société existe déja' );
$form->input('Raison Social', 'r_social', 'text' ,6 , $info_client->Shw('r_social',1), $rsocial_array);

//r_commerce
//$rc_array[]  = array('required', 'true', 'Insérer N° de registre de commerce' );
$rc_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$rc_array[]  = array('remote', 'r_commerce#clients#r_commerce', 'Ce N° de registre de commerce existe déja'); 
$form->input('N° de registre de commerce', 'r_commerce', 'text', 6, $info_client->Shw('r_commerce',1), $rc_array);

//nif
//$nif_array[]  = array('required', 'true', 'Insérer N° Identification Fiscale' );
$nif_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$nif_array[]  = array('remote', 'nif#clients#nif', 'Ce N° Identification Fiscale existe déja'); 
$form->input('N° Identification Fiscale', 'nif', 'text', 6, $info_client->Shw('nif',1), $nif_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Informations du Représentant');

// nom personne à contacté 
$nom_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$nom_array[]  = array('required', 'true', 'Insérer Nom Représentant' );
$form->input('Nom', 'nom', 'text', 6, $info_client->Shw('nom',1), $nom_array);

// prenom personne à contacté 
$prenom_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$prenom_array[]  = array('required', 'true', 'Insérer Préom Représentant' );
$form->input('Prénom', 'prenom', 'text', 6, $info_client->Shw('prenom',1), $prenom_array);

// civilite
$civilite_array[]  = array('Femme' , 'Femme' );
$civilite_array[]  = array('Homme' , 'Homme' );
$form->radio('Civilité', 'civilite', $info_client->Shw('civilite',1), $civilite_array, '');

//Adresse
$adresse_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$adresse_array[]  = array('required', 'true', 'Insérer Adresse' );
$form->input('Adresse', 'adresse', 'text', 6, $info_client->Shw('adresse',1), $adresse_array);

//Pays
$pays_array[]  = array('required', 'true', 'Choisir le Pays' );
$form->select_table('Pays', 'id_pays', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '------' ,$selected=$info_client->Shw('id_pays',1),$multi=NULL, $where='etat=1', $pays_array);

//ville
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville', 'id_ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$selected=$info_client->Shw('id_ville',1),$multi=NULL, $where=NULL, $ville_array);

// Tél
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, $info_client->Shw('tel',1), $tel_array);

// fax
$fax_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$fax_array[]  = array('required', 'true', 'Insérer N° de Fax' );
$fax_array[]  = array('minlength', '8', 'Le N° de Fax doit contenir au moins 8 chiffres' );
$fax_array[]  = array('number', 'true', 'Entrez un N° Fax Valid' );
$form->input('Fax', 'fax', 'text', 6, $info_client->Shw('fax',1), $fax_array);

// bp
$form->input('Boite Postale', 'bp', 'text', 6, $info_client->Shw('bp',1), '');

// email
$mail_array[]  = array('required', 'true', 'Insérer Email ' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 6, $info_client->Shw('email',1), $mail_array);

//End Step 2
$form->step_end();
//Start Step 3

$form->step_start(3, 'Complément Informations');

// devise
$form->select_table('Devise', 'id_devise', 6, 'ref_devise', 'id', 'devise' , 'devise', $indx = '*****' ,
	$selected=$info_client->Shw('id_devise',1),$multi=NULL, $where='etat=1', NULL);


// taxe
$taxe_array[]  = array('Oui' , 'O' );
$taxe_array[]  = array('Non' , 'N' );
$form->radio('TVA', 'tva', $info_client->Shw('tva',1), $taxe_array, '');

//pj_id
$form->input('Justifications du client', 'pj', 'file', 6, 'Justification_client.pdf', null);
$form->file_js('pj', 1000000, 'pdf',$info_client->Shw('pj',1),1);

//pj_id
$form->input('Photo du client', 'pj_photo', 'file', 6, 'Photo_client.pdf', null);
$form->file_js('pj_photo', 1000000, 'image',$info_client->Shw('pj_photo',1),1);


$form->step_end();
//Button submit 
$form->button('Enregistrer Modifications');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>