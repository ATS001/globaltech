<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 05-10-2018
//View
//Get all objectifs info 
 $info_objectifs = new Mobjectif_mensuel();
//Set ID of Module with POST id
 $info_objectifs->id_objectif_mensuel = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_objectifs->get_objectif_mensuel())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_objectifs->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('objectif_mensuel','Liste des objectifs', Null, $exec = NULL, 'reply'); ?>
					
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
$form = new Mform('edit_objectif_mensuel', 'edit_objectif_mensuel', '1', 'objectif_mensuel', '0', null);
$form->input_hidden('id', $info_objectifs->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


$array_commercial[]  = array('required', 'true', 'Choisir un Service....');
$form->select_table('Commercial ', 'id_commercial', 8, 'commerciaux', 'id', 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom) ' , 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)', $indx = '------' , $info_objectifs->g("id_commercial"), $multi=NULL, 'is_glbt = "OUI"', $array_commercial, null);	
//Année
$array_mois[]= array("required", "true", "Insérer l'année ...");
$options_mois  = array(date('Y') => date('Y'),  date('Y')+1 => date('Y')+1);
$form->select('Année', 'annee', 3, $options_mois, $indx = NULL , date('Y',strtotime($info_objectifs->g("date_s"))), $multi = NULL, $hard_code = null ); 
//Mois
$array_mois[]= array("required", "true", "Insérer le Mois ...");
$options_mois  = array(1 => 'Janvier'  ,2 =>  'Février' , 3 => 'Mars'  , 4 => 'Avril'  , 5 => 'Mai'  , 6 => 'Juin' , 7 => 'Juillet'  , 8 => 'Août' , 9 => 'Septembre' , 10 => 'Octobre'  , 11 => 'Novembre' , 12 => 'Décembre');
$form->select('Mois', 'mois', 3, $options_mois, $indx = NULL ,date('m',strtotime($info_objectifs->g("date_s"))), $multi = NULL, $hard_code = null ); 
/*	//Date Début ==> 
$array_date_d[]= array("required", "true", "Insérer Date Début ...");
$form->input_date('Date Début', 'date_s', 4, date('d-m-Y'), $array_date_d);
	//Date Fin ==> 
$array_date_f[]= array("required", "true", "Insérer Date Fin ...");
$form->input_date('Date Fin', 'date_e', 4, date('d-m-Y'), $array_date_f);*/
	//Objectif ==> 
$array_objectif[]= array("required", "true", "Insérer Objectif ...");
$form->input("Montant de l'objectif", "objectif", "text" ,"5 is-number alignRight", $info_objectifs->g("objectif"), $array_objectif, null, $readonly = null);
$array_seuil[]= array("required", "true", "Insérer Objectif ...");
$form->input("Seuil de réussite", "seuil", "text" ,"2 is-decimal alignRight", $info_objectifs->g("seuil"), $array_seuil, null, $readonly = null);



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

		