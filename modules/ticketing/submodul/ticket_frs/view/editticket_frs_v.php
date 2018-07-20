<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View
//Get all tickets info 
$info_tickets = new Mticket_frs;
//Set ID of Module with POST id
$info_tickets->id_tickets = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_tickets->get_ticket_frs()) {
    // returne message error red to client 
    exit('3#' . $info_tickets->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//var_dump($info_tickets->tickets_info);
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('ticket_frs', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

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
                $form = new Mform('editticket_frs', 'editticket_frs', '1', 'ticket_frs', '0', null);
                $form->input_hidden('id', $info_tickets->g('id'));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));

//For more Example see form class
//Fournisseur ==> 
                $frn_array[] = array('required', 'true', 'Choisir un fournisseur');
                $form->select_table('Fournisseur', 'id_fournisseur', 6, 'fournisseurs', 'id', 'id', 'denomination', $indx = '------', $info_tickets->g('id_fournisseur'), $multi = NULL, $where = 'etat=1', $frn_array, NULL);


//Date incident ==> 
                $date_incident[] = array('required', 'true', 'Insérer la date incident');
                $form->input_date('Date incident', 'date_incident', 4, $info_tickets->g('date_incident'), $date_incident);

//Select nature incident
                $hard_code_remise = '<label style="margin-left:30px;margin-right : 20px;">'
                        . '</label><input  id="autre_nt" name="autre_nt" class="input-large alignLeft" value="' . $info_tickets->g('autre_nt') . '" type="text"><span class="help-block"></span>';
                $nature_incident = array('Coupure de connexion' => 'Coupure de connexion',
                    'Intermittence de connexion' => 'Intermittence de connexion',
                    'BGP down' => 'BGP down',
                    'Lien PtP down' => 'Lien PtP down',
                    'Interférences' => 'Interférences',
                    'Bande passante' => 'Bande passante',
                    'CRC Errors' => 'CRC Errors',
                    'Autres' => 'Autres');
                $form->select('Nature incident', 'nature_incident', 4, $nature_incident, $indx = NULL, $info_tickets->g('nature_incident'), $multi = NULL, $hard_code_remise);

//Prise en charge par fournisseur 
                $hard_code_remise = '<label style="margin-left:30px;margin-right : 20px;">'
                        . '</label><input  id="autre_pecf" name="autre_pecf" class="input-large alignLeft" value="' . $info_tickets->g('autre_pecf') . '" type="text"><span class="help-block"></span>';
                $pec_frs = array('Equipe Noc' => 'Equipe Noc',
                    'Autres' => 'Autres');
                $form->select('PEC Fournisseur', 'prise_charge_frs', 4, $pec_frs, $indx = NULL, $info_tickets->g('prise_charge_frs'), $multi = NULL, $hard_code_remise);

//Prise en charge par Globaltech 
                $hard_code_remise = '<label style="margin-left:30px;margin-right : 20px;">'
                        . '</label><input  id="autre_pecg" name="autre_pecg" class="input-large alignLeft" value="' . $info_tickets->g('autre_pecg') . '" type="text"><span class="help-block"></span>';
                $pec_glbt = array('Support Technique' => 'Support Technique',
                    'Autres' => 'Autres');
                $form->select('PEC Globaltech', 'prise_charge_glbt', 4, $pec_glbt, $indx = NULL, $info_tickets->g('prise_charge_glbt'), $multi = NULL, $hard_code_remise);

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


        if ($('#autre_pecg').val() == "")
            $('#autre_pecg').hide
        if ($('#autre_pecf').val() == "")
            $('#autre_pecf').hide();
        if ($('#autre_nt').val() == "")
            $('#autre_nt').hide();




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