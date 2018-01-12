<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 10-01-2018
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

$id_commerciale=$commission->g('id_commerciale');

?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php TableTools::btn_add('commissions', 'Liste des commissions', MInit::crypt_tp('id', $id_commerciale), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier la commission: <?php $commission->s('id') ?>
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
                $form = new Mform('editcommissions', 'editcommissions', '1', 'commissions&' . MInit::crypt_tp('id', $id_commerciale), '0', null);
                $form->input_hidden('id', $commission->g('id'));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));


                //Objet ==>
                $array_objet[] = array("required", "true", "Insérer objet");
                $form->input("Object", "objet", "text", "9", $commission->g("objet"), $array_objet, null, $readonly = null);

                //Montant ==>
                $array_mt[] = array("required", "true", "Insérer le montant");
                $form->input("Montant", "credit", "text", "9", $commission->g("credit"), $array_mt, null, $readonly = null);

                //Methode de paiement
                //$methode_array  = array('Espèce' => 'Espèce', 'Chèque' => 'Chèque','Virement'=>'Virement','Autre' =>'Autre' );
                //$form->select('Méthode de Paiement', 'methode_payement', 3, $methode_array, Null,$commission->g("methode_payement"), $multi = NULL );

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

		