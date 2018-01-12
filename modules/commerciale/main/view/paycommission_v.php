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
    exit('3#' . $commission->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur XXX');
}

$id_commerciale = $commission->g("id_commerciale");


?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('commissions', 'Liste des commissions', MInit::crypt_tp('id', $id_commerciale), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        <?php echo ACTIV_APP; ?> Commission: <?php $commission->s('id') ?>
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
                $form = new Mform('paycommission', 'paycommission', '', 'commissions&'.MInit::crypt_tp('id', $id_commerciale), '0', null);
                $form->input_hidden('id', $commission->g('id'));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));
                //Add fields input here

                //Reste
                $reste = Mcommission::reste_commission(Mreq::tp('id'));
                //Reste
                $form->input('Reste', 'reste', "text", "9", $reste[0], null, $multi = true, true);


                //Montant
                $mt_array[] = array("required", "true", "Saisir le montant");
                $form->input("Montant", "debit", "text", "9", null, $mt_array, null, $readonly = null);

                //Methode de paiement
                $methode_array  = array('Espèce' => 'Espèce', 'Chèque' => 'Chèque','Virement'=>'Virement','Autre' =>'Autre' );
                $form->select('Méthode de Paiement', 'methode_payement', 3, $methode_array, Null,'Espèce', $multi = NULL );


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

		