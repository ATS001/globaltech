<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 05-10-2018
//View
//Get all objectifs info 
 $info_objectifs = new Mobjectif_service();
//Set ID of Module with POST id
 $info_objectifs->id_objectif_service = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_objectifs->get_objectif_service())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_objectifs->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('objectif_service','Liste des objectifs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le objectifs: <?php $info_objectifs->s('id')?>
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
$form = new Mform('edit_objectif_service', 'edit_objectif_service', '1', 'objectif_service', '0', null);
$form->input_hidden('id', $info_objectifs->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


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

//Description ==> 
	$array_description[]= array("required", "true", "Insérer Description ...");
	$form->input("Description", "description", "text" ,"9", $info_objectifs->g("description"), $array_description, null, $readonly = null);
//Liste Services	
   $array_service[]  = array('required', 'true', 'Choisir un Service....');
   $form->select_table('Service ', 'id_service', 8, 'services', 'id', 'service' , 'service', $indx = '------' ,$info_objectifs->g("id_service"),$multi=NULL, $where=NULL, $array_service, null);	
//Date Début ==> 
	$array_date_d[]= array("required", "true", "Insérer Date Début ...");
	$form->input_date('Date Début', 'date_s', 4, $info_objectifs->g("date_s"), $array_date_d);
	
//Date Fin ==> 
	$array_date_f[]= array("required", "true", "Insérer Date Fin ...");
	$form->input_date('Date Fin', 'date_e', 4, $info_objectifs->g("date_e"), $array_date_f);
//Objectif ==> 
	$array_objectif[]= array("required", "true", "Insérer Objectif ...");
	$form->input("Objectif", "objectif", "text" ,"5 is-number alignRight", $info_objectifs->g("objectif"), $array_objectif, null, $readonly = null);

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

		