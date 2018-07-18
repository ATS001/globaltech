<?php 
$form = new Mform('add_detaildevis', 'add_detaildevis', '', 'devis', '0', 'is_modal');
//token main form
$form->input_hidden('tkn_frm', Mreq::tp('tkn'));
$form->input_hidden('tva_d', 'O');

$form->input_hidden('commission', Mreq::tp('commission'));
$form->input_hidden('pu', 0);
//var_dump(Mreq::tp('commission'));

//Type Produit

$hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categ_produit" name="categ_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="'.((6 * 100) / 12).'" ><option >----</option></select>';
$type_produit_array[]  = array('required', 'true', 'Choisir un Type Produit');
$form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'type_produit' , 'type_produit', $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat = 1' , $type_produit_array, $hard_code_type_produit);
//Produit
//$produit_array[]  = array('required', 'true', 'Choisir un Produit / Service');
//$form->select_table('Produit / Service', 'id_produit', 8, 'produits', 'id', 'designation' , 'designation', $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat = 1' , $produit_array);
$opt_produit = array('' => '------');
$form->select('Produit / Service', 'id_produit', 8, $opt_produit, $indx = NULL ,$selected = NULL, $multi = NULL,  null);
$hard_code_pri_u_ht = '<label style="margin-left:15px;margin-right : 20px;">Prix Unité HT: </label><input id="prix_unitaire" name="prix_unitaire" value="0" class="input-large alignRight" readonly="" type="text">';
$hard_code_pri_u_ht .= '<span class="help-block returned_span">...</span>';
//Réference
$form->input('Réference', 'ref_produit', 'text' ,3, null, Null, $hard_code_pri_u_ht, 1);
//Remise
$hard_code_remis = '<label style="margin-left:15px;margin-right : 20px;">Valeur remise: </label><input id="remise_valeur_d" name="remise_valeur_d" class="input-large alignRight numeric" value="0" type="text">';
//Type Remise
//select($input_desc, $input_id, $input_class, $options, $indx = NULL ,$selected = NULL, $multi = NULL, $hard_code = null )
$typ_remise = array('P' => 'Pourcentage' , 'M' => 'Montant' );
$form->select('Nature remise', 'type_remise_d', 3, $typ_remise, $indx = NULL ,$selected = NULL, $multi = NULL,  $hard_code_remis );
//Quantité
$hard_code_pri_tt_ht = '<label style="margin-left:15px;margin-right : 20px;">Prix total HT: </label><input id="total_ht" name="total_ht" value="0" class="input-large alignRight" type="text" readonly="">';
$hard_code_tva = '<label style="margin-left:15px;margin-right : 20px;">total TVA: </label><input readonly="" id="total_tva" name="total_tva" class="input-small alignRight" value="0" type="text">';
$qte_array[]  = array('required', 'true', 'Insérez une Quantité' );
$qte_array[]  = array('minlength', '1', 'Minimum 1 caractères' );
$form->input('Quantité', 'qte', 'text' ,'1 is-number alignRight', '1', $qte_array, $hard_code_pri_tt_ht.$hard_code_tva);
//Prix 
$prix_array[]  = array('required', 'true', 'Insérez le Prix' );
$prix_array[]  = array('number', 'true', 'Montant invalid' );
$prix_array[]  = array('required', 'true', 'Insérer le prix' );


$form->input('Prix total TTC', 'total_ttc', 'text' ,'6 is-number alignRight', '0', Null, null, 1);
//Form render
$form->render();

?>
<script type="text/javascript">
//On change produit get all informations.
$(document).ready(function() {
    
    //Get TVA value from main TVA select 
    $('#tva_d').val($('#tva').val()); 
    //Get Commission  
    $('#commission_d').val($('#commission').val()); 
	 //called when key is pressed in textbox
	 function calculat_devis($prix_u, $qte, $type_remise, $remise_valeur, $tva, $f_total_ht, $f_total_tva, $f_total_ttc)//,$commission
	 {
    	//var $prix_u_remised = $total_ht = $total_ttc = $total_tva = null;
    	var $prix_u           = parseFloat($prix_u) ? parseFloat($prix_u) : 0;
    	var $qte              = parseInt($qte) ? parseInt($qte) : 0;
    	//var $type_remise    = $type_remise == null ? 'P' : $type_remise;
    	var $remise_valeur    = parseFloat($remise_valeur) ? parseFloat($remise_valeur) : 0;
    	var $val_tva          = <?php echo Mcfg::get('tva')?>
    	
    	//calculate remise
    	if($type_remise == 'P')
    	{
    		$prix_u_remised = $prix_u - ($prix_u * $remise_valeur) / 100 ;

    	}else if($type_remise == 'M'){
    		var $prix_u_remised = $prix_u - $remise_valeur;
    	}else{
    		var $prix_u_remised = $prix_u;
    	}
        
    	//Total HT 
    	var $total_ht = $prix_u_remised * $qte;
    	//Calculate TVA
    	if($tva == 'N')
    	{
    		var $total_tva = 0;
    	}else{
    		var $total_tva = ($total_ht * $val_tva) / 100; //TVA value get from app setting
    	}
    	var $total_ttc = $total_ht + $total_tva ;
    	$('#'+$f_total_ht).val($total_ht);
    	$('#'+$f_total_tva).val($total_tva);
    	$('#'+$f_total_ttc).val($total_ttc);  
    }
    $('#id_produit').change(function(e) {
    	var $id_produit = $(this).val();

    	if($id_produit == null){
    		return true;
    	}
    	$.ajax({

    		cache: false,
    		url  : '?_tsk=add_detaildevis&ajax=1',
    		type : 'POST',
    		data : '&act=1&id='+$id_produit+'&<?php echo MInit::crypt_tp('exec', 'produit_info') ?>',
    		dataType:"JSON",
    		success: function(data){
    			
    			if(data['error']){
    				ajax_loadmessage(data['error'] ,'nok',5000)
                    $('#prix_unitaire').val(0);
                    $('#ref_produit').val(null);
                    $('#prix_unitaire').trigger('change');
                    $('.returned_span').text('...');
                    $('#label_qte').text('Quantité: ');
                    return false;
    			}else{
                                        
                    var table = $('#table_details_devis').DataTable();
                    var $abn = data['abn'] == true ? 'abn' : '';

                    if (table.data().count()) {
                        if(data['abn'] == true){
                            ajax_loadmessage("Impossible d'insérer un abonnement avec autres produits" ,'nok',5000);
                            return false;
                        } 
                    }
                    $('#label_qte').text('Quantité: ('+data['unite_vente']+')');
                    $('#pu').val(data['prix_vente']);
                    $('#prix_unitaire').val(parseFloat(data['prix_vente'])+ ( parseFloat(data['prix_vente']) * parseFloat($('#commission').val()) / 100 ));
                    $('#ref_produit').val(data['reference']);
                    $('.returned_span').remove();
                    if(data['prix_vendu'] == 0){
                     $('#ref_produit').parent('div').after('<span class="help-block returned_span">Ce produit n\' pas été vendu avant!</span>'); 
                    }else{
                        $('#ref_produit').parent('div').after('<span class="help-block returned_span">Ce produit a été vendu à :'+data['prix_vendu']+' / Qte disponible : '+data['qte_in_stock']+'</span>');
                    }
                    $('#prix_unitaire').trigger('change');
                    //check if have already rox in table stop if produit is Abonnement
                    $('#is_abn').remove();
                    $('#addRow').after('<input id="is_abn" type="hidden" value="'+$abn+'"/>');

                    
                }
            }//end success
        });

    	var validator = $('#add_detaildevis').validate();
    	validator.resetForm();

    });
    $('#type_produit').change(function(e) {
        var $type_produit = $(this).val();

        if($type_produit == null){
            return true;
        }
        $('#categ_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        //$('#categ_produit').trigger('change');
        $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
         $('#prix_unitaire').val('0').trigger('change');
        $.ajax({

            cache: false,
            url  : '?_tsk=add_detaildevis&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$type_produit+'&<?php echo MInit::crypt_tp('exec', 'load_select_categ') ?>',
            dataType:"JSON",
            success: function(data){
               
                if(data['error'] == false){
                    ajax_loadmessage(data['mess'] ,'nok',5000);
                    return false;
                }else{
                    $.each(data, function(key, value) {   
                     $('#categ_produit')
                     .append($("<option></option>")
                         .attr("value",key)
                         .text(value)); 
                    });
                    $('#categ_produit').trigger("chosen:updated");
                   
                }
                
                
            }//end success
        });
    });
    $('#categ_produit').change(function(e) {
        var $categ_produit = $(this).val();

        if($categ_produit == null){
            return true;
        }
        $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        $('#prix_unitaire').val('0').trigger('change');
        $.ajax({

            cache: false,
            url  : '?_tsk=add_detaildevis&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$categ_produit+'&<?php echo MInit::crypt_tp('exec', 'load_select_produit') ?>',
            dataType:"JSON",
            success: function(data){
                if(data['error'] == false){
                    ajax_loadmessage(data['mess'] ,'nok',5000);
                    return false;
                }else{
                    $.each(data, function(key, value) {   
                   $('#id_produit')
                   .append($("<option></option>")
                   .attr("value",key)
                   .text(value)); 
                   });
                   $('#id_produit').trigger("chosen:updated");

                }
                
                
            }//end success
        });

    });
    $('#qte, #prix_unitaire, #remise_valeur_d, #type_remise_d').bind('input change',function() {
    	//var prix_unitaire = parseInt($('#prix_unitaire').val()) + ( parseInt($('#prix_unitaire').val())* parseFloat($('#commission').val() ) /100);
        var prix_unitaire = parseInt($('#prix_unitaire').val());
    	var qte           = parseFloat($('#qte').val());
    	var type_remise   = $('#type_remise_d').val();
    	var remise_valeur = parseFloat($('#remise_valeur_d').val());
    	var tva           = $('#tva').val();
        var commission    = parseFloat($('#commission').val());

    	calculat_devis(prix_unitaire, qte, type_remise, remise_valeur, tva, 'total_ht', 'total_tva', 'total_ttc');//,commission
    });
    $('.send_modal').on('click', function () {
        if(!$('#add_detaildevis').valid())
        {
            e.preventDefault();
        }else{
            $.ajax({
                cache: false,
                url  : '?_tsk=add_detaildevis&ajax=1',
                type : 'POST',
                data : $('#add_detaildevis').serialize(),
                dataType:"html",
                success: function(data_f)
                {

                    var data_arry = data_f.split("#");
                    if(data_arry[0]==0){
                        ajax_loadmessage(data_arry[1],'nok',3000);
                    }else{ 

                        ajax_loadmessage(data_arry[1],'ok',3000);
                        var t1 = $('.dataTable').DataTable().draw();
                        $('.close_modal').trigger('click');
                        $('#sum_table').val(data_arry[2]);
                        $('#valeur_remise').trigger('change');
                        
                    }
                },
                timeout: 30000,
                error: function(){
                    ajax_loadmessage('Délai non attendue','nok',5000)

                }
            });

        }

    });
});	

</script>