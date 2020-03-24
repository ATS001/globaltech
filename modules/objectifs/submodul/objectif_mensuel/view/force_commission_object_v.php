<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_mensuel
//Created : 28-12-2019
//View
//Get all objectif_mensuel info 
 $info_objectif_mensuel = new Mobjectif_mensuel();
//Set ID of Module with POST id
 $info_objectif_mensuel->id_objectif_mensuel = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_objectif_mensuel->get_objectif_mensuel())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('objectif_mensuel','Liste des objectif_mensuel', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?> objectif_mensuel: <?php $info_objectif_mensuel->s('id')?>
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
$form = new Mform('force_commission_object', 'force_commission_object', '', 'objectif_mensuel', '0', null);
$form->input_hidden('id', $info_objectif_mensuel->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

$form->input("Montant commission générée", "commission_reel", "text" ,"5 is-number alignRight", (($info_objectif_mensuel->g('realise')* $info_objectif_mensuel->g('taux_commission'))/100), null, null, $readonly = 1);
$array_objectif[]= array("required", "true", "Insérer Objectif ...");
$form->input("Montant commission forcé", "montant_benif", "text" ,"5 is-number alignRight", $info_objectif_mensuel->g('montant_benif'), $array_objectif, null, $readonly = null);

//Add fields input here


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

		