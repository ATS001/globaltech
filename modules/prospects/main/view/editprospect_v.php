<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 19-10-2019
//View
//Get all prospects info 
 $info_prospects = new Mprospects();
//Set ID of Module with POST id
 $info_prospects->id_prospect = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_prospects->get_prospect())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('prospects','Liste des prospects', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le prospect: <?php $info_prospects->s('raison_sociale')?>
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
$form = new Mform('editprospect', 'editprospect', '1', 'prospects', '0', null);
$form->input_hidden('id', $info_prospects->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Commercial ==> 
	$array_ID_COMMERCIAL[]= array("required", "true", "Sélectionnez Commercial ...");
	$form->select_table('Commercial', 'id_commercial', 6, 'commerciaux', 'id', "Concat(prenom,' ',nom)" , "Concat(prenom,' ',nom)", $indx = '------' ,
	$info_prospects->g("id_commercial"),$multi=NULL, $where='etat=1', $array_ID_COMMERCIAL);
//Raison Sociale ==> 
	$array_Raison_Sociale[]= array("required", "true", "Insérez Raison Sociale ...");
	$form->input("Raison Sociale", "raison_sociale", "text" ,"6", $info_prospects->g("raison_sociale"), $array_Raison_Sociale , null, $readonly = null);
//Offre ==> 
	$array_OFFRE[]= array("required", "true", "Sélectionnez Offre ...");
	$form->select_table('Offre', 'offre', 6, 'ref_prospects_offre', 'id', 'offre' , 'offre', $indx = '------' ,$info_prospects->g("offre"),$multi=NULL, $where='etat=1', $array_OFFRE);

//CA Prévisionnel ==> 
	$array_CA_PREVISIONNEL[]= array("required", "true", "Insérez CA Prévisionnel ...");
    $array_CA_PREVISIONNEL[]= array('number', 'true', 'Entrez un montant valide!!!!');
	$form->input("CA Prévisionnel", "ca_previsionnel", "text" ,"6", $info_prospects->g("ca_previsionnel"), $array_CA_PREVISIONNEL, null, $readonly = null);
//Pondération ==> 
	$array_PONDERATION[]= array("required", "true", "Insérez Pondération ...");
    $array_PONDERATION[]= array('number', 'true', 'Entrez un nombre valide!!!!');
	$form->input("Pondération %", "ponderation", "text" ,"6", $info_prospects->g("ponderation"), $array_PONDERATION, null, $readonly = null);
//CA Pondéré ==> 
	$form->input("CA Pondéré", "ca_pondere", "text" ,"6", $info_prospects->g("ca_pondere"), null, null, $readonly = null);
//Date Entrée ==> 
	//$array_DATE_ENTREE[]= array("required", "true", "Insérez Date Entrée ...");
	$form->input("Date Entrée", "date_entree", "date" ,"6", $info_prospects->g("date_entree"), null, null, $readonly = null);
//Date Cible ==> 
	//$array_DATE_CIBLE[]= array("required", "true", "Insérez Date Cible ...");
	$form->input("Date Cible", "date_cible", "date" ,"6", $info_prospects->g("date_cible"), null, null, $readonly = null);
//Statut Deal ==> 
	$array_STAUT_DEAL[]= array("required", "true", "Sélectionnez Statut Deal ...");
	$array_STAUT_DEAL = array('Chaud' => 'Chaud', 'Froid' => 'Froid');
	$form->select("Statut Deal", "statut_deal", 6, $array_STAUT_DEAL,null ,$info_prospects->g("statut_deal"), null);
//Commentaires ==> 
	$form->input("Commentaires", "commentaires", "text" ,"6", $info_prospects->g("commentaires"), null, null, $readonly = null);

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

		