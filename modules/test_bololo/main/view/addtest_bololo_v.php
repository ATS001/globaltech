<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: test_bololo
//Created : 29-10-2017
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('test_bololo','Liste des test_bololo', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un test_bololo
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
 
$form = new Mform('addtest_bololo', 'addtest_bololo', '', 'test_bololo', '0', null);

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

//Nom ==> 
	$array_nom[]= array("required", "true", "Insérer Nom ...");
	$form->input("Nom", "nom", "text" ,"9", null, $array_nom, null, $readonly = null);
//Prénom ==> 
	$array_prenom[]= array("required", "true", "Insérer Prénom ...");
	$form->input("Prénom", "prenom", "text" ,"9", null, $array_prenom, null, $readonly = null);
//Date Naiss ==> 
	$array_dat_nais[]= array("required", "true", "Insérer Date Naiss ...");
	$form->input("Date Naiss", "dat_nais", "text" ,"9", null, $array_dat_nais, null, $readonly = null);


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

		