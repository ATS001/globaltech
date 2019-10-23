<?php
defined('_MEXEC') or die;

//Get all compte info 
$info_produit = new Mproduit();
//Set ID of Module with POST id
$info_produit->id_produit = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_produit->get_produit()) {
    // returne message error red to client 
    exit('3#' . $info_produit->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('produits', 'Liste des produits', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier un produit
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
                $form = new Mform('editproduit', 'editproduit', $info_produit->Shw('id', 1), 'produits', '0');
                $form->input_hidden('id', $info_produit->Shw('id', 1));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));

                //Exige SN ==>
                $array_exige_sn = array('Oui' => 'Oui', 'Non' => 'Non');
                $form->select('Exige SN', 'exige-sn', 2, $array_exige_sn, $indx = NULL, $info_produit->Shw('exige-sn',1), $multi = NULL);
                
                //Entrepôt de produit
                $entrepot_array[] = array('required', 'true', 'Choisir un entrepôt');
                $form->select_table('Entrepôt', 'id_entrepot', 6, 'entrepots', 'id', 'libelle', 'libelle', $indx = '------', $selected =$info_produit->Shw('id_entrepot', 1), $multi = NULL, $where = 'etat= 1', $entrepot_array);

                //Type de produit
                $type_array[] = array('required', 'true', 'Choisir un type');
                $form->select_table('Type', 'idtype', 6, 'ref_types_produits', 'id', 'type_produit', 'type_produit', $indx = '------', $info_produit->Shw('idtype', 1), $multi = NULL, $where = NULL, $type_array);
                
                //Catégorie
                $cat_array[] = array('required', 'true', 'Choisir une catégorie');
                $form->select_table('Catégorie', 'idcategorie', 6, 'ref_categories_produits', 'id', 'categorie_produit', 'categorie_produit', $indx = '------', $info_produit->Shw('idcategorie', 1), $multi = NULL, $where = 'type_produit='.$info_produit->Shw('idtype',1), $cat_array);

                //Unité de vente
                $uv_array[] = array('required', 'true', 'Choisir une unité de vente');
                $form->select_table('Unité de vente', 'iduv', 6, 'ref_unites_vente', 'id', 'unite_vente', 'unite_vente', $indx = '------', $info_produit->Shw('iduv', 1), $multi = NULL, $where = NULL, $uv_array);

                
                //Désignation
                $designation_array[] = array('required', 'true', 'Insérez une désignation');
                $designation_array[] = array('minlength', '2', 'Minimum 2 caractères');
                $form->input('Désignation', 'designation', 'text', 6, $info_produit->Shw('designation', 1), $designation_array);

                //prix vente
                $pv_array[] = array('number', 'true', 'Entrez un nombre valide');
                $form->input('Prix de vente', 'prix_vente', 'text', 6, $info_produit->Shw('prix_vente', 1), $pv_array);

                //stock minimale
                $stock_min_array[] = array('number', 'true', 'Entrez un nombre valide');
                $form->input('Stock minimale', 'stock_min', 'text', 6, $info_produit->Shw('stock_min', 1), $stock_min_array);


                $form->button('Modifier le produit');

                //Form render
                $form->render();
                ?>
            </div>
        </div>
    </div>
</div>


 <script type="text/javascript">
$(document).ready(function() {

 if($("#idtype option:selected").text() == 'Produit'){
 $('#prix_vente').attr('readonly', true);
 }
 
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
            url  : '?_tsk=addproduits&ajax=1',
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