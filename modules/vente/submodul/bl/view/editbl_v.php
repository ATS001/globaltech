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
				
		<?php TableTools::btn_add('bl','Liste des bons de livraison', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier BL: <?php $info_bl->s('id')?>
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



$form->extra_html('table_product', $info_bl->Gettable_d_bl());

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

$('.qte, .liv').bind('input change',function() {
        var $qte_id = $(this).attr('id');
        var $id = $qte_id.substr($qte_id.lastIndexOf("_")+1);
        var $val_stock = $('#stok_'+$id).text();
        var $val_stock = parseFloat($val_stock) ? parseFloat($val_stock) : 0;
        var $val_qte   = $('#qte_'+$id).val();
        var $val_qte   = parseFloat($val_qte) ? parseFloat($val_qte) : 0;

/*      var $val_qte_bl= $('#qte_bl_'+$id).val();
        var $val_qte_bl= parseFloat($val_qte_bl) ? parseFloat($val_qte_bl) : 0;*/

        var $val_liv   = $('#liv_'+$id).val();
        var $val_liv   = parseFloat($val_liv) ? parseFloat($val_liv) : 0;

        if($val_liv > $val_stock)
        {
        	ajax_loadmessage('La quantité à livrer n\'est pas disponible dans le stock: '+ $val_stock,'nok');
        	$('#liv_'+$id).val($val_stock)
    		return false;
        }
       if($val_liv > $val_qte)
        {
        	ajax_loadmessage('La quantité à livrer ne doit pas dépasser la quantité commandée: '+ $val_qte,'nok');
            $('#liv_'+$id).val($val_qte)
            return false;
        }
        
 });

});
</script>