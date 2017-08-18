<?php
//Get all revendeur info
 $info_rev= new Mrev();
//Set ID of Module with POST id
 $info_rev->id_rev = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_rev->get_rev())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_rev->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php 
              TableTools::btn_add('revendeurs', 'Liste Revendeurs', Null, $exec = NULL, 'reply');      
		 ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier Compte Revendeur 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_rev->Shw('denomination',1).' '.$info_rev->Shw('piece_identite',1).' -'.$info_rev->id_rev.'-)';?>
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

$form = new Mform('editrevendeur', 'editrevendeur', $info_rev->Shw('id',1), 'revendeurs','1');
$form->input_hidden('id', $info_rev->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;

//Start Step 1
$form->step_start(1, 'Renseignements concernant le Revendeur');

//pj_form
$form->input('Demande d\'agrément', 'pj', 'file', 6, 'Formulaire.pdf', null);
$form->file_js('pj', 1000000, 'pdf',$info_rev->Shw('pj',1),1);


//image
$form->input('Logo de la société', 'pj_image', 'file', 6, 'Image.pdf', null);
$form->file_js('pj_image', 1000000, 'image',$info_rev->Shw('pj_image',1),1);

//Dénomination
$denomination_array[]  = array('required', 'true', 'Insérer la Raison Sociale du Revendeur' );
$denomination_array[]  = array('remote', 'denomination#revendeurs#denomination', 'Cette Raison Sociale existe déja' );
$form->input('Raison Sociale', 'denomination', 'text' ,9 , $info_rev->Shw('denomination',1), $denomination_array);


//Pièce d'identité
$piece_identite_array[]  = array('required', 'true', 'Insérer le numéro du Registre de Commerce' );
$piece_identite_array[]  = array('remote', 'piece_identite#revendeurs#piece_identite', 'Ce numéro du Registre de Commerce existe déja' );
$form->input('Registre de Commerce', 'piece_identite', 'text' ,9 , $info_rev->Shw('piece_identite',1), $piece_identite_array);


//Numéro d'agrément
$num_agrement_array[]  = array('required', 'true', 'Insérer le numéro Agrément' );
$num_agrement_array[]  = array('remote', 'num_agrement#revendeurs#num_agrement', 'Ce numéro Agrément existe déja' );
$form->input('Numéro d\'agrément', 'num_agrement', 'text' ,9 , $info_rev->Shw('num_agrement',1), $num_agrement_array);
//qualification
$qualification_array[]  = array('required', 'true', 'Insérer le Secteur Activité' );
$form->input('Secteur Activité', 'qualification', 'text', 9, $info_rev->Shw('qualification',1), $qualification_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Coordonées revendeur');

//ville
$ville_array[]  = array('required', 'true', 'Choisir la ville du Revendeur' );
$form->select_table('Ville', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$selected=$info_rev->Shw('ville',1),$multi=NULL, $where=NULL, $ville_array);

//Adresse
$adresse_array[]  = array('required', 'true', 'Insérer Adresse du Revendeur' );
$form->input('Adresse', 'adresse', 'text', 9, $info_rev->Shw('adresse',1), $adresse_array);

// email
$mail_array[]  = array('required', 'true', 'Insérer Email ' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 6, $info_rev->Shw('email',1), $mail_array);
// Tél
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, $info_rev->Shw('tel',1), $tel_array);
// fax
$fax_array[]  = array('required', 'true', 'Insérer N° de Fax' );
$fax_array[]  = array('minlength', '8', 'Le N° de Fax doit contenir au moins 8 chiffres' );
$fax_array[]  = array('number', 'true', 'Entrez un N° Fax Valid' );
$form->input('Fax', 'fax', 'text', 6, $info_rev->Shw('fax',1), $fax_array);
//End Step 2
$form->step_end();
//Start Step 3
$form->step_start(3, 'Activités Revendeur');

//Groupe  VSAT
$vsat_array[]  = array('OUI', '1' );
$vsat_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente VSAT');
$form->radio('Vente VSAT', 'vsat', $info_rev->Shw('vsat',1), $vsat_array, $radio_js_array);

//Groupe  UHF/VHF
$uhf_vhf_array[]  = array('OUI', '1' );
$uhf_vhf_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente UHF/VHF');
$form->radio('Vente UHF/VHF', 'uhf_vhf', $info_rev->Shw('uhf_vhf',1), $uhf_vhf_array, $radio_js_array);

//Groupe  GSM
$gsm_array[]  = array('OUI', '1' );
$gsm_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente GSM');
$form->radio('Vente GSM', 'gsm', $info_rev->Shw('gsm',1), $gsm_array, $radio_js_array);

//Groupe  BLR
$blr_array[]  = array('OUI', '1' );
$blr_array[]  = array('NON' , '2' );
$radio_js_array[]  = array('required', 'true', 'Vente BLR');
$form->radio('Vente BLR', 'blr', $info_rev->Shw('blr',1), $blr_array, $radio_js_array);

//End step 3
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>




