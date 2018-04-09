<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('tickets', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Ajouter un ticket
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
                $form = new Mform('addtickets', 'addtickets', '', 'tickets', '0', null);

//For more Example see form class
//Client ==> 
                $client_array[] = array('required', 'true', 'Choisir un Client');
                $form->select_table('Client', 'id_client', 6, 'clients', 'id', 'denomination', 'denomination', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=1', $client_array, NULL);

//Projet ==> 
                $array_projet[] = array("required", "true", "Insérer le projet");
                $form->input("Projet", "projet", "text", "9", null, $array_projet, null, $readonly = null);

//Date prévisionnelle ==> 
                $date_prev[] = array('required', 'true', 'Insérer une date prévisionnelle');
                $form->input_date('Date prévisionnelle', 'date_previs', 2, date('d-m-Y'), $date_prev);

//Type Produit

$hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categorie_produit" name="categorie_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="'.((6 * 100) / 12).'" ><option >----</option></select>';
$type_produit_array[]  = array('required', 'true', 'Choisir un Type Produit');
$form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'type_produit' , 'type_produit', 
                     $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat = 1' , $type_produit_array, $hard_code_type_produit);

$opt_produit = array('' => '------');
$form->select('Produit / Service', 'id_produit', 8, $opt_produit, $indx = NULL ,$selected = NULL, $multi = NULL,  null);


//Technicien ==> 
                //$array_technicien[] = array("required", "true", "Choisir un technicien");
                //$form->select_table('Technicien', 'id_technicien', 6, 'users_sys', 'id', 'id', 'CONCAT(users_sys.lnom," ",users_sys.fnom)', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=1', $array_technicien, NULL);

//Message
                $array_message[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'message', 8, NULL, $array_message, $input_height = 200);

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
    $(document).ready(function () {

 $('#type_produit').change(function(e) {
        var $type_produit = $(this).val();

        if($type_produit == null){
            return true;
        }
        $('#categorie_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        
        $.ajax({

            cache: false,
            url  : '?_tsk=addtickets&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$type_produit+'&<?php echo MInit::crypt_tp('exec', 'load_select_categ') ?>',
            dataType:"JSON",
            success: function(data){
               
                if(data['error'] == false){
                    ajax_loadmessage(data['mess'] ,'nok',5000);
                    return false;
                }else{
                    $.each(data, function(key, value) {   
                     $('#categorie_produit')
                     .append($("<option></option>")
                         .attr("value",key)
                         .text(value)); 
                    });
                    $('#categorie_produit').trigger("chosen:updated");
                   
                }
                
                
            }//end success
        });
    });
    $('#categorie_produit').change(function(e) {
        var $categorie_produit = $(this).val();

        if($categorie_produit == null){
            return true;
        }
        $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        $.ajax({

            cache: false,
            url  : '?_tsk=addtickets&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$categorie_produit+'&<?php echo MInit::crypt_tp('exec', 'load_select_produit') ?>',
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

    });
</script>	

