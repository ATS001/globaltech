<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 04-01-2018
//View
//Get all commerciale info 
$commission = new Mcommission();
//Set ID of Module with POST id
$commission->id_commission = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or !$commission->get_commission()) {
    // returne message error red to client
    exit('3#' . $commission->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$id_commerciale = $commission->g("id_commerciale");
$id_commission=$commission->commission_info["id_credit"];

?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('paiements', 'Liste des paiements', MInit::crypt_tp('id', $id_commission), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        <?php echo ACTIV_APP; ?> paiement : <?php $commission->s('id') ?>
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
                $form = new Mform('adddecharge', 'adddecharge', '', 'paiements&'.MInit::crypt_tp('id', $id_commission), '0', null);
                $form->input_hidden('id', $commission->g('id'));
                //$form->input_hidden('idc', Mreq::tp('idc'));
                //$form->input_hidden('idh', Mreq::tp('idh'));
                //Add fields input here

                //decharge
                $form->input('Décharge', 'decharge', 'file', 6, null, null);
                $form->file_js('decharge', 1000000, 'pdf');

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

//JS Bloc    

    });
</script>	

		