<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 14-05-2018
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
$form = new Mform('devislivraison', 'devislivraison', '', 'devis', '0', null);
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
if($info_devis->g('type_devis') == 'VNT'){
	$form->extra_html('table_product', $info_devis->Gettable_detail_product_add_livraison());
}

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
    
<?php
	if($info_devis->g('type_devis') == 'VNT'){
?>
    $('.liv').bind('input change',function() {
		var $qte_id            = $(this).attr('id');
        var $id                = $qte_id.substr($qte_id.lastIndexOf("_")+1);
        var $val_stock         = $('#stok_'+$id).text();
        var $val_stock         = parseFloat($val_stock) ? parseFloat($val_stock) : 0;
        var $val_qte           = $('#qte_'+$id).text();
        var $val_qte           = parseFloat($val_qte) ? parseFloat($val_qte) : 0;
        var $val_qte_dej_liv   = $('#qte_dej_liv_'+$id).text();
        var $val_qte_dej_liv   = parseFloat($val_qte_dej_liv) ? parseFloat($val_qte_dej_liv) : 0;
        var $val_rest_a_liv        = $val_qte - $val_qte_dej_liv;
        var $val_liv           = $('#liv_'+$id).val();
        var $val_liv           = parseFloat($val_liv) ? parseFloat($val_liv) : 0;
        if($val_liv > $val_stock)
        {
        	ajax_loadmessage('La quantité à livrer n\'est pas disponible dans le stock','nok');
        	$('#liv_'+$id).val($val_stock)
    		return false;
        }
        if($val_liv > $val_rest_a_liv)
        {
            ajax_loadmessage('La quantité à livrer ne doit pas dépasser la quantité restée à livrer','nok');
            $('#liv_'+$id).val($val_rest_a_liv )
            return false;
        }
        
    });

<?php } ?>
});
</script>	

		