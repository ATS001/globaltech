<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//View
//Get all entrepots info 
 $info_entrepots = new Mentrepots();
//Set ID of Module with POST id
 $info_entrepots->id_entrepots = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_entrepots->get_entrepots())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('entrepots','Liste des entrepots', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le entrepots: <?php $info_entrepots->s('id')?>
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
$form = new Mform('editentrepots', 'editentrepots', '1', 'entrepots', '0', null);
$form->input_hidden('id', $info_entrepots->g('id'));
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

//Référence ==> 
//	$array_reference[]= array("required", "true", "Insérer Référence ...");
//	$form->input("Référence", "reference", "text" ,"9", $info_entrepots->g("reference"), $array_reference , null, $readonly = null);
//Entrepot ==> 
	$array_libelle[]= array("required", "true", "Insérer Entrepot ...");
	$form->input("Entrepot", "libelle", "text" ,"9", $info_entrepots->g("libelle"), $array_libelle , null, $readonly = null);
//Emplacement Physique ==> 
	$array_emplacement[]= array("required", "true", "Insérer Emplacement Physique ...");
	$form->input("Emplacement Physique", "emplacement", "text" ,"9", $info_entrepots->g("emplacement"), $array_emplacement , null, $readonly = null);
//Date de création ==> 
	$array_date_creation[]= array("required", "true", "Insérer Date de création ...");
	$form->input("Date de création", "date_creation", "date" ,"9", $info_entrepots->g("date_creation"), $array_date_creation , null, $readonly = null);



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

		