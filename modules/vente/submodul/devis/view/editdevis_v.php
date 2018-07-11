<?php
//Get all Devis info 
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
				
		<?php TableTools::btn_add('devis','Liste des Devis', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le Devis: <?php $info_devis->s('reference')?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header   -->
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
$form = new Mform('editdevis', 'editdevis', '1', 'devis', '0', null);
$form->input_hidden('id', $info_devis->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Reference
$form->input_hidden('checker_reference',  MInit::cryptage($info_devis->g('reference'), 1));
$form->input_hidden('reference', $info_devis->g('reference'));

//Date devis
$array_date[]= array('required', 'true', 'Insérer la date de devis');
$form->input_date('Date devis', 'date_devis', 4, date('d-m-Y'), $array_date);
//Client liste
$hard_code_client = '<a id="add_client_diver" href="#" rel="add_client_diver" data="" data_titre="Ajout Client Diver " class=" "><span class="help-block returned_span"><i class="fa fa-plus"></i> Ajouter un client divers</span></a>';
$client_array[]  = array('required', 'true', 'Choisir un Client');
$form->select_table('Client ', 'id_client', 8, 'clients', 'id', 'denomination' , 'denomination', $indx = '------' , $info_devis->g('id_client'),$multi=NULL, $where='etat=1 or type_client=\'T\'', $client_array, $hard_code_client);
//TVA
$tva_opt = array('O' => 'OUI' , 'N' => 'NON' );
$form->select('Soumis à TVA', 'tva', 2, $tva_opt, $indx = NULL ,$info_devis->g('tva'), $multi = NULL);
//Projet if client have more project
$form->input('Projet', 'projet', 'text' ,'6', $info_devis->g('projet'), null , null, null);

//Commercial
$hard_code_commercial = '<span class="help-block returned_span">...</span>';
$commercial_array[]  = array('required', 'true', 'Choisir un Commercial');
$form->select_table('Commercial', 'id_commercial', 6, 'commerciaux', 'id', 'CONCAT(nom," ",prenom)' , 'CONCAT(nom," ",prenom)' , $indx = '------' ,$selected=$info_devis->g('id_commercial'),$multi=NULL, $where='etat=1', $commercial_array, $hard_code_commercial);
//Commission du commercial
$array_commission[]= array('required', 'true', 'Insérer la commission du commercial');
$array_commission[]= array('number', 'true', 'Montant invalid' );
$form->input('Commission du commercial (%)', 'commission', 'text' ,'2 is-number alignRight',$info_devis->g('commission'), $array_commission, null, null);


//Table 
$columns = array('id' => '1' ,'Item' => '3' , 'Réference'=>'14', 'Produit' => '30', 'P.U HT' => '10', 'T.Rem' => '5', 'V.Remise' => '5', 'Qte' => '5', 'Total HT' => '10', 'TVA' => '7', 'Total' =>'10', '#' =>'3'   );
$js_addfunct = 'var column = t.column(0);
     column.visible( ! column.visible() );';
$verif_value = $info_devis->g('tkn_frm');    
$form->draw_datatabe_form('table_details_devis', $verif_value, $columns, 'adddevis', 'add_detaildevis', 'Ajouter détails Devis', $js_addfunct);
//Finance bloc
$form->bloc_title('Zone totaux');
//Type Remise
$form->input('Total des articles enregistrés', 'sum_table', 'text' ,'4 is-number alignRight', $info_devis->g('totalht'), null, null, 'readonly');
$hard_code_remise = '<label style="margin-left:15px;margin-right : 20px;">Valeur remise: </label><input id="valeur_remise" name="valeur_remise" class="input-small alignRight" value="'.$info_devis->g('valeur_remise').'" type="text">';
$hard_code_remise .= '<label style="margin-left:30px;margin-right : 20px;">Commission   :</label><input readonly="" id="total_commission" name="total_commission" class="input-large is-number alignRight" value="'.$info_devis->g('total_commission').'" type="text"><span class="help-block">Cette remise sera appliquée sur le total H.T de devis</span>';
$typ_remise = array('P' => 'Pourcentage' , 'M' => 'Montant' );

$form->select('Remise Exept', 'type_remise', 3, $typ_remise, $indx = NULL ,$info_devis->g('type_remise'), $multi = NULL,  $hard_code_remise);



//Prix
$prixht_array[]  = array('required', 'true', 'Le montant est invalid');
$hard_code_prices = '<label style="margin-left:15px;margin-right : 20px;"> T.V.A    Calculée     : </label><input id="totaltva" readonly="" name="totaltva" class="input-small is-number alignRight " value="'.$info_devis->g('totaltva').'" type="text">';
$hard_code_prices .= '<label style="margin-left:15px;margin-right : 20px;">Prix Global TTC: </label><input readonly="" id="totalttc" name="totalttc" class="input-large is-number alignRight" value="'.$info_devis->g('totalttc').'" type="text">';
$form->input('Prix Global HT', 'totalht', 'text' ,'3 is-number alignRight', $info_devis->g('totalht'), $prixht_array, $hard_code_prices, 'readonly');
//Validité
$vie_opt = array('30' => '30 Jours' , '60' => '60 Jours', '90' => '90 Jours', '180' => 'Six mois', '365' => 'Un an');
$form->select('Validité', 'vie', 3, $vie_opt, $indx = NULL ,$selected = $info_devis->g('vie'), $multi = NULL);
//Conditions commercial
$clauses = 'Paiement 100% à la commande pour';
$form->input_editor('Conditions commerciales', 'claus_comercial', 8, $info_devis->g('claus_comercial'), $js_array = null,  $input_height = 50);
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
    $cms = parseFloat($('#commission').val());
    
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
    	$('#'+$f_total_ht).val($total_ht);
    	$('#'+$f_total_tva).val($total_tva);
    	$('#'+$f_total_ttc).val($total_ttc);  
        $('#'+$f_total_commission).val($total_commission);  
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
   		var $data  = $(this).attr('data')+'&commission='+$('#commission').val(); ; 
        ajax_bbox_loader($link, $data, $titre, 'large')
        
    });

    $('#tva').on('change', function () {
        var table = $('#table_details_devis').DataTable();

        if (table.data().count()) {

            bootbox.confirm("<span class='text-warning bigger-110 orange'>Le changement de TVA sera appliqué sur l'ensemble des lignes détails, voulez vous vous continuer ?</span>", 
                function(result){
                    if(result == true){
                        var $tkn_frm = $(this).attr('tkn_frm');
                        $.ajax({

                            cache: false,
                            url  : '?_tsk=add_detaildevis&ajax=1'+'&act=1&<?php echo MInit::crypt_tp('exec', 'set_tva')?>',
                            type : 'POST',
                            data : $('#editdevis').serialize(),
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


                });  
            
            
            
        }

    });

    $('#commission').binf('input change', function () {
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
                            data : $('#editdevis').serialize(),
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
                     
                      $('#commission').val($cms);
                      
                    }
                }
            );  
        }

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
        var $data  = $(this).attr('data')+'&commission='+$('#commission').val();
        ajax_bbox_loader($link, $data, $titre, 'large')
        
    });

    $('#valeur_remise').bind('input change',function() {
    	// Calcul values
    	var totalht       = parseInt($('#sum_table').val());
    	var type_remise   = $('#type_remise').val();
    	var remise_valeur = parseFloat($('#valeur_remise').val());
    	var tva           = $('#tva').val();
    	var dix_per_ht    = parseFloat((totalht * 10) / 100);
        var commission    = parseFloat($("#commission").val());

    	if((type_remise == 'P' && remise_valeur > 10) || (type_remise == 'M' && remise_valeur > dix_per_ht)){
    		ajax_loadmessage('La remise exeptionnel ne doit pas dépasser 10% du Total des articles','nok');
    		$('#totalht').val(totalht);
    		$('#valeur_remise').val(0);
    		calculat_devis(totalht, null, 0, tva, 'totalht', 'totaltva', 'totalttc',commission,'total_commission');
    		return false;
    	}
    	calculat_devis(totalht, type_remise, remise_valeur, tva, 'totalht', 'totaltva', 'totalttc',commission,'total_commission');
    })
    $('#type_remise').on('change', function () {
        $('#valeur_remise').trigger('input');
    });
    
    $('#id_client').on('input change', function () {
                
        var $id_client = $(this).val();
        $.ajax({

            cache: false,
            url  : '?_tsk=add_detaildevis&ajax=1',
            type : 'POST',
            data : '&act=1&<?php echo MInit::crypt_tp('exec', 'info_client') ?>&id='+$id_client,
            dataType:"JSON",
            success: function(data){
                //info client après               
                $('#tva').val(data['tva_brut']);
                $('#tva').trigger("chosen:updated");

            }
        });

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

