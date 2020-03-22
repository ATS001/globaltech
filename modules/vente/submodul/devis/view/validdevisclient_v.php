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


//Date devis
$array_date[]= array('required', 'true', 'Insérer la date de devis');
$form->input_date('Date Validation', 'date_valid_client', 2, date('d-m-Y'), $array_date);

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
//Tableau Qte
if($info_devis->g('type_devis') == 'VNT'){
	$form->extra_html('table_product', $info_devis->Gettable_detail_product_livraison());
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
    $('#reponse').on('change', function () {
    	$val_reponse = $(this).val();
    	if($val_reponse === 'valid')
    	{
    		$('#table_product').show();
    		$('#modcom').closest('.form-group').show();
    		$('#scan').closest('.form-group').show();
    	}else{
    		$('#table_product').hide();
    		$('#modcom').closest('.form-group').hide();
    		$('#scan').closest('.form-group').hide();
    	}
    });
    $('.appro_stock').on('click', function(event) {
        
        
        
        var $link  = 'validdevisclient';
        var $titre = 'Ajouter produit au stock'; 
        var $data  = '&act=1&id_produit='+$(this).attr('data')+'&qte_exist='+$('#stok_'+$(this).attr('data')).text()+'&need='+$('#liv_'+$(this).attr('data')).val(); 
       
        ajax_bbox_loader($link, $data, $titre, 'large')
        
    
        /* Act on the event */
    });

<?php
	if($info_devis->g('type_devis') == 'VNT'){
?>
    $('.liv').bind('input change',function() {
        var $qte_id = $(this).attr('id');
        var $id = $qte_id.substr($qte_id.lastIndexOf("_")+1);
        var $val_stock = $('#stok_'+$id).text();
        var $val_stock = parseFloat($val_stock) ? parseFloat($val_stock) : 0;
        var $val_qte   = $('#qte_'+$id).text();
        var $val_qte   = parseFloat($val_qte) ? parseFloat($val_qte) : 0;
        var $val_liv   = $('#liv_'+$id).val();
        var $val_liv   = parseFloat($val_liv) ? parseFloat($val_liv) : 0;
        var $typ_prod  = $('#qte_'+$id).attr('tp');
        
        
        if($val_liv > $val_stock && $typ_prod == 1 )
        {
            ajax_loadmessage('La quantité à livrer n\'est pas disponible dans le stock','nok');
            $('#liv_'+$id).val($val_stock)
            return false;
        }
        if($val_liv > $val_qte)
        {
            ajax_loadmessage('La quantité à livrer ne doit pas dépasser la quantité commandée','nok');
            $('#liv_'+$id).val($val_qte )
            return false;
        }
        

        
        
    });

<?php } ?>
});
</script>	