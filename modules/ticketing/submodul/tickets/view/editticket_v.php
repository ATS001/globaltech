<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View
//Get all tickets info 
$info_tickets = new Mtickets();
//Set ID of Module with POST id
$info_tickets->id_tickets = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_tickets->get_tickets()) {
    // returne message error red to client 
    exit('3#' . $info_user->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//var_dump($info_tickets->tickets_info);
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('tickets', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier le tickets: <?php $info_tickets->s('id') ?>
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
                $form = new Mform('editticket', 'editticket', '1', 'tickets', '0', null);
                $form->input_hidden('id', $info_tickets->g('id'));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));


$hard_code_site_client = '<label style="margin-left:15px;margin-right : 20px;">Site: </label><select id="projet" name="projet" class="chosen-select col-xs-6 col-sm-4" chosen-class="' . ((6 * 100) / 12) . '" ><option >' . $info_tickets->g('site') . '</option></select>';


                $client_array[] = array('required', 'true', 'Choisir un Client');
                $form->select_table('Client ', 'id_client', 5, 'clients', 'id', 'id', 'denomination', $indx = '------', $info_tickets->g('id_client'), $multi = NULL, $where = 'etat=1', $client_array, $hard_code_site_client);



              
//Serial number ==> 
                
                $form->input("Serial number", "serial_number", "text", "9", $info_tickets->g('serial_number'), NULL, null, $readonly = null);

//Date prévisionnelle ==> 
                $date_prev[] = array('required', 'true', 'Insérer une date prévisionnelle');
                $form->input_date('Date prévisionnelle', 'date_previs', 2, $info_tickets->g('date_previs'), $date_prev);

//Type Produit
//Type produit old
                $form->input_hidden('type_produit_old', $info_tickets->g('type_produit'));
                $hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categorie_produit" name="categorie_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="' . ((6 * 100) / 12) . '" ><option value="' . $info_tickets->g('categorie_produit') . '" >' . $info_tickets->g('categorie_produit') . '</option></select>';
                $type_produit_array[] = array('required', 'true', 'Choisir un Type Produit');
                $form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'id', 'type_produit', $indx = '------', $info_tickets->g('type_produit'), $multi = NULL, NULL, $type_produit_array, $hard_code_type_produit); //Produit


                $opt_produit = array($info_tickets->g('id_produit') => $info_tickets->g('prd'));
                $form->select('Produit / Service', 'id_produit', 8, $opt_produit, $indx = NULL, $selected = NULL, $multi = NULL, null);

//Technicien ==> 
                //$array_technicien[] = array("required", "true", "Choisir un technicien");
                //$form->select_table('Technicien', 'id_technicien', 6, 'users_sys', 'id', 'id', 'CONCAT(users_sys.lnom," ",users_sys.fnom)', $indx = '------', $info_tickets->g('id_technicien'), $multi = NULL, $where = 'etat=1', $array_technicien, NULL);
//Message
                $array_message[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'message', 8, $info_tickets->g('message'), $array_message, $input_height = 200);

                $form->button('Enregistrer');
//Form render
                $form->render();
                ?>
            </div>
        </div>
    </div>
</div>
<!-- End Add ticket bloc -->

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
/* ******************************************************************************* */
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

    });
</script>	

