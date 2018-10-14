<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs_service
//Created : 23-09-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('objectifs_service','Liste des objectifs_service', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un objectifs_service
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
 
$form = new Mform('addobjectifs_service', 'addobjectifs_service', '', 'objectifs_service', '0', null);

//Date Example
//$array_date[]= array('required', 'true', 'Insérer la date de ...');
//$form->input_date('Date', 'date_', 4, date('d-m-Y'), $array_date);
//Select Table Example


//$select_array[]  = array('required', 'true', 'Choisir un ....');
//$form->select_table('Select ', 'select', 8, 'table', 'id', 'text' , 'text', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $select_array, null);



//Select Simple Example
//$field_opt = array('O' => 'OUI' , 'N' => 'NON' );
//$form->select('Label Field', 'field', 2, $field_opt, $indx = NULL ,$selected = NULL, $multi = NULL);

//Separate Zone title
//$form->bloc_title('Zone separated');


//Input Example
//$form->input('Label field', 'field', 'text' ,'class', '0', null, null, $readonly = null);
//For more Example see form class

//Descreption ==> 
	$array_descreption[]= array("required", "true", "Insérer Descreption ...");
	$form->input("Descreption", "descreption", "text" ,"9", null, $array_descreption, null, $readonly = null);
//Id Service ==> 
	$array_service[]= array("required", "true", "Insérer Id Service ...");
	
	$form->select_table('Service ', 'service', 6, 'services', 'id', 'service' , 'service', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $array_service, null);
//Objectif à atteindre ==> 
	$array_objectif[]  = array('number', 'true', 'Entrez un nombre valide' );
	$array_objectif[]= array("required", "true", "Insérer Objectif à atteindre ...");
	$form->input("Objectif à atteindre", "objectif", "text" ,"4 is-number alignRight", null, $array_objectif, null, $readonly = null);
//Date début ==> 
	$array_date_debut[]= array("required", "true", "Insérer Date début ...");
	$form->input_date('Date Début', 'date_debut', 4, date('d-m-Y'), $array_date_debut);
//Date Fin ==> 
	$array_date_fin[]= array("required", "true", "Insérer Date Fin ...");
	$form->input_date('Date Fin', 'date_fin', 4, date('d-m-Y'), $array_date_fin);


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

		