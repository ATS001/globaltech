<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectifs
//Created : 05-10-2018
//View
//Get all objectifs info 
 $info_objectifs = new Mobjectif_commercial();
//Set ID of Module with POST id
 $info_objectifs->id_objectif_commercial = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_objectifs->get_objectif_commercial())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_objectifs->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('objectif_commercial','Liste des objectifs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier l'Objectif: <?php $info_objectifs->s('id')?>
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
$form = new Mform('edit_objectif_commercial', 'edit_objectif_commercial', '1', 'objectif_commercial', '0', null);
$form->input_hidden('id', $info_objectifs->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));




//Description ==> 
	$array_description[]= array("required", "true", "Insérer Description ...");
	$form->input("Description", "description", "text" ,"9", $info_objectifs->g("description"), $array_description, null, $readonly = null);
//Liste Commerciaux	
//public function select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null, $hard_code = null ) 
$array_commercial[]  = array('required', 'true', 'Choisir un Service....');
$form->select_table('Commercial ', 'id_commercial', 8, 'commerciaux', 'id', 'text' , 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)', $indx = null , $info_objectifs->g("id_commercial"), $multi=NULL, 'is_glbt = "OUI"', $array_commercial, null);	
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

		