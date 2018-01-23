<?php 
//Get all proforma info 
 $info_proforma_d = new Mproforma();
//Set ID of Module with POST id
 $info_proforma_d->id_proforma_d = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_proforma_d->get_proforma_d())
 {  
    // returne message error red to client 
    exit('3#'.$info_proforma_d->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
$form = new Mform('edit_detailproforma', 'edit_detailproforma', '', 'proforma', '0', 'is_modal');
//token main form
$form->input_hidden('id', $info_proforma_d->h('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Check tkn_frm
$form->input_hidden('checker_tkn_frm',  MInit::cryptage($info_proforma_d->h('tkn_frm'), 1));
$form->input_hidden('tkn_frm', $info_proforma_d->h('tkn_frm'));
$form->input_hidden('tva_d', 'O');
//commission commercial
$form->input_hidden('commission', Mreq::tp('commission'));
//prix unitaire sans commission
$form->input_hidden('pu', $info_proforma_d->h('prix_unitaire'));

//Produit
$produit_array[]  = array('required', 'true', 'Choisir un Produit / Service');
$form->select_table('Produit / Service', 'id_produit', 8, 'produits', 'id', 'designation' , 'designation', $indx = '------', $info_proforma_d->h('id_produit'),$multi=NULL, $where=NULL, $produit_array);

$prix_affich=$info_proforma_d->h('prix_unitaire') + ($info_proforma_d->h('prix_unitaire') *  Mreq::tp('commission') / 100);

$hard_code_pri_u_ht = '<label style="margin-left:15px;margin-right : 20px;">Prix Unité HT: </label><input id="prix_unitaire" name="prix_unitaire" class="input-large alignRight" type="text" readonly="" value="'.$prix_affich.'">';
$hard_code_pri_u_ht .= '<span class="help-block returned_span">...</span>';
//Réference
$form->input('Réference', 'ref_produit', 'text' ,3, $info_proforma_d->h('ref_produit'), Null, $hard_code_pri_u_ht, 1);
//Remise
$hard_code_remis = '<label style="margin-left:15px;margin-right : 20px;">Valeur remise: </label><input id="remise_valeur_d" name="remise_valeur_d" class="input-large alignRight" value="'.$info_proforma_d->h('remise_valeur').'" type="text">';
//Type Remise
//select($input_desc, $input_id, $input_class, $options, $indx = NULL ,$selected = NULL, $multi = NULL, $hard_code = null )
$typ_remise = array('P' => 'Pourcentage' , 'M' => 'Montant' );
$form->select('Nature remise', 'type_remise_d', 3, $typ_remise, $indx = NULL , 'P', $multi = NULL,  $hard_code_remis );
//Quantité
$hard_code_pri_tt_ht = '<label style="margin-left:15px;margin-right : 20px;">Prix total HT: </label><input id="total_ht" name="total_ht" value="'.$info_proforma_d->h('total_ht').'" class="input-large alignRight" type="text" readonly="">';
$hard_code_tva = '<label style="margin-left:15px;margin-right : 20px;">total TVA: </label><input readonly="" id="total_tva" name="total_tva" class="input-small alignRight" value="'.$info_proforma_d->h('total_tva').'" type="text">';
$qte_array[]  = array('required', 'true', 'Insérez une Quantité' );
$qte_array[]  = array('minlength', '1', 'Minimum 1 caractères' );
$form->input('Quantité', 'qte', 'text' ,'1 is-number alignRight', $info_proforma_d->h('qte'), $qte_array, $hard_code_pri_tt_ht.$hard_code_tva);
//Prix 
$prix_array[]  = array('required', 'true', 'Insérez le Prix' );
$prix_array[]  = array('number', 'true', 'Montant invalid' );
$prix_array[]  = array('required', 'true', 'Insérer le prix' );


$form->input('Prix total TTC', 'total_ttc', 'text' ,'6 is-number alignRight', $info_proforma_d->h('total_ttc'), Null, null, 1);
//Form render
$form->render();

?>
<script type="text/javascript">
//On change produit get all informations.
$(document).ready(function() {
    $('#remise_valeur_d').mask('99.9');
    $('#type_remise_d').change(function(e) {
        if($('#type_remise_d').val() == 'P'){
            $('#remise_valeur_d').mask('99.9');
        }else{
            $('#remise_valeur_d').unmask();
        }
    });
    //Get TVA value from main TVA select 
    $('#tva_d').val($('#tva').val());
    $('#commission_d').val($('#commission').val()); 
     //called when key is pressed in textbox
     function calculat_proforma($prix_u, $qte, $type_remise, $remise_valeur, $tva, $f_total_ht, $f_total_tva, $f_total_ttc)
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
    $('#id_produit').change(function(e) {
        var $id_produit = $(this).val();

        if($id_produit == null){
            return true;
        }
        $.ajax({

            cache: false,
            url  : '?_tsk=add_detailproforma&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$id_produit+'&<?php echo MInit::crypt_tp('exec', 'produit_info') ?>',
            dataType:"JSON",
            success: function(data){
                
                if(data['error']){
                    ajax_loadmessage(data['error'] ,'nok',5000);
                    $('#prix_unitaire').val(0);
                    $('#ref_produit').val(null);
                    $('#prix_unitaire').trigger('change');
                    $('.returned_span').text('...');
                    $('#label_qte').text('Quantité: ');
                    return false;
                }else{
                                                                                                    
                    $('#label_qte').text('Quantité: ('+data['unite_vente']+')');
                    $('#pu').val(data['prix_vente']);
                    $('#prix_unitaire').val(parseFloat(data['prix_vente'])+ ( parseFloat(data['prix_vente']) * parseFloat($('#commission').val()) / 100 ));
                    $('#ref_produit').val(data['reference']);
                    $('.returned_span').remove();
                    if(data['prix_vendu'] == 0){
                     $('#ref_produit').parent('div').after('<span class="help-block returned_span">Ce produit n\' pas été vendu avant!</span>'); 
                    }else{
                        $('#ref_produit').parent('div').after('<span class="help-block returned_span">Ce produit étais vendu à :'+data['prix_vendu']+'</span>');
                    }
                    $('#prix_unitaire').trigger('change');
                                        
                }
            }//end success
        });

        var validator = $('#edit_detailproforma').validate();//1 voir avec kada
        validator.resetForm();

    });
    $('#qte, #prix_unitaire, #remise_valeur_d, #type_remise_d').bind('input change',function() {
        var prix_unitaire = parseInt($('#prix_unitaire').val());
        var qte           = parseFloat($('#qte').val());
        var type_remise   = $('#type_remise_d').val();
        var remise_valeur = parseFloat($('#remise_valeur_d').val());
        var tva           = $('#tva').val();

        calculat_proforma(prix_unitaire, qte, type_remise, remise_valeur, tva, 'total_ht', 'total_tva', 'total_ttc');
    });
    $('.send_modal').on('click', function () {
        if(!$('#edit_detailproforma').valid())
        {
            e.preventDefault();
        }else{
            $.ajax({
                cache: false,
                url  : '?_tsk=edit_detailproforma&ajax=1',
                type : 'POST',
                data : $('#edit_detailproforma').serialize(),
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