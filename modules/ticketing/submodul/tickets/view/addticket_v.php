<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View

//chck if called with client ID then suggest task for after exec
//id_clnt crypted => id client
//tsk_aft crypted => Task after exec
//
$after_exec     = 'tickets';
$id_clnt        = MReq::tp('id_clnt');
$tsk_aft        = MReq::tp('tsk_aft');
$name_client    = null;
$title          = 'Ajouter Ticket';
$btn_return_txt = 'Liste des tickets';
$btn_task       = 'tickets';
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
    $btn_return_txt = 'Détail Client '.$name_client;
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
                $form = new Mform('addtickets', 'addtickets', '',$after_exec, '0', null);

//Client ==>

$hard_code_site_client = '<label style="margin-left:15px;margin-right : 20px;">Site: </label><select id="projet" name="projet" class="chosen-select col-xs-6 col-sm-4" chosen-class="' . ((6 * 100) / 12) . '" ><option >----</option></select>';


                if($id_clnt == NULL){
                $client_array[] = array('required', 'true', 'Choisir un Client');
                $form->select_table('Client', 'id_client', 5, 'clients', 'id', 'denomination', 'denomination', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=1', $client_array, $hard_code_site_client);
}else
{
                $form->input_hidden('id_client',$id_clnt);
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));
}

//contact==>
                 $form->input("Contact", "contact", "text", "9", null, NULL, null, $readonly = null);

//Serial number==>
                 $form->input("Serial number", "serial_number", "text", "9", null, NULL, null, $readonly = null);
                 //Technicien ==>
$array_technicien[] = array("required", "true", "Choisir un technicien");
$form->select_table('Technicien', 'id_technicien', 6, 'users_sys', 'id', 'id', 'CONCAT(users_sys.lnom," ",users_sys.fnom)', $indx = '------', $selected = NULL, $multi = NULL, $where = ' service=6 AND etat=1', $array_technicien, NULL);

//Date problème ==>
                $date_prob[] = array('required', 'true', 'Insérer une date prévisionnelle');
                $form->input_date('Date problème', 'date_probleme', 2, date('d-m-Y'), $date_prob);

//Date prévisionnelle ==>
                $date_prev[] = array('required', 'true', 'Insérer une date prévisionnelle');
                $form->input_date('Date prévisionnelle', 'date_previs', 2, date('d-m-Y'), $date_prev);

//Type Produit

                $hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categorie_produit" name="categorie_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="' . ((6 * 100) / 12) . '" ><option ></option></select>';
              $form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'type_produit', 'type_produit', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat = 1', null, $hard_code_type_produit);


                $form->select('Produit / Service', 'id_produit', 8, null, $indx = NULL, $selected = NULL, $multi = NULL, null);

//Message
                $array_message[] = array("required", "true", "Insérer une description ");
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

        $('#type_produit').change(function (e) {
            var $type_produit = $(this).val();

            if ($type_produit == null) {
                return true;
            }
            $('#categorie_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
            $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');

            $.ajax({

                cache: false,
                url: '?_tsk=addtickets&ajax=1',
                type: 'POST',
                data: '&act=1&id=' + $type_produit + '&<?php echo MInit::crypt_tp('exec', 'load_select_categ') ?>',
                dataType: "JSON",
                success: function (data) {

                    if (data['error'] == false) {
                        ajax_loadmessage(data['mess'], 'nok', 5000);
                        return false;
                    } else {
                        $.each(data, function (key, value) {
                            $('#categorie_produit')
                                    .append($("<option></option>")
                                            .attr("value", key)
                                            .text(value));
                        });
                        $('#categorie_produit').trigger("chosen:updated");

                    }


                }//end success
            });
        });
        $('#categorie_produit').change(function (e) {
            var $categorie_produit = $(this).val();

            if ($categorie_produit == null) {
                return true;
            }
            $('#id_produit').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
            $.ajax({

                cache: false,
                url: '?_tsk=addtickets&ajax=1',
                type: 'POST',
                data: '&act=1&id=' + $categorie_produit + '&<?php echo MInit::crypt_tp('exec', 'load_select_produit') ?>',
                dataType: "JSON",
                success: function (data) {
                    if (data['error'] == false) {
                        ajax_loadmessage(data['mess'], 'nok', 5000);
                        return false;
                    } else {
                        $.each(data, function (key, value) {
                            $('#id_produit')
                                    .append($("<option></option>")
                                            .attr("value", key)
                                            .text(value));
                        });
                        $('#id_produit').trigger("chosen:updated");

                    }


                }//end success
            });

        });

        //************************************
         $('#serial_number').blur(function (e) {
            var $serial_number = $(this).val();

            if ($serial_number == null) {
                return true;
            }

            $.ajax({

                cache: false,
                url: '?_tsk=addtickets&ajax=1',
                type: 'POST',
                data: '&act=1&id=' + $serial_number + '&<?php echo MInit::crypt_tp('exec', 'check_exist_sn') ?>',
                dataType: "JSON",
                success: function (data) {

                  if ( data['sn'] == "") {
                     $('.show_info_product').remove();
                     $('#serial_number').parent('div').after('<span class="show_info_product help-block returned_span"></span>');
                  }else
                     if (typeof data['sn'] != "" && data['error'] == false){
                     $('.show_info_product').remove();
                     $('#serial_number').parent('div').after('<span class="show_info_product help-block returned_span">Ce produit n\' a pas été fourni par GLOBALTECH</span>');
                    }
                    else
                    if(data[$serial_number] !== 'undefined')
                    {
                     $('.show_info_product').remove();
                     $('#serial_number').parent('div').after('<span class="show_info_product help-block returned_span">Ce produit a été fourni par GLOBALTECH</span>');
                    }

                    //}


                }//end success
            });

        });

        //************************************

		 //************************************

		  $('#id_client').change(function (e) {
            var $id_client = $(this).val();

            if ($id_client == null) {
                return true;
            }
            //$('#id_client').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
            $.ajax({

                cache: false,
                url: '?_tsk=addtickets&ajax=1',
                type: 'POST',
                data: '&act=1&id=' + $id_client + '&<?php echo MInit::crypt_tp('exec', 'load_client_site') ?>',
                dataType: "JSON",
                success: function (data) {
                    if (data['error'] == false) {
                      $('#projet').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
                        ajax_loadmessage(data['mess'], 'nok', 5000);
                        return false;
                    } else {
                        $.each(data, function (key, value) {
                            $('#projet')
                                    .append($("<option></option>")
                                            .attr("value", key)
                                            .text(value));
                        });
                        $('#projet').trigger("chosen:updated");

                    }


                }//end success
            });

        });

        //************************************

    });
</script>
