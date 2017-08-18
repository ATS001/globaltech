 <?php 
defined('_MEXEC') or die; 
$info_gsm = new  Mgsm();
$info_gsm->id_gsm= Mreq::tp('id');

	if(!MInit::crypt_tp('id', null, 'D')  or !$info_gsm->get_gsm())
	{  
  //returne message error red to client 
		exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	$id_gsm=Mreq::tp('id');
?>

 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('gsm_technologie','Liste Technologies GSM', MInit::crypt_tp('id',$id_gsm), $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Technologie GSM 
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

$form = new Mform('addgsm_technologie', 'addgsm_technologie', '', 'gsm_technologie&'.MInit::crypt_tp('id',$id_gsm), '');
$form->input_hidden('id_site_gsm',  $id_gsm);

$array_gsm_technologie = new Mgsm_technologie();
$arr_technologie = $array_gsm_technologie->get_technologies($id_gsm);



//Set Step's for wizard form
$wizard_array[] = array(1,'Etape 1','active');
$form->wizard_steps = $wizard_array;
//Start Step 1
$form->step_start(1, 'Renseignements technologie');

//Technologie

$technologie_array[]  = array('required', 'true', 'Insérer la Technologie' );
//$form->input('Technologie', 'technologie', 'text' ,9 , null, $technologie_array);


$form->select('Technologie', 'technologie', 9, $arr_technologie, $indx = '*****' ,$selected = NULL, $multi = NULL );

/*$form->select_table('Technologie', 'technologie', 9, 'technologies_gsm t,gsm_stations g', 't.id', 't.libelle' , 't.libelle', $indx = '*****' ,
	$selected=NULL,$multi=NULL, $where='t.etat=1 and 
	(((`t`.`libelle` = IF((`g`.`tech_2g` = 1),"2G",NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_3g` = 1),"3G",NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_4g` = 1),"4G",NULL))
         OR (`t`.`libelle` = IF((`g`.`tech_cdma` = 1),"CDMA",NULL)))
       AND (NOT(`t`.`id` IN(SELECT
                              `tech`.`technologie`
                            FROM `gsm_technologie` `tech`
                            WHERE (`tech`.`id_site_gsm` = `g`.`id`)))))

	and g.id='.$id_gsm, $technologie_array);*/

//Marque BTS

$marque_bts_array[]  = array('required', 'true', 'Insérer la Marque BTS' );
$form->input('Marque BTS', 'marque_bts', 'text' ,9 , null, $marque_bts_array);

//Numéro de Série
$num_serie_array[]  = array('required', 'true', 'Insérer le Numéro de Série' );
$num_serie_array[]  = array('remote', 'num_serie#gsm_technologie#num_serie', 'Ce Numéro de Série existe déja' );
$form->input('Numéro de Série', 'num_serie', 'text' ,9 , null, $num_serie_array);

//Modèle Antenne
$modele_antenne_array[]  = array('required', 'true', 'Insérer le Modèle Antenne' );
$form->input('Modèle Antenne', 'modele_antenne', 'text', 9, null, $modele_antenne_array);

//Nombre Radios
$nbr_radio_array[]  = array('required', 'true', 'Insérer le Nombre Radios' );
$nbr_radio_array[]  = array('number', 'true', 'Entrez un Nombre Radio valide' );
$form->input('Nombre Radios', 'nbr_radio', 'text', 9, null, $nbr_radio_array);

//Group Nombre Radios
$nbr_secteur_array[]  = array(1, 1);
$nbr_secteur_array[]  = array(2, 2 );
$nbr_secteur_array[]  = array(3, 3 );
$form->radio('Nombre Secteurs', 'nbr_secteur', 1 , $nbr_secteur_array, null);

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