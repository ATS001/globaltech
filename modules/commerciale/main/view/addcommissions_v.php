<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 04-01-2018
//View
//Get all commerciale info
$commerciale = new Mcommerciale();
//Set ID of Module with POST id
$commerciale->id_commerciale = Mreq::tp('id_commerciale');

//Check if Post ID <==> Post idc or get_modul return false.
if (!MInit::crypt_tp('id_commerciale', null, 'D') or !$commerciale->get_commerciale()) {
    // returne message error red to client
    exit('3#' . $commerciale->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$id_commerciale=$commerciale->g("id");
//var_dump($id_commerciale);

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

        <?php TableTools::btn_add('commissions','Liste des commissions', MInit::crypt_tp('id',$id_commerciale), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
	<h1>
		Ajouter une commission
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
 
$form = new Mform('addcommissions', 'addcommissions', '', 'commissions&'.MInit::crypt_tp('id',$id_commerciale), '0', null);
//$form = new Mform('addcommissions', 'addcommissions', '', 'commissions&'.MInit::crypt_tp('id',$id_commerciale), '');

$form->input_hidden('id_commerciale', $id_commerciale);




//Objet ==>
$array_objet[]= array("required", "true", "Insérer objet");
$form->input("Object", "objet", "text" ,"9", null, $array_objet, null, $readonly = null);

//Montant ==>
	$array_mt[]= array("required", "true", "Insérer le montant");
	$form->input("Montant", "credit", "text" ,"9", null, $array_mt, null, $readonly = null);


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

		