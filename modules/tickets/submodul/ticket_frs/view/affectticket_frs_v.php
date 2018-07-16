<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('ticket_frs','Liste des ticket_frs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un ticket_frs
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
 
$form = new Mform('affectticket_frs', 'affectticket_frs', '', 'ticket_frs', '0', null);

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

//Fournisseur ==> 
	$array_id_fournisseur[]= array("required", "true", "Insérer Fournisseur ...");
	$form->input("Fournisseur", "id_fournisseur", "text" ,"9", null, $array_id_fournisseur, null, $readonly = null);
//Date incident ==> 
	$array_date_incident[]= array("required", "true", "Insérer Date incident ...");
	$form->input("Date incident", "date_incident", "text" ,"9", null, $array_date_incident, null, $readonly = null);
//Nature incident ==> 
	$array_nature_incident[]= array("required", "true", "Insérer Nature incident ...");
	$form->input("Nature incident", "nature_incident", "text" ,"9", null, $array_nature_incident, null, $readonly = null);
//Description ==> 
	$array_description[]= array("required", "true", "Insérer Description ...");
	$form->input("Description", "description", "text" ,"9", null, $array_description, null, $readonly = null);
//Prise en charge Fournisseur ==> 
	$array_prise_charge_frs[]= array("required", "true", "Insérer Prise en charge Fournisseur ...");
	$form->input("Prise en charge Fournisseur", "prise_charge_frs", "text" ,"9", null, $array_prise_charge_frs, null, $readonly = null);
//Prise en charge Globaltech ==> 
	$array_prise_charge_glbt[]= array("required", "true", "Insérer Prise en charge Globaltech ...");
	$form->input("Prise en charge Globaltech", "prise_charge_glbt", "text" ,"9", null, $array_prise_charge_glbt, null, $readonly = null);
//Technicien ==> 
	$array_id_technicien[]= array("required", "true", "Insérer Technicien ...");
	$form->input("Technicien", "id_technicien", "text" ,"9", null, $array_id_technicien, null, $readonly = null);
//Date affectation ==> 
	$array_date_affectation[]= array("required", "true", "Insérer Date affectation ...");
	$form->input("Date affectation", "date_affectation", "text" ,"9", null, $array_date_affectation, null, $readonly = null);
//Code clôture ==> 
	$array_code_cloture[]= array("required", "true", "Insérer Code clôture ...");
	$form->input("Code clôture", "code_cloture", "text" ,"9", null, $array_code_cloture, null, $readonly = null);
//Observation ==> 
	$array_observation[]= array("required", "true", "Insérer Observation ...");
	$form->input("Observation", "observation", "text" ,"9", null, $array_observation, null, $readonly = null);


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

		