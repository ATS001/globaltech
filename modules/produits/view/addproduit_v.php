    <div class="pull-right tableTools-container">
        <div class="btn-group btn-overlap">

            <?php TableTools::btn_add('produits', 'Liste des produits', Null, $exec = NULL, 'reply'); ?>

        </div>
    </div>
    <div class="page-header">
        <h1>
            Ajouter un produit
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
            </small>
        </h1>
    </div><!-- /.page-header -->
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
                    $form = new Mform('addproduit', 'addproduit', '', 'produits', '0');

//Type de produit
$type_array[] = array('required', 'true', 'Choisir un type');
$form->select_table('Type', 'idtype', 6, 'ref_types_produits', 'id', 'type_produit', 'type_produit', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat= 1', $type_array);

//Catégorie produit
$opt_categ = array('' => '------');
$form->select('Catégorie', 'idcategorie', 3, $opt_categ, $indx = NULL ,$selected = null, $multi = NULL);

//Unité de vente
$uv_array[] = array('required', 'true', 'Choisir une unité de vente');
$form->select_table('Unité de vente', 'iduv', 6, 'ref_unites_vente', 'id', 'unite_vente', 'unite_vente', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat= 1', $uv_array);

//prix vente
$pv_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Prix de vente', 'prix_vente', 'text', 6, null, $pv_array);

//Désignation
$designation_array[] = array('required', 'true', 'Insérez une désignation');
$designation_array[] = array('minlength', '2', 'Minimum 2 caractères');
$form->input('Désignation', 'designation', 'text', 6, null, $designation_array);

//stock minimale
$stock_min_array[] = array('number', 'true', 'Entrez un nombre valide');
$form->input('Stock minimale', 'stock_min', 'text', 6, null, $stock_min_array);

$form->button('Enregistrer le produit');

//Form render
 $form->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    
  <script type="text/javascript">
$(document).ready(function() {


        $('#prix_vente').attr('readonly', true);

       
    $('#idtype').on('change',function() {

        if($("#idtype option:selected").text() != 'Produit'){

            $('#prix_vente').attr('readonly', false);
            
        }else{

            $('#prix_vente').attr('readonly', true).val('');
           
        }

    });
    
    $('#idtype').change(function(e) {
        var $idtype = $(this).val();

        if($idtype == null){
            return true;
        }
        $('#idcategorie').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        //$('#categ_produit').trigger('change');
        //$('#idtype').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        $.ajax({

            cache: false,
            url  : '?_tsk=addproduit&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$idtype+'&<?php echo MInit::crypt_tp('exec', 'load_select_categ') ?>',
            dataType:"JSON",
            success: function(data){
               
                if(data['error'] == false){
                    ajax_loadmessage(data['mess'] ,'nok',5000);
                    return false;
                }else{
                    $.each(data, function(key, value) {   
                     $('#idcategorie')
                     .append($("<option></option>")
                         .attr("value",key)
                         .text(value)); 
                    });
                    $('#idcategorie').trigger("chosen:updated");
                }
                
                
            }//end success
        });
    });
    

    });
    
   </script>  