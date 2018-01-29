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
                           data="<?php  MInit::crypt_tp('id', $paiement->id_commission)?>">
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
                                    echo '<B>Aucun paiement trouvé</B> ';
                                    else {
                                    ?>
                                    <table class="table table-striped table-bordered table-hover" style="width: 820px">
                                            <th align="center" style="width: 20px">
                                                ID
                                            </th>
                                            <th style="width:300px">
                                                Objet
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Montant
                                            </th><th align="center" style="width: 130px">
                                                Date de paiement
                                            </th>
                                        <th align="center" style="width: 160px">
                                                Méthode de payement
                                            </th>
                                        <th width="50px">
                                            Justificatif
                                        </th>

                                        <?php
                                        //                                                                                         if($complements == null)
                                        //                                                                                             echo '<B>Aucun enregistrement trouvé</B> ';
                                        //
                                        //                                                                                         else {
                                        foreach ($paiements as $cmpl) {
                                            ?>
                                            <tr>
                                                    <td align="center" valign="top">
                                                  <?php if ($cmpl['0'] != null) { ?>
                                                      <span><?php echo $cmpl['0']; ?></span>
                                                  <?php } else echo '-'; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($cmpl['2'] != null) { ?>
                                                            <span><?php echo $cmpl['2']; ?></span>
                                                        <?php } else echo '-'; ?>
                                                    </td>
                                                    <td align="right" valign="top">
                                                        <?php if ($cmpl['6'] != null) { ?>
                                                            <span><?php echo $cmpl['6']; ?></span>
                                                        <?php } else echo '-'; ?>
                                                    </td>
                                                <td align="center" valign="top">
                                                        <?php if ($cmpl['16'] != null) { ?>
                                                            <span><?php echo $cmpl['16']; ?></span>
                                                        <?php } else echo '-'; ?>
                                                    </td>
                                                    <td align="center" valign="top">
                                                        <?php if ($cmpl['9'] != null) { ?>
                                                            <span><?php echo $cmpl['9']; ?></span>
                                                        <?php } else echo '-'; ?>

                                                    </td>
                                                <td align="center" valign="top">
                                                    <?php if ($cmpl['10'] != null) { ?>
                                                        <a href="#" class="iframe_pdf" rel=<?php echo $cmpl['10']; ?>>
                                                <i style="display: block;margin-left:auto;margin-right:auto"
                                                   class="ace-icon fa fa-print"></i>
                                                </a>
                                                    <?php } else echo '-'; ?>
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
                                    <ul class="list-unstyled spaced" style="width: 500px">
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
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Type
                                            <b style="color:blue"><?php $paiement->f('Type'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i> Montant
                                            <b style="color:blue"><?php $paiement->f('credit'); ?>  </b>

                                       </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Payé
                                            <b style="color:blue"><?php $paiement->f('paye'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Reste
                                            <b style="color:blue"><?php $paiement->f('reste'); ?></b>
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
