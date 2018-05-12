<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 13-05-2018
//View
//Get all bl info 
 $info_bl = new Mbl();
//Set ID of Module with POST id
 $info_bl->id_bl = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_bl->get_bl())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('bl','Liste des bl', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le bl: <?php $info_bl->s('id')?>
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
$form = new Mform('editbl', 'editbl', '1', 'bl', '0', null);
$form->input_hidden('id', $info_bl->g('id'));
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

//reference ==> 
	$array_reference[]= array("required", "true", "Insérer reference ...");
	$form->input("reference", "reference", "text" ,"9", $info_bl->g("reference"), $array_reference , null, $readonly = null);
//client ==> 
	$array_client[]= array("required", "true", "Insérer client ...");
	$form->input("client", "client", "text" ,"9", $info_bl->g("client"), $array_client , null, $readonly = null);
//designation projet ==> 
	$array_projet[]= array("required", "true", "Insérer designation projet ...");
	$form->input("designation projet", "projet", "text" ,"9", $info_bl->g("projet"), $array_projet , null, $readonly = null);
//ref bon commande client ==> 
	$array_ref_bc[]= array("required", "true", "Insérer ref bon commande client ...");
	$form->input("ref bon commande client", "ref_bc", "text" ,"9", $info_bl->g("ref_bc"), $array_ref_bc , null, $readonly = null);
//Devis ==> 
	$array_iddevis[]= array("required", "true", "Insérer Devis ...");
	$form->input("Devis", "iddevis", "text" ,"9", $info_bl->g("iddevis"), $array_iddevis , null, $readonly = null);
//date_bl ==> 
	$array_date_bl[]= array("required", "true", "Insérer date_bl ...");
	$form->input("date_bl", "date_bl", "text" ,"9", $info_bl->g("date_bl"), $array_date_bl , null, $readonly = null);



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

		