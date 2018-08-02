<?php
$info_facture = new Mfacture();
$info_facture->id_facture = Mreq::tp('id');
$info_facture->get_facture_info();
$facture = $info_facture->facture_info;

$info_facture->get_client();
$client = $info_facture->client_info;

$info_facture->get_contrat($facture['idcontrat']);

$info_facture->get_complement_by_facture_modele();
$complements = $info_facture->complement_info;

$info_facture->get_all_encaissements();
$encaissements = $info_facture->encaissement_info;
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_action('factures', $info_facture->id_facture, 'detailsfacture');
        TableTools::btn_add('factures', 'Liste des factures', NULL, $exec = NULL, 'reply');
        ?>        

    </div>
</div>
<div class="page-header">
    <h1>
        Détails de la facture : <?php $info_facture->printattribute_fact('reference'); ?> 

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
                            Facture 
                        </a>

                    </li>

                    <li>
                        <a data-toggle="tab" href="#feed">
                            <i class="red ace-icon fa fa-adjust bigger-120"></i>
                            Compléments
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#feed1">
                            <i class="blue ace-icon fa fa-inbox bigger-120"></i>
                            Encaissements
                        </a>
                    </li>
                    <div class="widget-toolbar hidden-480">
                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'facture') ?>" data="<?php echo MInit::crypt_tp('id', $info_facture->id_facture) ?>">
                            <i class="ace-icon fa fa-print"></i>
                        </a>
                    </div> 

                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">

                        <div class="row">
                            <div class="col-sm-4">

                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                        <b>Facture Info</b> 

                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i> Référence                                                                                               
                                            <b class="blue pull-right"> <?php $info_facture->printattribute_fact('reference'); ?> </b>                                                                                                          


                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i> Date facture
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('date_facture'); ?>  </b>                                                                                                              

                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i> Période facturée
                                            <b class="blue pull-right"><?php
        if ($info_facture->printattribute_fact('du') != null and $info_facture->printattribute_fact('au') != null)
            $info_facture->printattribute_fact('du');
        ?> <b>Au</b> <?php $info_facture->printattribute_fact('au'); ?></b> 
                                        </li>											

                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Total HT
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('total_ht'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            TVA
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('tva'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Totale TVA
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('total_tva'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Total TTC
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('total_ttc'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Total payé
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('total_paye'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right blue"></i>
                                            Reste
                                            <b class="blue pull-right"><?php $info_facture->printattribute_fact('reste'); ?></b>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- /.col -->

                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                        <b>Client Info</b>
                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled  spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Réference
                                            <b class="blue pull-right"><?php $info_facture->printattribute_clt('reference'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Dénomination
                                            <b class="blue pull-right"><?php $info_facture->printattribute_clt('denomination'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>Raison social
                                            <b class="blue pull-right"><?php $info_facture->printattribute_clt('r_social'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Registe de commerce
                                            <b class="blue pull-right"><?php echo $info_facture->printattribute_clt('r_commerce'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            NIF
                                            <b class="blue pull-right"><?php echo $info_facture->printattribute_clt('nif'); ?></b>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- /.col -->

                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-yellow arrowed-in arrowed-right">
                                        <b>Contrat Info</b>
                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled  spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right yellow"></i> Référence
                                            <b class="blue pull-right"><?php $info_facture->printattribute_ctr('reference'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right yellow"></i>
                                            Date contrat
                                            <b class="blue pull-right"><?php echo $info_facture->printattribute_ctr('date_contrat'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right yellow"></i> Daté d'effet
                                            <b class="blue pull-right"><?php $info_facture->printattribute_ctr('date_effet'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right yellow"></i>Date fin
                                            <b class="blue pull-right"><?php $info_facture->printattribute_ctr('date_fin'); ?></b>
                                        </li>                                        

                                    </ul>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /#home -->


                    <div id="feed" class="tab-pane">
                        <div class="profile-info-row">
                            <div class="profile-info-value">
           <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                <span>
                                    <?php
                                    if ($complements == null)
                                        echo '<B>Aucun complément trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px">
                                            <th>
                                                ID
                                            </th>
                                            <th>
                                                Désignation
                                            </th>
                                            <th>
                                                Type
                                            </th>
                                            <th>
                                                Montant
                                            </th>
                                            <?php
//                                                                                         if($complements == null)
//                                                                                             echo '<B>Aucun enregistrement trouvé</B> ';
//                                                                                         else {
                                            foreach ($complements as $cmpl) {
                                                ?>
                                                <tr>	
                                                    <td>
                                                        <span><?php echo $cmpl['id']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $cmpl['designation']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $cmpl['type']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $cmpl['montant']; ?></span>
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


                    </div><!-- /#feed -->


                    <div id="feed1" class="tab-pane">
                        <div class="profile-info-row">
                            <div class="profile-info-value">
           <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                <span>
                                    <?php
                                    if ($encaissements == null)
                                        echo '<B>Aucun encaissement trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px">
                                            <th>
                                                ID
                                            </th>
                                            <th>
                                                Référence
                                            </th>
                                            <th style="text-align: right;">
                                                Montant
                                            </th>
                                            <th style="text-align: center;">
                                                Date
                                            </th>
                                            <th style="text-align: center;">
                                                Document
                                            </th>
                                            <?php foreach ($encaissements as $encs) { ?>
                                                <tr>	
                                                    <td>
                                                        <span><?php echo $encs['id']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $encs['reference']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $encs['montant']; ?></span>
                                                    </td>
                                                    <td align="center">
                                                        <span><?php echo $encs['date_encaissement']; ?></span>
                                                    </td>
                                                    <td align="center" valign="top">
                                                        <?php if ($encs['pj'] != null) { ?>
                                                            <a href="#" class="iframe_pdf" rel=<?php echo $encs['pj']; ?>>
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


                    </div><!-- /#feed -->




                </div>
            </div>
        </div>





    </div><!-- /.-profile -->
