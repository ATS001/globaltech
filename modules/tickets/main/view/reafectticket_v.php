<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('tickets','Liste des tickets', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un tickets
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
 
$form = new Mform('reafectticket', 'reafectticket', '', 'tickets', '0', null);

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

//Client ==> 
	$array_id_client[]= array("required", "true", "Insérer Client ...");
	$form->input("Client", "id_client", "text" ,"9", null, $array_id_client, null, $readonly = null);
//Projet ==> 
	$array_projet[]= array("required", "true", "Insérer Projet ...");
	$form->input("Projet", "projet", "text" ,"9", null, $array_projet, null, $readonly = null);
//Message ==> 
	$array_message[]= array("required", "true", "Insérer Message ...");
	$form->input("Message", "message", "text" ,"9", null, $array_message, null, $readonly = null);
//Date prévisionnelle ==> 
	$array_date_previs[]= array("required", "true", "Insérer Date prévisionnelle ...");
	$form->input("Date prévisionnelle", "date_previs", "text" ,"9", null, $array_date_previs, null, $readonly = null);
//Date de réalisation ==> 
	$array_date_realis[]= array("required", "true", "Insérer Date de réalisation ...");
	$form->input("Date de réalisation", "date_realis", "text" ,"9", null, $array_date_realis, null, $readonly = null);
//Type produit ==> 
	$array_type_produit[]= array("required", "true", "Insérer Type produit ...");
	$form->input("Type produit", "type_produit", "text" ,"9", null, $array_type_produit, null, $readonly = null);
//Catégorie produit ==> 
	$array_categorie_produit[]= array("required", "true", "Insérer Catégorie produit ...");
	$form->input("Catégorie produit", "categorie_produit", "text" ,"9", null, $array_categorie_produit, null, $readonly = null);
//Technicien ==> 
	$array_idtechnicien[]= array("required", "true", "Insérer Technicien ...");
	$form->input("Technicien", "idtechnicien", "text" ,"9", null, $array_idtechnicien, null, $readonly = null);


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
    
$('#id_client').attr('readonly', false);

});
</script>	

		