 <?php

//Get all Installateur info
 $info_gsm_technologie= new Mgsm_technologie();
//Set ID of Module with POST id
 $info_gsm_technologie->id_technologie = Mreq::tp('id');
 
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_gsm_technologie->get_technologie())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_gsm_technologie->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 //Get id gsm station
 $id_gsm=$info_gsm_technologie->Shw('id_site_gsm',1);

 ?>

 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('gsm_technologie','Liste Technologies GSM', MInit::crypt_tp('id',$id_gsm), $exec = NULL, 'reply'); ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier Technologie GSM
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_gsm_technologie->Shw('marque_bts',1).' '.$info_gsm_technologie->Shw('num_serie',1).' -'.$info_gsm_technologie->id_technologie.'-)';?>

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

$form = new Mform('editgsm_technologie', 'editgsm_technologie', $info_gsm_technologie->Shw('id',1), 'gsm_technologie&'.MInit::crypt_tp('id',$id_gsm), null);
$form->input_hidden('id', $info_gsm_technologie->Shw('id',1));
$form->input_hidden('id_site_gsm', $id_gsm);
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements technologie');

//$form->input('Site gsm','id_site_gsm','text', 9,$info_gsm_technologie->Shw('id_site_gsm',1),null);
//Technologie

/*$technologie_array[]  = array('required', 'true', 'Insérer la Technologie' );
//$form->input('Technologie', 'technologie', 'text' ,9 , null, $technologie_array);

$form->select_table('Technologie', 'technologie', 9, 'technologie_gsm_by_station_edit', 'id', 'libelle' , 'libelle', $indx = '*****',
$selected=$info_gsm_technologie->Shw('technologie',1),$multi=NULL, $where='etat=1 and id_site='. $id_gsm , $technologie_array);*/


//Marque BTS

$marque_bts_array[]  = array('required', 'true', 'Insérer la Marque BTS' );
$form->input('Marque BTS', 'marque_bts', 'text' ,9 , $info_gsm_technologie->Shw('marque_bts',1), $marque_bts_array);

//Numéro de Série
$num_serie_array[]  = array('required', 'true', 'Insérer le Numéro de Série' );
$num_serie_array[]  = array('remote', 'num_serie#gsm_technologie#num_serie', 'Ce Numéro de Série existe déja' );
$form->input('Numéro de Série', 'num_serie', 'text' ,9 , $info_gsm_technologie->Shw('num_serie',1), $num_serie_array);

//Modèle Antenne
$modele_antenne_array[]  = array('required', 'true', 'Insérer le Modèle Antenne' );
$form->input('Modèle Antenne', 'modele_antenne', 'text', 9, $info_gsm_technologie->Shw('modele_antenne',1), $modele_antenne_array);

//Nombre Radios
$nbr_radio_array[]  = array('required', 'true', 'Insérer le Nombre Radios' );
$nbr_radio_array[]  = array('number', 'true', 'Entrez un Nombre Radio valide' );
$form->input('Nombre Radios', 'nbr_radio', 'text', 9, $info_gsm_technologie->Shw('nbr_radio',1), $nbr_radio_array);

//Group Nombre Radios
$nbr_secteur_array[]  = array(1, 1);
$nbr_secteur_array[]  = array(2, 2 );
$nbr_secteur_array[]  = array(3, 3 );
$form->radio('Nombre Secteurs', 'nbr_secteur', $info_gsm_technologie->Shw('nbr_secteur',1) , $nbr_secteur_array, null);

//End Step 1
$form->step_end();

//Button submit 
$form->button('Enregistrer la technologie');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
<?php 
//SYS MRN ERP
// Modul: gsm => View