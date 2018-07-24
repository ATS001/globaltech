<?php
//Get all contrat info 
$info_contrat = new Mcontrat();
//Set ID of Module with POST id
$info_contrat->id_contrat = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_contrat->get_contrat()) {
    // returne message error red to client 
    exit('3#' . $info_contrat->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
  $ref=$info_contrat->s('reference');
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

<?php TableTools::btn_add('contrats', 'Liste des abonnements', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier l'abonnement: <?php  echo $ref; ?>
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
$form = new Mform('editcontrat', 'editcontrat', 1 , 'contrats', '0', null);
$form->input_hidden('id', Mreq::tp('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

$list_devis = Mcontrat::select_devis($info_contrat->s('iddevis'));

//Reference
$form->input_hidden('checker_reference', MInit::cryptage($info_contrat->s('reference'), 1));
$form->input_hidden('ref', $info_contrat->s('reference'));

//Devis
$form->select('Devis', 'iddevis', 8, $list_devis, '------', $info_contrat->s('iddevis'), null, null);


//Date effet
$array_date_effet[] = array('required', 'true', 'Insérer la date effet');
$form->input_date('Date début', 'date_effet', 4, $info_contrat->s('date_effet'), $array_date_effet);

//Date fin
$array_date_fin[] = array('required', 'true', 'Insérer la date de fin');
$form->input_date('Date de fin', 'date_fin', 4, $info_contrat->s('date_fin'), $array_date_fin);

//Type échéance
$ech_array[] = array('required', 'true', 'Choisir un type échéance');
$form->select_table('Type échéance', 'idtype_echeance', 8, 'ref_type_echeance', 'id', 'id', 'type_echeance', $indx = '------', $info_contrat->s('idtype_echeance'), $multi = NULL, $where = NULL, $ech_array);

// Facturation
$facturation_array[]  = array('Début de période' , 'D' );
$facturation_array[]  = array('Fin de période' , 'F' );
$form->radio('Facturation', 'periode_fact', $info_contrat->s('periode_fact'), $facturation_array, '');


//Commentaire
$form->input_editor('Commentaire', 'commentaire', 8, $info_contrat->s('commentaire'), $js_array = null, $input_height = 50);

//Date notif
$array_date_notif[] = array('required', 'true', 'Insérer la date de notification');
$form->input_date('Date notification', 'date_notif', 4, $info_contrat->s('date_notif'), $array_date_notif);

//pj_id
$form->input('Justification du contrat', 'pj', 'file', 6, 'Justification_client.pdf', null);
$form->file_js('pj', 1000000, 'pdf', $info_contrat->s('pj'), 1);

//pj_id
/*$form->input('Photo', 'pj_photo', 'file', 6, 'Photo_client.jpeg', null);
$form->file_js('pj_photo', 1000000, 'image', $info_contrat->s('pj_photo'), 1);*/

//Table 
$columns = array('id' => '1','Item' => '5', 'Date échéance' => '12','Montant TTC' => '20', 'Commentaire' => '52', '#' =>'5'   );
$js_addfunct = 'var column = t.column(0);
     column.visible( ! column.visible() );';

$verif_value = $info_contrat->s('tkn_frm');


$form->draw_datatabe_form('table_echeance', $verif_value, $columns, 'addcontrat', 'addecheance_contrat', 'Ajouter une échéance', $js_addfunct);


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
        
        if ($("#idtype_echeance option:selected").text() == 'Autres') {

                $('.table_echeance').show();

            } else {

                $('.table_echeance').hide();
            }    

        $('#idtype_echeance').bind('select change', function () {

            if ($("#idtype_echeance option:selected").text() == 'Autres') {

                $('.table_echeance').show();

            } else {

                $('.table_echeance').hide();

            }

        });

        $('#addRow').on('click', function () {

            if ($('#iddevis').val() == '') {

                ajax_loadmessage('Il faut choisir un devis', 'nok');
                return false;
            }
            var $link = $(this).attr('rel');
            var $titre = $(this).attr('data_titre');
            var $data = $(this).attr('data')+'&dat_ef='+$('#date_effet').val()+'&dat_fn='+$('#date_fin').val();
            ajax_bbox_loader($link, $data, $titre, 'large')

        });




        $('#iddevis').on('change', function () {

            //var $adresse = '<div class="form-group>"><address><strong>Twitter, Inc.</strong><br>795 Folsom Ave, Suite 600<br>San Francisco, CA 94107<br><abbr title="Phone">P:</abbr>(123) 456-7890</address></div>';
            //$(this).parent('div').after($adresse);

        });

        $('#table_echeance tbody ').on('click', 'tr .edt_ctr', function () {

            if ($('#iddevis').val() == '') {

                ajax_loadmessage('Il faut choisir un devis', 'nok');
                return false;
            }
            var $link = $(this).attr('rel');
            var $titre = 'Modifier détail abonnement';
            var $data = $(this).attr('data')+'&dat_ef='+$('#date_effet').val()+'&dat_fn='+$('#date_fin').val();
            ajax_bbox_loader($link, $data, $titre, 'large')

        });
        $('#table_echeance tbody ').on('click', 'tr .del_ctr', function () {
            var $idecheance = $(this).attr('data');
            $.ajax({

                cache: false,
                url: '?_tsk=addecheance_contrat&ajax=1',
                type: 'POST',
                data: '&act=1&<?php echo MInit::crypt_tp('exec', 'delete') ?>&' + $idecheance,
                dataType: "html",
                success: function (data) {
                    var data_arry = data.split("#");
                    if (data_arry[0] == 0) {
                        ajax_loadmessage(data_arry[1], 'nok', 5000)
                    } else {
                        ajax_loadmessage(data_arry[1], 'ok', 3000);
                        var t1 = $('.dataTable').DataTable().draw();

                    }

                }
            });
        });






    });
</script>	

