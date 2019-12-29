<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 03-10-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('objectif_commercial','Liste des objectifs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un Objectif Commercial
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
 
$form = new Mform('add_objectif_commercial', 'add_objectif_commercial', '', 'objectif_commercial', '0', null);



	//Description ==> 
$array_description[]= array("required", "true", "Insérer Description ...");
$form->input("Description", "description", "text" ,"9", null, $array_description, null, $readonly = null);
	//Liste Services	
$array_commercial[]  = array('required', 'true', 'Choisir un Service....');
$form->select_table('Commercial ', 'id_commercial', 8, 'commerciaux', 'id', 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom) ' , 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)', $indx = '------' , $selected=NULL, $multi=NULL, 'is_glbt = "OUI"', $array_commercial, null);	
	//Date Début ==> 
$array_date_d[]= array("required", "true", "Insérer Date Début ...");
$form->input_date('Date Début', 'date_s', 4, date('d-m-Y'), $array_date_d);

	//Date Fin ==> 
$array_date_f[]= array("required", "true", "Insérer Date Fin ...");
$form->input_date('Date Fin', 'date_e', 4, date('d-m-Y'), $array_date_f);
	//Objectif ==> 
$array_objectif[]= array("required", "true", "Insérer Objectif ...");
$form->input("Objectif", "objectif", "text" ,"5 is-number alignRight", null, $array_objectif, null, $readonly = null);


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

		