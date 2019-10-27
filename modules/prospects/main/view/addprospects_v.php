<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 19-10-2019
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('prospects','Liste des prospects', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un Prospect
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
 
$form = new Mform('addprospects', 'addprospects', '', 'prospects', '0', null);

//Commercial ==> 
	$array_ID_COMMERCIAL[]= array("required", "true", "Sélectionnez Commercial ...");
	$form->select_table('Commercial', 'id_commercial', 6, 'commerciaux', 'id', "Concat(prenom,' ',nom)" , "Concat(prenom,' ',nom)", $indx = '------' ,
	$selected=NULL,$multi=NULL, $where='etat=1', $array_ID_COMMERCIAL);
//Raison Sociale ==> 
	$array_Raison_Sociale[]= array("required", "true", "Insérez Raison Sociale ...");
	$array_Raison_Sociale[]= array('remote', 'raison_sociale#prospects#raison_sociale', 'Cette raison sociale existe déja'); 
	$form->input("Raison Sociale", "raison_sociale", "text" ,"6", null, $array_Raison_Sociale, null, $readonly = null);
//Offre
	$array_offre[]= array("required", "true", "Sélectionnez Offre ...");
	$form->select_table('Offre', 'offre', 6, 'categorie_client', 'id', 'categorie_client' , 'categorie_client', $indx = '------' ,null,$multi=NULL, $where='etat=1', $array_offre);
//CA Prévisionnel ==> 
	$array_CA_PREVISIONNEL[]= array("required", "true", "Insérez CA Prévisionnel ...");
    $array_CA_PREVISIONNEL[]= array('number', 'true', 'Entrez un montant valide!!!!');
	$form->input("CA Prévisionnel", "ca_previsionnel", "text" ,"6", null, $array_CA_PREVISIONNEL, null, $readonly = null);
//Pondération ==> 
	$array_PONDERATION[]= array("required", "true", "Insérez Pondération ...");
    $array_PONDERATION[]= array('number', 'true', 'Entrez un nombre valide!!!!');
    $ca_pondere = '<label style="margin-left:10px;margin-right : 20px;">CA Pondéré: </label><input id="ca_pondere" name="ca_pondere" value="0" class="input-large alignRight" readonly="" type="text">';
	$form->input("Pondération %", "ponderation", "text" ,"1", null, $array_PONDERATION, $ca_pondere, null);
//Date Entrée ==> 
	//$array_DATE_ENTREE[]= array("required", "true", "Insérez Date Entrée ...");
	$form->input("Date Entrée", "date_entree", "date" ,"6", null, null, null, $readonly = null);
//Date Cible ==> 
	//$array_DATE_CIBLE[]= array("required", "true", "Insérez Date Cible ...");
	$form->input("Date Cible", "date_cible", "date" ,"6", null, null, null, $readonly = null);
//Statut Deal ==> 
	$array_STAUT_DEAL[]= array("required", "true", "Sélectionnez Statut Deal ...");
	$array_STAUT_DEAL = array('Chaud' => 'Chaud', 'Froid' => 'Froid');
	$form->select("Statut Deal", "statut_deal", 6, $array_STAUT_DEAL, null,'Chaud', null);
//Commentaires ==> 
	$form->input("Commentaires", "commentaires", "text" ,"6", null, null, null, $readonly = null);

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
    
            $('#ponderation').on('change', function () {
			
            if ($("#ca_previsionnel").val() != null) {

                $('#ca_pondere').val(parseFloat($('#ca_previsionnel').val())+ ( parseFloat($('#ca_previsionnel').val())) * parseFloat($('#ponderation').val()) / 100 );

            } 

        });

});
</script>	

		