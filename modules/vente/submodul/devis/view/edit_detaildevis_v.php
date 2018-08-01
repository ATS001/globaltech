<?php 
//Get all Devis info 
 $info_devis_d = new Mdevis();
//Set ID of Module with POST id
 $info_devis_d->id_devis_d = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_devis_d->get_devis_d())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_devis_d->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
$form = new Mform('edit_detaildevis', 'edit_detaildevis', '', 'devis', '0', 'is_modal');
//token main form
$form->input_hidden('id', $info_devis_d->h('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Check tkn_frm
$form->input_hidden('checker_tkn_frm',  MInit::cryptage($info_devis_d->h('tkn_frm'), 1));
$form->input_hidden('tkn_frm', $info_devis_d->h('tkn_frm'));
$form->input_hidden('tva_d', 'O');
//commission commercial
$form->input_hidden('commission', Mreq::tp('commission'));
//prix unitaire sans commission
$form->input_hidden('pu', $info_devis_d->h('prix_unitaire'));

//Type produit old

$option_categ_produit = $form->select_option_only('ref_categories_produits', 'id', 'categorie_produit' , 'categorie_produit', $info_devis_d->h('categ_id'), $multi = NULL, ' type_produit = '.$info_devis_d->h('type_id'));




//$option_produits = $form->select_option_only('produits', 'id', 'designation' , 'designation', $info_devis_d->h('id_produit'), $multi = NULL, $where);

$form->input_hidden('type_produit_old', $info_devis_d->h('type_id'));
$hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categ_produit" name="categ_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="'.((6 * 100) / 12).'" >'.$option_categ_produit.'</select>';
$type_produit_array[]  = array('required', 'true', 'Choisir un Type Produit');
$form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'type_produit' , 'type_produit', $indx = '------' ,$selected = $info_devis_d->h('type_id'),$multi=NULL, $where='etat = 1' , $type_produit_array, $hard_code_type_produit);//Produit
//$produit_array[]  = array('required', 'true', 'Choisir un Produit / Service');
//$form->select_table('Produit / Service', 'id_produit', 8, 'produits', 'id', 'designation' , 'designation', $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat = 1' , $produit_array);
//$opt_produit = array($info_devis_d->h('id_produit') => $info_devis_d->h('designation'));
//$form->select('Produit / Service', 'id_produit', 8, $opt_produit, $indx = NULL ,$selected = NULL, $multi = NULL,  null);

$etat_produit_p  = Msetting::get_set('etat_produit', 'produit_valide_p');
$etat_produit_ap = Msetting::get_set('etat_produit', 'produit_valide_ap');
$etat_en_stock   = Msetting::get_set('etat_produit', 'en_stock');
$etat_stock_faible = Msetting::get_set('etat_produit', 'stock_faible');
$etat_stock_epuise = Msetting::get_set('etat_produit', 'stock_epuise');
$where = ' etat in('.$etat_produit_p.','.$etat_produit_ap.','.$etat_en_stock.','.$etat_stock_faible.','.$etat_stock_epuise.') AND idcategorie = '.$info_devis_d->h('categ_id');
$form->select_table('Produit / Service', 'id_produit', 8, 'produits', 'id', 'id' , 'designation', $indx = null ,$selected = $info_devis_d->h('id_produit'),$multi=NULL, $where = $where , null, null);
//Produit
//$produit_array[]  = array('required', 'true', 'Choisir un Produit / Service');
//$form->select_table('Produit / Service', 'id_produit', 8, 'produits', 'id', 'designation' , 'designation', $indx = '------', $info_devis_d->h('id_produit'),$multi=NULL, $where='etat = 1' , $produit_array);
$delta_commission = Mreq::tp('type_commission') == 'C' ? Mreq::tp('commission') : 0;

$prix_affich=$info_devis_d->h('prix_unitaire') + ($info_devis_d->h('prix_unitaire') *  $delta_commission / 100);
//var_dump(Mreq::tp('commission'));
$hard_code_pri_u_ht = '<label style="margin-left:15px;margin-right : 20px;">Prix Unité HT: </label><input id="prix_unitaire" name="prix_unitaire" class="input-large alignRight" type="text" readonly="" value="'.$prix_affich.'">';
$hard_code_pri_u_ht .= '<span class="help-block returned_span">...</span>';
//Réference
$form->input('Réference', 'ref_produit', 'text' ,3, $info_devis_d->h('ref_produit'), Null, $hard_code_pri_u_ht, 1);
//Remise
$hard_code_remis = '<label style="margin-left:15px;margin-right : 20px;">Valeur remise: </label><input id="remise_valeur_d" name="remise_valeur_d" class="input-large alignRight" value="'.$info_devis_d->h('remise_valeur').'" type="text">';
//Type Remise
//select($input_desc, $input_id, $input_class, $options, $indx = NULL ,$selected = NULL, $multi = NULL, $hard_code = null )
$typ_remise = array('P' => 'Pourcentage' , 'M' => 'Montant' );
$form->select('Nature remise', 'type_remise_d', 3, $typ_remise, $indx = NULL , 'P', $multi = NULL,  $hard_code_remis );
//Quantité
$hard_code_pri_tt_ht = '<label style="margin-left:15px;margin-right : 20px;">Prix total HT: </label><input id="total_ht" name="total_ht" value="'.$info_devis_d->h('total_ht').'" class="input-large alignRight" type="text" readonly="">';
$hard_code_tva = '<label style="margin-left:15px;margin-right : 20px;">total TVA: </label><input readonly="" id="total_tva" name="total_tva" class="input-small alignRight" value="'.$info_devis_d->h('total_tva').'" type="text">';
$qte_array[]  = array('required', 'true', 'Insérez une Quantité' );
$qte_array[]  = array('minlength', '1', 'Minimum 1 caractères' );
$form->input('Quantité', 'qte', 'text' ,'1 is-number alignRight', $info_devis_d->h('qte'), $qte_array, $hard_code_pri_tt_ht.$hard_code_tva);
//Prix 
$prix_array[]  = array('required', 'true', 'Insérez le Prix' );
$prix_array[]  = array('number', 'true', 'Montant invalid' );
$prix_array[]  = array('required', 'true', 'Insérer le prix' );


$form->input('Prix total TTC', 'total_ttc', 'text' ,'6 is-number alignRight', $info_devis_d->h('total_ttc'), Null, null, 1);
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
	 function calculat_devis($prix_u, $qte, $type_remise, $remise_valeur, $tva, $f_total_ht, $f_total_tva, $f_total_ttc)
	 {
    	//var $prix_u_remised = $total_ht = $total_ttc = $total_tva = null;
    	var $prix_u         = parseFloat($prix_u) ? parseFloat($prix_u) : 0;
    	var $qte            = parseInt($qte) ? parseInt($qte) : 0;
    	//var $type_remise    = $type_remise == null ? 'P' : $type_remise;
    	var $remise_valeur  = parseFloat($remise_valeur) ? parseFloat($remise_valeur) : 0;
    	var $tva            = $tva == null ? 'O' : $tva;
        var $val_tva = <?php echo Mcfg::get('tva')?>
    	
    	//calculate remise
    	if($type_remise == 'P')
    	{
    		$prix_u_remised = $prix_u - ($prix_u * $remise_valeur) / 100;

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
    $('#type_produit').change(function(e) {
        var $type_produit = $(this).val();

        if($type_produit == null){
            return true;
        }
        $('#categ_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        
        $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        $('#prix_unitaire').val('0').trigger('change');
        $('.show_info_product').text('...');
        $('#ref_produit').val('');
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
        $('.show_info_product').text('...');
        $('#ref_produit').val('');
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
                }else{
                                        
                    var table = $('#table_details_devis').DataTable();
                    var $abn = data['abn'] == true ? 'abn' : '';
                    var $typ_old = $('#type_produit_old').val();
                    var $typ_new = $('#type_produit').val();
                    if (table.data().count()) {

                        if(data['abn'] == true && $typ_old != $typ_new ){
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
                     $('#ref_produit').parent('div').after('<span class="show_info_product help-block returned_span">Ce produit n\' pas été vendu avant!</span>'); 
                    }else{
                        $('#ref_produit').parent('div').after('<span class="show_info_product help-block returned_span">Ce produit a été vendu à :'+data['prix_vendu']+' '+data['qte_dispo']+'</span>');
                    }
                    $('#prix_unitaire').trigger('change');
                    //check if have already rox in table stop if produit is Abonnement
                    $('#is_abn').remove();
                    $('#addRow').after('<input id="is_abn" type="hidden" value="'+$abn+'"/>');

                    
                }
            }//end success
        });

        var validator = $('#edit_detaildevis').validate();
        validator.resetForm();

    });
    $('#qte, #prix_unitaire, #remise_valeur_d, #type_remise_d').bind('input change',function() {
    	var prix_unitaire = parseInt($('#prix_unitaire').val());
    	var qte           = parseFloat($('#qte').val());
    	var type_remise   = $('#type_remise_d').val();
    	var remise_valeur = parseFloat($('#remise_valeur_d').val());
    	var tva           = $('#tva').val();

    	calculat_devis(prix_unitaire, qte, type_remise, remise_valeur, tva, 'total_ht', 'total_tva', 'total_ttc');
    });
    $('.send_modal').on('click', function () {
        if(!$('#edit_detaildevis').valid())
        {
            e.preventDefault();
        }else{
            $.ajax({
                cache: false,
                url  : '?_tsk=edit_detaildevis&ajax=1',
                type : 'POST',
                data : $('#edit_detaildevis').serialize(),
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