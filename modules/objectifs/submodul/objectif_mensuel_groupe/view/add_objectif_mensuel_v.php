<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 03-10-2018
//View


$id_commercial = Mreq::tp('id_commercial');
$annee = Mreq::tp('annee');

/*
$info_objectif_mensuel =  new Mobjectif_mensuel();
$info_objectif_mensuel->id_objectif_mensuel = Mreq::tp('id_commercial');
$info_objectif_mensuel->get_objectif_mensuel();
$id_commercial = $info_objectif_mensuel->objectif_mensuel_info["id_commercial"];
*/
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add('objectif_mensuel','Liste des objectifs',"id_commercial=$id_commercial&annee=$annee", $exec = NULL, 'reply'); ?>

	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un Objectif Mensuel
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- Bloc form Add Devis-->
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

$form = new Mform('add_objectif_mensuel', 'add_objectif_mensuel', '', 'objectif_mensuel&'."id_commercial=$id_commercial&annee=$annee", '0', null);



	//Description ==>
/*$array_description[]= array("required", "true", "Insérer Description ...");
$form->input("Description", "description", "text" ,"9", null, $array_description, null, $readonly = null);*/
	//Liste Services
$array_commercial[]  = array('required', 'true', 'Choisir un Service....');
$form->select_table('Commercial ', 'id_commercial', 8, 'commerciaux', 'id', 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom) ' , 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)', $indx = '------' , $selected=NULL, $multi=NULL, 'is_glbt = "OUI"', $array_commercial, null);
//Année
$array_mois[]= array("required", "true", "Insérer l'année ...");
$options_mois  = array(date('Y') => date('Y'),  date('Y')+1 => date('Y')+1);
$form->select('Année', 'annee', 3, $options_mois, $indx = NULL ,$selected = NULL, $multi = NULL, $hard_code = null );
//Mois
$array_mois[]= array("required", "true", "Insérer le Mois ...");
$options_mois  = array(0 => 'Toute l\'année' ,1 => 'Janvier'  ,2 =>  'Février' , 3 => 'Mars'  , 4 => 'Avril'  , 5 => 'Mai'  , 6 => 'Juin' , 7 => 'Juillet'  , 8 => 'Août' , 9 => 'Septembre' , 10 => 'Octobre'  , 11 => 'Novembre' , 12 => 'Décembre');
$form->select('Mois', 'mois', 3, $options_mois, $indx = NULL ,$selected = date('m'), $multi = NULL, $hard_code = null );
/*	//Date Début ==>
$array_date_d[]= array("required", "true", "Insérer Date Début ...");
$form->input_date('Date Début', 'date_s', 4, date('d-m-Y'), $array_date_d);
	//Date Fin ==>
$array_date_f[]= array("required", "true", "Insérer Date Fin ...");
$form->input_date('Date Fin', 'date_e', 4, date('d-m-Y'), $array_date_f);*/
	//Objectif ==>
$array_objectif[]= array("required", "true", "Insérer Objectif ...");
$form->input("Montant de l'objectif", "objectif", "text" ,"5 is-number alignRight", null, $array_objectif, null, $readonly = null);
$array_seuil[]= array("required", "true", "Insérer Objectif ...");
$form->input("Seuil de réussite", "seuil", "text" ,"2 is-decimal alignRight", null, $array_seuil, null, $readonly = null);


$form->button('Enregistrer');
	//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
<!-- End Add devis bloc -->

<script type="text/javascript">
$(document).ready(function() {

//JS bloc

});
</script>
