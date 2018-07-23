<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//View
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('ticket_frs', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

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
                $form = new Mform('addticket_frs', 'addticket_frs', '', 'ticket_frs', '0', null);

//For more Example see form class
//Fournisseur ==> 
                $frn_array[] = array('required', 'true', 'Choisir un fournisseur');
                $form->select_table('Fournisseur', 'id_fournisseur', 6, 'fournisseurs', 'id', 'denomination', 'denomination', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=1', $frn_array, NULL);

//Date incident ==> 
                $date_incident[] = array('required', 'true', 'Insérer la date incident');
                $form->input_date('Date incident', 'date_incident', 4, date('d-m-Y'), $date_incident);

//Select nature incident
                $hard_code_remise = '<label style="margin-left:30px;margin-right : 20px;">'
                        . '</label><input  id="autre_nt" name="autre_nt" class="input-large alignLeft" value="" type="text"><span class="help-block"></span>';
                $nature_incident = array('Coupure de connexion' => 'Coupure de connexion',
                    'Intermittence de connexion' => 'Intermittence de connexion',
                    'BGP down' => 'BGP down',
                    'Lien PtP down' => 'Lien PtP down',
                    'Interférences' => 'Interférences',
                    'Bande passante' => 'Bande passante',
                    'CRC Errors' => 'CRC Errors',
                    'Autres' => 'Autres');
                $form->select('Nature incident', 'nature_incident', 4, $nature_incident, $indx = NULL, $selected = 'Coupure de connexion', $multi = NULL,$hard_code_remise);

//Prise en charge par fournisseur 
                $hard_code_remise = '<label style="margin-left:30px;margin-right : 20px;">'
                        . '</label><input  id="autre_pecf" name="autre_pecf" class="input-large alignLeft" value="" type="text"><span class="help-block"></span>';
                $pec_frs = array('Equipe Noc' => 'Equipe Noc',
                    'Autres' => 'Autres');
                $form->select('PEC Fournisseur', 'prise_charge_frs', 4, $pec_frs, $indx = NULL, $selected = 'Equipe Noc', $multi = NULL, $hard_code_remise);

//Prise en charge par Globaltech 
                $hard_code_remise = '<label style="margin-left:30px;margin-right : 20px;">'
                        . '</label><input  id="autre_pecg" name="autre_pecg" class="input-large alignLeft" value="" type="text"><span class="help-block"></span>';
                $pec_glbt = array('Support Technique' => 'Support Technique',
                    'Autres' => 'Autres');
                $form->select('PEC Globaltech', 'prise_charge_glbt', 4, $pec_glbt, $indx = NULL, $selected = 'Support Technique', $multi = NULL,$hard_code_remise);

//Description
                $array_desc[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'description', 8, NULL, $array_desc, $input_height = 200);


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

        $('#autre_pecf').hide();
        $('#autre_nt').hide();
        $('#autre_pecg').hide();

        $('#nature_incident').on('change', function () {

            if ($("#nature_incident option:selected").text() != 'Autres') {
                $('#autre_nt').val("");
                $('#autre_nt').hide();


            } else {
                $('#autre_nt').show();

            }

        });

        $('#prise_charge_frs').on('change', function () {

            if ($("#prise_charge_frs option:selected").text() != 'Autres') {
                $('#autre_pecf').val("");
                $('#autre_pecf').hide();


            } else {
                $('#autre_pecf').show();

            }

        });

        $('#prise_charge_glbt').on('change', function () {

            if ($("#prise_charge_glbt option:selected").text() != 'Autres') {
                $('#autre_pecg').val("");
                $('#autre_pecg').hide();


            } else {
                $('#autre_pecg').show();

            }

        });



    });

</script>  