<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: taux_change
//Created : 12-12-2019
//View
//Get all taux_change info 
 $info_taux_change = new Mtaux_change();
//Set ID of Module with POST id
 $info_taux_change->id_taux_change = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_taux_change->get_taux_change())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('taux_change','Liste des taux de change', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Actualiser le taux de change de la devise : <?php $info_taux_change->s('devise')?>
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
$form = new Mform('refresh_taux_change', 'refresh_taux_change', '1', 'taux_change', '0', null);
$form->input_hidden('id', $info_taux_change->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Devise ==> 
	$array_id_devise[]= array("required", "true", "Insérer Devise ...");
	$form->input("Devise", "id_devise", "text" ,"5", $info_taux_change->g("devise"), $array_id_devise , null, $readonly = 1);

//Conversion ==> 
	$array_conversion[]= array("required", "true", "Insérez Conversion ...");
    $conversion = '<label style="margin-left:1px;margin-right : 1px;"></label><input id="devise_p" name="devise_p" value="'.$info_taux_change->g("devise_principale").'" class="col-sm-2 col-xs-2 alignLeft" readonly="" type="text">';
	$form->input("Conversion", "conversion", "text" ,"3", $info_taux_change->g("conversion"), $array_conversion , $conversion, $readonly = null);

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

		