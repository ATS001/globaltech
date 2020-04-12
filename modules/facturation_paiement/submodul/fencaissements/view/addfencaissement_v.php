<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fencaissements
//Created : 12-04-2020
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add('fencaissements','Liste des fencaissements', Null, $exec = NULL, 'reply'); ?>

	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un fencaissements
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

$form = new Mform('addfencaissement', 'addfencaissement', '', 'fencaissements', '0', null);

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

//reference ==>
	$array_reference[]= array("required", "true", "Insérer reference ...");
	$form->input("reference", "reference", "text" ,"9", null, $array_reference, null, $readonly = null);
//designation ==>
	$array_designation[]= array("required", "true", "Insérer designation ...");
	$form->input("designation", "designation", "text" ,"9", null, $array_designation, null, $readonly = null);
//idfacture ==>
	$array_idfacture[]= array("required", "true", "Insérer idfacture ...");
	$form->input("idfacture", "idfacture", "text" ,"9", null, $array_idfacture, null, $readonly = null);
//montant ==>
	$array_montant[]= array("required", "true", "Insérer montant ...");
	$form->input("montant", "montant", "text" ,"9", null, $array_montant, null, $readonly = null);
//pj ==>
	$array_pj[]= array("required", "true", "Insérer pj ...");
	$form->input("pj", "pj", "text" ,"9", null, $array_pj, null, $readonly = null);
//date_encaissement ==>
	$array_date_encaissement[]= array("required", "true", "Insérer date_encaissement ...");
	$form->input("date_encaissement", "date_encaissement", "text" ,"9", null, $array_date_encaissement, null, $readonly = null);
//depositaire ==>
	$array_depositaire[]= array("required", "true", "Insérer depositaire ...");
	$form->input("depositaire", "depositaire", "text" ,"9", null, $array_depositaire, null, $readonly = null);
//montant_devise_ext ==>
	$array_montant_devise_ext[]= array("required", "true", "Insérer montant_devise_ext ...");
	$form->input("montant_devise_ext", "montant_devise_ext", "text" ,"9", null, $array_montant_devise_ext, null, $readonly = null);
//mode_payement ==>
	$array_mode_payement[]= array("required", "true", "Insérer mode_payement ...");
	$form->input("mode_payement", "mode_payement", "text" ,"9", null, $array_mode_payement, null, $readonly = null);
//ref_payement ==>
	$array_ref_payement[]= array("required", "true", "Insérer ref_payement ...");
	$form->input("ref_payement", "ref_payement", "text" ,"9", null, $array_ref_payement, null, $readonly = null);


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

		
