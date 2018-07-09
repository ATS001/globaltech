<?php
$paiement = new Mcommission;
$paiement->id_paiement = Mreq::tp('id');
$paiement->get_paiement();

$id_paiement = Mreq::tp('id');
$id_commission = $paiement->paiements_info['id_credit'];

$decharge = $paiement->paiements_info['decharge'];
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_action('paiements', $paiement->id_paiement, 'detailpaiements');
        TableTools::btn_add('paiements', 'Liste des paiements', MInit::crypt_tp('id', $id_commission), $exec = NULL, 'reply');
        ?>


    </div>
</div>
<div class="page-header">
    <h1>
        Détails du paiement : <?php $paiement->m('id'); ?> 

        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div>

<!-- /.page-header -->
<div class="row">

    <div>
        <div id="user-profile-2" class="user-profile">
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                    <li class="active">
                        <a data-toggle="tab" href="#home">
                            <i class="green ace-icon fa fa-user bigger-120"></i>
                            Paiement
                        </a>
                    </li>
                    <?php if ($paiement->paiements_info['etat'] == 0) { ?>
                        <div class="widget-toolbar hidden-480">

                            <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'decharge') ?>" data="<?php echo MInit::crypt_tp('id', $paiement->id_paiement) ?>">
                                <i class="ace-icon fa fa-print"></i>
                            </a>

                        </div>  
<?php } ?>
                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">
                        <div class="col-xs-12 col-sm-4"></div>

                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                    <b>Paiement Info</b>
                                </div>
                            </div>

                            <div>
                                <ul class="list-unstyled  spaced">
                                    <li>
                                        <i class="ace-icon fa fa-caret-right green"></i> Commerciale
                                        <b style="color:green"><?php $paiement->m('commerciale'); ?></b>
                                    </li>

                                    <li>
                                        <i class="ace-icon fa fa-caret-right green"></i> Objet
                                        <b style="color:green"><?php $paiement->m('objet'); ?></b>
                                    </li>

                                    <li>
                                        <i class="ace-icon fa fa-caret-right green"></i> Méthode de paiement
                                        <b style="color:green"><?php $paiement->m('methode_payement'); ?></b>
                                    </li>

                                    <li>
                                        <i class="ace-icon fa fa-caret-right green"></i> Montant

                                        <b style="color:green"><?php $paiement->m('debit'); ?></b>
                                    </li>
                                    <li>
                                        <i class="ace-icon fa fa-caret-right green"></i> Date paiement

                                        <b style="color:green"><?php $paiement->m('date_debit'); ?></b>
                                    </li>
                                    <?php if ($decharge != null) {
                                        ?>
                                        <li>


                                            <i class="ace-icon fa fa-caret-right green"></i>Décharge :
                                            <a href="#" class="iframe_pdf" rel=<?php echo $decharge; ?>>
                                                <i class="ace-icon fa fa-print"></i>
                                            </a>


                                        </li>

                                        <?php
                                    }
                                    ?>

                                </ul>
                            </div>
                        </div><!-- /.col -->   

                        <div class="col-xs-12 col-sm-4"></div>


                    </div><!-- /.row -->

                </div><!-- /#home -->


            </div>
        </div>
    </div>
</div><!-- /.produit-profile -->
