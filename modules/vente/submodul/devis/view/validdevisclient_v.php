<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 09-10-2017
//View
//Get all devis info 
 $info_devis = new Mdevis();
//Set ID of Module with POST id
 $info_devis->id_devis = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_devis->get_devis())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('devis','Liste des devis', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?> devis: <?php $info_devis->s('id')?>
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
$form = new Mform('validdevisclient', 'validdevisclient', '', 'devis', '0', null);
$form->input_hidden('id', $info_devis->g('id'));
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

//Add fields input here
//Réponse client
$hard_ref_bc = '<label style="margin-left:15px;margin-right : 20px;">Référence Bon de commande </label><input id="ref_bc" name="ref_bc" class="input-large" value="" type="text">';
$reponse_opt = array('valid' => 'Validation Devis', 'modif' => 'Modification' , 'refus' => 'Réfus' );
$form->select('Réponse client', 'reponse', 6, $reponse_opt, $indx = NULL ,$selected = NULL, $multi = NULL);
//Mode validation
$mode_opt = array('bc' => 'Bon de commande', 'ar' => 'Acusé devis', 'email' => 'Email' , 'tel' => 'Téléphone' );
$form->select('Mode communication', 'modcom', 3, $mode_opt, $indx = NULL ,$selected = NULL, $multi = NULL, $hard_ref_bc);
//PJ
$form->input('Pièce jointe', 'scan', 'file', 6, null, null);
$form->file_js('scan', 1000000, 'pdf');
//Remarque

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
    

});
</script>	

		