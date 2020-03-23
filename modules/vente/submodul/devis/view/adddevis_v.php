<?php
//chck if called with client ID then suggest task for after exec
//id_clnt crypted => id client
//tsk_aft crypted => Task after exec
//
$after_exec     = 'devis';
$id_clnt        = MReq::tp('id_clnt');
$tsk_aft        = MReq::tp('tsk_aft');
$name_client    = null;
$title          = 'Ajouter un Devis';
$btn_return_txt = 'Liste des Devis';
$btn_task       = 'devis';
$btn_setting    = null;

if($id_clnt != null && $tsk_aft != null){
    if(!MInit::crypt_tp('id_clnt', null, 'D')){
          Minit::big_message('ID client n\'est pas correcte', 'danger');
          die();
    }
    if(!MInit::crypt_tp('tsk_aft', null, 'D')){
          Minit::big_message('Erreur Système #aft_exec', 'danger');
          die();
    }
    $after_exec = $tsk_aft.'&'.MInit::crypt_tp('id', $id_clnt);
    $name_client = Mdevis::get_client_name($id_clnt);
    $title .=  ' pour le client :'.$name_client;
    $btn_return_txt = 'Détail client '.$name_client;
    $btn_task = $tsk_aft;
    $btn_setting = MInit::crypt_tp('id', $id_clnt);
}

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add($btn_task, $btn_return_txt, $btn_setting, $exec = NULL, 'reply'); ?>

	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo $title; ?>
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
$tva  = Mcfg::get('tva');
$form = new Mform('adddevis', 'adddevis', '', $after_exec, '0', null);
//$form->input_hidden('commission', Mreq::tp('commission'));
$plafond_remise = session::get('service') == 7 ? Msetting::get_set('plafond_remise_commercial') : 10;
$form->input_hidden('remise_plafond', $plafond_remise);
$form->input_hidden('old_client', null);

//Date devis
$array_date[]= array('required', 'true', 'Insérer la date de devis');
$form->input_date('Date devis', 'date_devis', 2, date('d-m-Y'), $array_date);

//Client
$hard_code_client = '<a id="add_client_diver" href="#" rel="add_client_diver" data="" data_titre="Ajout Client Diver " class=" "><span class="help-block returned_span"><i class="fa fa-plus"></i> Ajouter un client divers</span></a>';
$client_devise = '<span class="help-block returned_client"><i class="fa fa-money"></i> Devise </span>';
$where_id_client = null;
//if client is set no temp clien button
if($id_clnt != null){
    $client_d = new MClients();
    $client_d->id_client = $id_clnt;
    $client_d->get_client();
    $devise=$client_d->g('devise');
    $id_devise_c=$client_d->g('id_devise');

    $hard_code_client = null;
    $where_id_client = ' AND id = '.$id_clnt;

    $client_devise = '<span class="help-block returned_client"><i class="fa fa-money"></i> Ce devis sera créé en: '.$devise.' </span>';
}
$client_array[]  = array('required', 'true', 'Choisir un Client');
//Il faut vérifier si le client est préenvoyé

$form->select_table('Client', 'id_client', 6, 'clients', 'id', 'denomination' , 'denomination', $indx = '------' ,$selected = $id_clnt,$multi=NULL, $where='(etat=1 or type_client=\'T\' )'.$where_id_client, $client_array, $hard_code_client.$client_devise);
//TVA
$tva_opt = array('O' => 'OUI' , 'N' => 'NON' );
$form->select('Soumis à TVA', 'tva', 2, $tva_opt, $indx = NULL ,$selected = NULL, $multi = NULL);
//Projet if client have more project
$form->input('Projet', 'projet', 'text' ,'6', NULL, null, null, null);

//Commercial
//$hard_code_commercial = '<span class="help-block returned_span">...</span>';
$commercial_array[]  = array('required', 'true', 'Choisir un Commercial');
//$form->select_table('Commercial', 'id_commercial', 6, 'commerciaux', 'id', 'CONCAT(nom," ",prenom)' , 'CONCAT(nom," ",prenom)' , $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat=1  AND is_glbt = \'Oui\' ', $commercial_array, null);
$form->select_table('Commercial', 'id_commercial[]', 8, 'commerciaux', 'id', 'CONCAT(nom," ",prenom)','CONCAT(nom," ",prenom)', $indx = NULL ,null , 1, $where='etat=1 AND is_glbt = \'Oui\' ', NULL);

//Commission du commercial
/*$hard_code_commission  = '<label style="margin-left:15px;margin-right : 20px;">Prise en charge par: </label><select id="type_commission" name="type_commission" class="chosen-select col-xs-12 col-sm-3" chosen-class="'.((3 * 100) / 12).'" ><option value="C" >Client</option><option value="S" >Société</option></select>';
$array_commission[]= array('required', 'true', 'Insérer la commission du commercial');
$array_commission[]= array('number', 'true', 'Montant invalid' );
$form->input('Commission du commercial (%)', 'commission', 'text' ,'2 is-number alignRight','0', $array_commission, $hard_code_commission, null);*/
//Commercial externe
//$hard_code_commercial = '<span class="help-block returned_span">...</span>';
//$commercial_array[]  = array('required', 'true', 'Choisir un Commercial externe');
$form->select_table('Commercial externe', 'id_commercial_ex', 6, 'commerciaux', 'id', 'CONCAT(nom," ",prenom)' , 'CONCAT(nom," ",prenom)' , $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat=1 AND is_glbt = \'Non\' ', $commercial_array, null);
//Commission du commercial
$hard_code_commission_ex  = '<label style="margin-left:15px;margin-right : 20px;">Prise en charge par: </label><select id="type_commission_ex" name="type_commission_ex" class="chosen-select col-xs-12 col-sm-3" chosen-class="'.((3 * 100) / 12).'" ><option value="C" >Client</option><option value="S" >Société</option></select>';
//$array_commission[]= array('required', 'true', 'Insérer la commission du commercial');
$array_commission_ex[]= array('number', 'true', 'Montant invalid' );
$form->input('Commission du commercial externe (%)', 'commission_ex', 'text' ,'2 is-number alignRight','0', $array_commission_ex, $hard_code_commission_ex, null);


//Table
$columns = array('id' => '1' ,'Item' => '3' , 'Réference'=>'14', 'Produit' => '30', 'P.U HT' => '10', 'T.Rem' => '5', 'V.Remise' => '5', 'Qte' => '5', 'Total HT' => '10', 'TVA' => '7', 'Total' =>'10', '#' =>'3'   );
$js_addfunct = 'var column = t.column(0);
     column.visible( ! column.visible() );';
/*$ssid = 'f_v'.$this->_id_form;
  $verif_value  = md5(session::get($ssid));*/
$verif_value = md5(session::get('f_vadddevis'));
$form->draw_datatabe_form('table_details_devis', $verif_value, $columns, 'adddevis', 'add_detaildevis', 'Ajouter détails Devis', $js_addfunct);
//Finance bloc
$form->bloc_title('Zone totaux');
//Type Remise
$form->input('Total des articles enregistrés', 'sum_table', 'text' ,'4 is-number alignRight', '0', null, null, 'readonly');
$hard_code_remise = '<label style="margin-left:15px;margin-right : 20px;">Valeur remise: </label><input id="valeur_remise" name="valeur_remise" class="input-small alignRight" value="0" type="text">';
//$hard_code_remise .= '<label style="margin-left:30px;margin-right : 20px;">Commission   :</label><input readonly="" id="total_commission" name="total_commission" class="input-large is-number alignRight" value="0" type="text"><span class="help-block">Cette remise sera appliquée sur le total H.T de devis</span>';
$hard_code_remise .= '<span class="help-block">Cette remise sera appliquée sur le total H.T de devis</span>';
$typ_remise = array('P' => 'Pourcentage' , 'M' => 'Montant' );
$form->select('Remise Exept', 'type_remise', 3, $typ_remise, $indx = NULL ,$selected = NULL, $multi = NULL,  $hard_code_remise);

//Prix
$prixht_array[]  = array('required', 'true', 'Le montant est invalide');
$hard_code_prices = '<label style="margin-left:15px;margin-right : 20px;"> T.V.A    Calculée     : </label><input id="totaltva" readonly="" name="totaltva" class="input-small is-number alignRight " value="0" type="text">';
$hard_code_prices .= '<label style="margin-left:15px;margin-right : 20px;">Prix Global TTC: </label><input readonly="" id="totalttc" name="totalttc" class="input-large is-number alignRight" value="0" type="text">';
$form->input('Prix Global HT', 'totalht', 'text' ,'3 is-number alignRight', '0', $prixht_array, $hard_code_prices, 'readonly');
//Validité
$vie_opt = array('30' => '1 Mois' , '60' => '2 Mois', '90' => '3 Mois', '180' => '6 Mois', '365' => '12 Mois');
$form->select('Validité', 'vie', 3, $vie_opt, $indx = '-----' ,30 , $multi = NULL);
//Conditions commercial
$clauses = 'Paiement 100% à la commande';
$form->input_editor('Conditions commerciales', 'claus_comercial', 8, $clauses, $js_array = null,  $input_height = 50);
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


    //called when key is pressed in textbox
	 function calculat_devis($totalht, $type_remise, $remise_valeur, $tva, $f_total_ht, $f_total_tva, $f_total_ttc,$commission,$f_total_commission)
	 {

    	var $totalht         = parseFloat($totalht) ? parseFloat($totalht) : 0;
    	//var $type_remise    = $type_remise == null ? 'P' : $type_remise;
    	var $remise_valeur  = parseFloat($remise_valeur) ? parseFloat($remise_valeur) : 0;
    	var $tva            = $tva == null ? 'O' : $tva;
    	var $val_tva = <?php echo Mcfg::get('tva')?>
    	//calculate remise
    	if($type_remise == 'P')
    	{
    		$totalht_remised = $totalht - ($totalht * $remise_valeur) / 100;

    	}else if($type_remise == 'M'){
    		var $totalht_remised = $totalht - $remise_valeur;

    	}else{
    		var $totalht_remised = $totalht;
    	}
    	//Total HT
    	var $total_ht = $totalht_remised;
    	//Calculate TVA
    	if($tva == 'N')
    	{
    		var $total_tva = 0;
    	}else{
    		var $total_tva = ($total_ht * $val_tva) / 100; //TVA value get from app setting
    	}
    	var $total_ttc = $total_ht + $total_tva ;
        var $total_commission = ($total_ttc * $commission) / 100;
        $('#'+$f_total_ht).val(Math.round($total_ht));
        $('#'+$f_total_tva).val(Math.round($total_tva));
        $('#'+$f_total_ttc).val(Math.round($total_ttc));  
        //$('#'+$f_total_commission).val(Math.round($total_commission));  
    }

    $('#addRow').on( 'click', function () {
        $cms = parseFloat($('#commission').val());

        var table = $('#table_details_devis').DataTable();

    	if($('#id_client').val() == ''){

    		ajax_loadmessage('Il faut choisir un client','nok');
    		return false;
    	}

        if($('#id_commercial').val() == ''){

            ajax_loadmessage('Il faut choisir un commercial','nok');
            return false;
        }

        if($('#commission').val() == ''){

            ajax_loadmessage('Il faut saisir une commission','nok');
            return false;
        }

        if(table.data().count() && $('#is_abn').val() == 'abn'){
            ajax_loadmessage("Impossible d'insérer un abonnement avec autres produits",'nok');
            return false;
        }

        var $link  = $(this).attr('rel');
   		var $titre = $(this).attr('data_titre');
   		var $data  = $(this).attr('data')+'&commission='+$('#commission').val()+'&id_commercial='+$('#id_commercial').val()+'&id_client='+$('#id_client').val();
        ajax_bbox_loader($link, $data, $titre, 'large')

    });



    $('#valeur_remise').bind('input change',function() {
    	// Calcul values
        var totalht                 = parseInt($('#sum_table').val());
        var type_remise             = $('#type_remise').val();
        var remise_valeur           = parseFloat($('#valeur_remise').val());
        var tva                     = $('#tva').val();
        var commission              = parseFloat($("#commission").val());
        calculat_devis(totalht, type_remise, remise_valeur, tva, 'totalht', 'totaltva', 'totalttc',commission,'total_commission');

    });
/*
    $('#id_commercial').on('change', function () {
        var $id_commercial = $(this).val();

        if($id_commercial == null){
            return true;
        }
        $.ajax({

            cache: false,
            url  : '?_tsk=add_detaildevis&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$id_commercial+'&<?php // echo MInit::crypt_tp('exec', 'info_commercial') ?>',
            dataType:"JSON",
            success: function(data){

                if(data['error']== false){
                    ajax_loadmessage(data['mess'],'nok',5000)
                }else{
                    $('#remise_plafond').val(data['remise']);
                    $('#remise_dg_plafond').val(data['remise_dg']);
                }

            }
        });
    });

*/

    $('#tva').on('change', function () {
        var table = $('#table_details_devis').DataTable();
        var $tva_option;

        if (table.data().count()) {

            bootbox.confirm("<span class='text-warning bigger-110 orange'>Le changement de TVA sera appliqué sur l'ensemble des lignes détails, voulez vous continuer ?</span>",
                function(result){
                    if(result == true){
                        var $tkn_frm = $(this).attr('tkn_frm');
                        $.ajax({

                            cache: false,
                            url  : '?_tsk=add_detaildevis&ajax=1'+'&act=1&<?php echo MInit::crypt_tp('exec', 'set_tva')?>',
                            type : 'POST',
                            data : $('#adddevis').serialize(),
                            dataType:"JSON",
                            success: function(data){

                                if(data['error']== false){
                                    ajax_loadmessage(data['mess'],'nok',5000)
                                }else{
                                    ajax_loadmessage(data['mess'],'ok',3000);
                                    var t1 = $('.dataTable').DataTable().draw();
                                    $('#sum_table').val(data['sum']);
                                    $('#valeur_remise').trigger('change');
                                }

                            }
                        });
                    }else{
                        var $totaltva = parseFloat($('#totaltva').val());
                        if( $totaltva == 0){
                           $('#tva').val('N');
                        }else{
                           $('#tva').val('O');
                        }
                        $('#tva').trigger("chosen:updated");
                    }
                }
            );
        }

    });



    $('#commission').focusin( function () {
        $(this).data('exist_val_commission', $(this).val());

    });

    $('#commission').bind('input change', function () {
        var $exist_value_commission = $(this).data('exist_val_commission');
        $cms = parseFloat($('#commission').val());

        $set_commision = parseFloat(<?php echo Msetting::get_set('plafond_comission') ?>);

        if($cms > $set_commision){


            ajax_loadmessage('La commission ne doit pas dépasser '+$set_commision,'nok',5000);

            $('#commission').val($exist_value_commission);

            return false;

        }

    });



    $('#commission').on('focusout', function () {
         var $exist_value_commission = $(this).data('exist_val_commission');
        //Get previous data

        //var $exist_type_commission = $('#type_commission').data('exist_type_commission');

        //First check if PEC commission by US return true
        if($('#type_commission').val() == 'S' && $(this).val() == 0){
            return true;
        }
        $cms = parseFloat($('#commission').val());
        $set_commision = parseFloat(<?php echo Msetting::get_set('plafond_comission') ?>);

        if($cms > $set_commision){


            //ajax_loadmessage('La commission ne doit pas dépasser '+$set_commision,'nok',5000);

            $('#commission').val($exist_value_commission);
            return false;

        }
        var table = $('#table_details_devis').DataTable();

        if (table.data().count() &&  this.value !== $exist_value_commission) {

            bootbox.confirm("<span class='text-warning bigger-110 orange'>Le changement de la commission sera appliqué sur l'ensemble des lignes détails, voulez vous continuer ?</span>",
                function(result){
                    if(result == true){
                        $cms = parseFloat($('#commission').val());
                        var $tkn_frm = $(this).attr('tkn_frm');
                        $.ajax({

                            cache: false,
                            url  : '?_tsk=add_detaildevis&ajax=1'+'&act=1&<?php echo MInit::crypt_tp('exec', 'set_commission')?>',
                            type : 'POST',
                            data : $('#adddevis').serialize(),
                            dataType:"JSON",
                            success: function(data){

                                if(data['error']== false){
                                    ajax_loadmessage(data['mess'],'nok',5000)
                                }else{
                                    ajax_loadmessage(data['mess'],'ok',3000);
                                    var t1 = $('.dataTable').DataTable().draw();
                                    $('#sum_table').val(data['sum']);
                                    $('#valeur_remise').trigger('change');
                                }

                            }
                        });
                    }else{

                        $('#commission').val($exist_value_commission);


                    }
                }
            );
        }

    });

    $('#type_commission').on('change', function () {

        //Get previous data
        if($(this).val() == 'C'){
            var $exist_type_commission = 'S'
        }else{
            var $exist_type_commission = 'C'
        }


        //First check if PEC commission by US return true
        if($('#type_commission').val() == 'S' && $(this).val() == 0){
            return true;
        }
        var table = $('#table_details_devis').DataTable();
        $cms = parseFloat($('#commission').val());

        $set_commision = parseFloat(<?php echo Msetting::get_set('plafond_comission') ?>);

        if($cms > $set_commision){

            ajax_loadmessage('La commission ne doit pas dépasser '+$set_commision,'nok',5000);
            $('#commission').val(0);
            return false;

        }
        if (table.data().count()) {

            bootbox.confirm("<span class='text-warning bigger-110 orange'>Le changement de la commission sera appliqué sur l'ensemble des lignes détails, voulez vous continuer ?</span>",
                function(result){
                    if(result == true){
                        $cms = parseFloat($('#commission').val());
                        var $tkn_frm = $(this).attr('tkn_frm');
                        $.ajax({

                            cache: false,
                            url  : '?_tsk=add_detaildevis&ajax=1'+'&act=1&<?php echo MInit::crypt_tp('exec', 'set_commission')?>',
                            type : 'POST',
                            data : $('#adddevis').serialize(),
                            dataType:"JSON",
                            success: function(data){

                                if(data['error']== false){
                                    ajax_loadmessage(data['mess'],'nok',5000)
                                }else{
                                    ajax_loadmessage(data['mess'],'ok',3000);
                                    var t1 = $('.dataTable').DataTable().draw();
                                    $('#sum_table').val(data['sum']);
                                    $('#valeur_remise').trigger('change');
                                }

                            }
                        });
                    }else{


                            $('#type_commission').val($exist_type_commission);
                            $('#type_commission').trigger("chosen:updated");
                            //$('#type_commission').trigger("change");



                        //$("div.id_100 select").val($exist_type_commission);

                    }
                }
            );
        }

    });

    /*$('#type_commission').on('change', function () {

        var $exist_type_commission = $(this).data('exist_type_commission');
        if($(this).val() != $exist_type_commission ){
            $('#commission').trigger('focusout');
        }

    });*/

    $('#type_remise').on('change', function () {
        $('#valeur_remise').trigger('input');
    });

    $('#id_client').on('focus', function () {
        // Store the old client on focus 
        $old_client = $(this.val());
    });

    var old_client = $("#id_client");
    old_client.data("prev",old_client.val());

    $('#id_client').on('input change', function () {
                
        var $id_client   = $(this).val();        
        var table = $('#table_details_devis').DataTable();

        var old_id_client = $(this);
        //alert(old_id_client.data("prev"));
        $('#old_client').val(old_id_client.data("prev"));
        old_id_client.data("prev",old_id_client.val());

        var previousClient = $("#old_client").val();
        //alert(" PreviousValue : " + previousClient);

        $.ajax({

            cache: false,
            url  : '?_tsk=add_detaildevis&ajax=1',
            type : 'POST',
            data : '&act=1&<?php echo MInit::crypt_tp('exec', 'info_client') ?>&id='+$id_client,
            dataType:"JSON",
            success: function(data){
                if($('#id_client').val() == ''){
                    //$('#id_client option:selected').text() == '------'){
                    $('.returned_client').remove(); 
                    $('#id_client').parent('div').after('<span class="show_info_client help-block returned_client"><i class="fa fa-money"></i> Devise </span>');
                }else{
                    $('.returned_client').remove(); 
                    $('#id_client').parent('div').after('<span class="show_info_client help-block returned_client"><i class="fa fa-money"></i>Ce devis sera créé en: '+data['devise']+'</span>');
                }          
                //Prices update  after client change
                if (table.data().count()) {

                        var $tkn_frm = $(this).attr('tkn_frm');
                        $.ajax({

                            cache: false,
                            url  : '?_tsk=add_detaildevis&ajax=1'+'&act=1&<?php echo MInit::crypt_tp('exec', 'prices_update_on_devise_change')?>&id_client='+$id_client+'&old_client='+previousClient+'&edit=0',
                            type : 'POST',
                            data : $('#adddevis').serialize(),
                            dataType:"JSON",
                            success: function(data){

                                if(data['error']== false){
                                    ajax_loadmessage(data['mess'],'nok',5000)
                                }else{
                                    ajax_loadmessage(data['mess'],'ok',3000);
                                    var t1 = $('.dataTable').DataTable().draw();
                                    $('#sum_table').val(data['sum']);
                                    $('#valeur_remise').trigger('change'); 
                                }

                            }
                        });
                }  

                //info client après
                $('#tva').val(data['tva_brut']);
                $('#tva').trigger("chosen:updated");
                $('#tva').trigger("change");
            }
        });
    });

    $('#table_details_devis tbody ').on('click', 'tr .edt_det', function() {

        if($('#id_client').val() == ''){

            ajax_loadmessage('Il faut choisir un client','nok');
            return false;
        }

        if($('#id_commercial').val() == ''){

            ajax_loadmessage('Il faut choisir un commercial','nok');
            return false;
        }

        if($('#commission').val() == ''){

            ajax_loadmessage('Il faut saisir une commission','nok');
            return false;
        }

        var $link  = $(this).attr('rel');
        var $titre = 'Modifier détail Devis';
        var $data  = $(this).attr('data')+'&commission='+$('#commission').val()+'&type_commission='+$('#type_commission').val();
        ajax_bbox_loader($link, $data, $titre, 'large')

    });



    $('#table_details_devis tbody ').on('click', 'tr .del_det', function() {
        var $id_detail = $(this).attr('data');
        $.ajax({

            cache: false,
            url  : '?_tsk=add_detaildevis&ajax=1',
            type : 'POST',
            data : '&act=1&<?php echo MInit::crypt_tp('exec', 'delete') ?>&'+$id_detail,
            dataType:"html",
            success: function(data){
                var data_arry = data.split("#");
                if(data_arry[0]==0){
                    ajax_loadmessage(data_arry[1],'nok',5000)
                }else{
                    ajax_loadmessage(data_arry[1],'ok',3000);
                    var t1 = $('.dataTable').DataTable().draw();
                    $('#sum_table').val(data_arry[2]);
                    $('#valeur_remise').trigger('change');
                    $('#is_abn').remove();
                }

            }
        });
    });

    $('#add_client_diver').on( 'click', function () {

        var $link  = $(this).attr('rel');
        var $titre = $(this).attr('data_titre');
        var $data  = $(this).attr('data');
        ajax_bbox_loader($link, $data, $titre, 'large')

    });




});
</script>
