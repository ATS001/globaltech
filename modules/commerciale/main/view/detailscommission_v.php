<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 10-01-2018
//View
$paiement = new Mcommission();

$paiement->id_commission = Mreq::tp('id');
$paiement->get_commission_info();
$paiement2 = $paiement->details_commission_info;

$paiement->get_commission();
$id_commerciale = $paiement->commission_info['id_commerciale'];
$paiement->get_paiement();
$paiements = $paiement->commission_info;

?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php TableTools::btn_add('commissions', 'Liste des commissions', MInit::crypt_tp('id', $id_commerciale), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Détails commission: <?php echo $paiement->id_commission; ?>

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
                            Paiements
                        </a>

                    </li>
                    <li>
                        <a data-toggle="tab" href="#feed">
                            <i class="red ace-icon fa fa-adjust bigger-120"></i>
                            Commission
                        </a>
                    </li>


                    <div class="widget-toolbar hidden-480">
                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'decharge') ?>"
                           data="<?php //echo MInit::crypt_tp('id', $info_facture->id_facture)?>">
                            <i class="ace-icon fa fa-print"></i>
                        </a>
                    </div>

                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">

                        <div class="profile-info-row">
                            <div class="profile-info-value">
                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                <span>
                                    <?php
                                    if ($paiements == null)
                                    echo '<B>Aucun complément trouvé</B> ';
                                    else {
                                    ?>
                                    <table class="table table-striped table-bordered table-hover" style="width: 800px">
                                            <th>
                                                ID
                                            </th>
                                            <th>
                                                Objet
                                            </th>
                                            <th>
                                                Montant
                                            </th>
                                        <th>
                                                Méthode de payement
                                            </th>

                                        <?php
                                        //                                                                                         if($complements == null)
                                        //                                                                                             echo '<B>Aucun enregistrement trouvé</B> ';
                                        //                                                                                         else {
                                        foreach ($paiements as $cmpl) {
                                            ?>
                                            <tr>
                                                    <td>
                                                        <span><?php echo $cmpl['0']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $cmpl['2']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $cmpl['5']; ?></span>
                                                    </td>
                                                <td>
                                                        <span><?php echo $cmpl['8']; ?></span>
                                                    </td>

                                                </tr>


                                            <?php
                                        }
                                        }
                                        ?>

                                    </table>
                                </span>
                            </div>
                        </div>


                    </div><!-- /#home -->

                    <div id="feed" class="tab-pane">

                        <div class="row">
                            <div class="col-sm-4">

                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                        <b>Commission Info</b>

                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i> Commerciale
                                            <b style="color:blue"> <?php $paiement->f('commerciale'); ?> </b>

                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Objet
                                            <b style="color:blue"><?php $paiement->f('objet'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i> Montant
                                            <b style="color:blue"><?php $paiement->f('credit'); ?>  </b>

                                        </li>


                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Type
                                            <b style="color:blue"><?php $paiement->f('Type'); ?></b>
                                        </li>


                                    </ul>
                                </div>
                            </div><!-- /.col -->

                        </div><!-- /.row -->
                    </div><!-- /#feed -->
                </div>
            </div>
        </div>


    </div><!-- /.-profile -->
