<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 05-10-2018
//View
//Get all objectifs info
 $info_objectifs = new Mobjectif_mensuel_groupe();
//Set ID of Module with POST id
 $info_objectifs->id_objectif_mensuel_groupe = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false.
 if(!MInit::crypt_tp('id', null, 'D') or !$info_objectifs->get_objectif_mensuel_groupe())
 {
 	// returne message error red to client
 	exit('3#'.$info_objectifs->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add('objectif_mensuel_groupe','Liste des objectifs commercial', Null, $exec = NULL, 'reply'); ?>

	</div>
</div>
<div class="page-header">
	<h1>
		Modifier : <?php echo $info_objectifs->g('description') .' pour : '. $info_objectifs->g('commercial')?>
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
$form = new Mform('editobjectifgroupe', 'editobjectifgroupe', '1', 'objectif_mensuel_groupe', '0', null);
$form->input_hidden('id', $info_objectifs->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));



	//Description ==>
$array_description[]= array("required", "true", "Insérer Description ");
$form->input("Description", "description", "text" ,"9",$info_objectifs->g("description"), $array_description,null, $readonly = null);

$array_commercial[]  = array('required', 'true', 'Choisir un commercial');
$form->select_table('Commercial ', 'id_commercial', 8, 'commerciaux', 'id', 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom) ' , 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)', $indx = '------' , $info_objectifs->g("id_commercial"), $multi=NULL, 'is_glbt = "OUI"', $array_commercial, null);
//Année
$array_mois[]= array("required", "true", "Insérer l'année ...");
$options_mois  = array(date('Y') => date('Y'),  date('Y')+1 => date('Y')+1);
$form->select('Année', 'annee', 3, $options_mois, $indx = NULL , date('Y',strtotime($info_objectifs->g("annee"))), $multi = NULL, $hard_code = null );

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

//JS Bloc

});
</script>
