<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('entrepots','Liste des Entrepôts', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un Entrepot
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
 
$form = new Mform('addentrepots', 'addentrepots', '', 'entrepots', '0', null);

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

//Référence ==> 
	//$array_reference[]= array("required", "true", "Insérer Référence ...");
	//$form->input("Référence", "reference", "text" ,"9", null, $array_reference, null, $readonly = null);
//Entrepot ==> 
	$array_libelle[]= array("required", "true", "Insérer Entrepot ...");
	$form->input("Entrepôt", "libelle", "text" ,"9", null, $array_libelle, null, $readonly = null);
//Emplacement Physique ==> 
	$array_emplacement[]= array("required", "true", "Insérer Emplacement Physique ...");
	$form->input("Emplacement Physique", "emplacement", "text" ,"9", null, $array_emplacement, null, $readonly = null);
//Date de création ==> 
	$array_date_creation[]= array("required", "true", "Insérer Date de création ...");
	$form->input("Date de création", "date_creation", "date" ,"9", null, $array_date_creation, null, $readonly = null);


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

		