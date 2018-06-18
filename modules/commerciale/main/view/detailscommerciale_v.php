<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
$commerciale = new Mcommerciale();
$commerciale->id_commerciale = Mreq::tp('id');
$commerciale->get_commerciale();

$commission = new Mcommission();
$commission->id_commerciale = Mreq::tp('id');
$commission->get_list_commission_by_commerciale();
$commissions = $commission->commission_info;
$commission->get_list_paiement_by_commerciale();
$paiements=$commission->paiements_info;

//var_dump($paiements);

if (!MInit::crypt_tp('id', null, 'D') or !$commerciale->get_commerciale()) {
    // returne message error red to client
    exit('3#' . $commerciale->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

$pj = $commerciale->commerciale_info['pj'];
$photo = Minit::get_file_archive($commerciale->commerciale_info['photo']);
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
         TableTools::btn_action('commerciale', $commerciale->id_commerciale, 'detailscommerciale');
        TableTools::btn_add('commerciale', 'Liste commerciale', Null, $exec = NULL, 'reply');
        ?>
    </div>
</div>
<div class="page-header">
    <h1>
        Détails du commerciale: <?php $commerciale->s('id'); ?>

        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div>


<div class="row">
    <div class="col-xs-12">
        <div>
            <div id="user-profile-2" class="user-profile">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-18">
                        <li class="active">
                            <a data-toggle="tab" href="#home">
                                <i class="green ace-icon fa fa-installer bigger-120"></i>
                                Commerciale
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#feed1">
                                <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                Commissions
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#feed2">
                                <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                Paiements
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">

                                    <div>
                                        <h3 class="widget-title grey lighter">
                                            <?php if ($photo != null) {
                                                ?>


                                                <span class="profile-picture">
										    	<img width="200" height="300" class="editable img-responsive"
                                                     alt=<?php $commerciale->s('nom') ?> id="avatar2"
                                                     src="<?php echo $photo ?>"/>
									        </span>


                                                <?php
                                            }
                                            ?>
                                        </h3>
                                        <ul class="list-unstyled spaced">

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Nom :
                                                <b style="color:green"><?php $commerciale->s("nom") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Prénom :
                                                <b style="color:green"><?php $commerciale->s("prenom") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Interne :
                                                <b style="color:green"><?php $commerciale->s("is_glbt") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>CIN :
                                                <b style="color:green"><?php $commerciale->s("cin") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>RIB:
                                                <b style="color:green"><?php $commerciale->s("rib") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Téléphone :
                                                <b style="color:green"><?php $commerciale->s("tel") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>E-mail :
                                                <b style="color:green"><?php $commerciale->s("email") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Sexe :
                                                <b style="color:green"><?php $commerciale->s("sexe") ?></b>
                                            </li>
                                            <?php if ($pj != null) {
                                                ?>
                                                <li>


                                                    <i class="ace-icon fa fa-caret-right green"></i>Pièce :
                                                    <a href="#" class="iframe_pdf" rel=<?php echo $pj; ?>>
                                                        <i class="ace-icon fa fa-print"></i>
                                                    </a>


                                                </li>

                                                <?php
                                            }
                                            ?>

                                        </ul>
                                    </div>

                                </div><!-- /.col -->
                            </div>


                        </div><!-- /#home -->


                        <div id="feed1" class="tab-pane">

                            <div class="profile-info-row">
                                <div class="profile-info-value">
                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span>
                                    <?php
                                    if ($commissions == null)
                                    echo '<B>Aucune paiement trouvé</B> ';
                                    else {
                                    ?>
                                        <table class="table table-striped table-bordered table-hover"
                                               style="width: 820px">
                                            <th align="center" style="width: 20px">
                                                ID
                                            </th>
                                            <th style="width:300px">
                                                Objet
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Montant
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Payé
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Reste
                                            </th>
                                            <th align="center" style="width: 130px">
                                                Type
                                            </th>
                                            <?php
                                            foreach ($commissions as $cmpl) {
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
                                                        <?php if ($cmpl['5'] != null) { ?>
                                                            <span><?php echo $cmpl['5']; ?></span>
                                                        <?php } else echo '-'; ?>
                                                    </td>
                                                <td align="center" valign="top">
                                                        <?php if ($cmpl['16'] != null) { ?>
                                                            <span><?php echo $cmpl['16']; ?></span>
                                                        <?php } else echo '-'; ?>
                                                    </td>
                                                    <td align="center" valign="top">
                                                        <?php if ($cmpl['17'] != null) { ?>
                                                            <span><?php echo $cmpl['17']; ?></span>
                                                        <?php } else echo '-'; ?>

                                                    </td>
                                                    <td align="center" valign="top">
                                                        <?php if ($cmpl['7'] != null) { ?>
                                                            <span><?php echo $cmpl['7']; ?></span>
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


                        </div><!-- /#feed1 -->

                        <div id="feed2" class="tab-pane">

                            <div class="profile-info-row">
                                <div class="profile-info-value">
                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span>
                                    <?php
                                    if ($paiements == null)
                                    echo '<B>Aucune commission trouvée</B> ';
                                    else {
                                    ?>
                                        <table class="table table-striped table-bordered table-hover"
                                               style="width: 820px">
                                            <th align="center" style="width: 20px">
                                                ID
                                            </th>
                                            <th style="width:300px">
                                                Objet
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Montant
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Date paiement
                                            </th>
                                            <th align="center" style="width: 120px">
                                                Méthode de paiement
                                            </th>
                                            <th align="center" style="width: 130px">
                                                Justificatif
                                            </th>
                                            <?php
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


                        </div><!-- /#feed1 -->



                    </div><!-- /.row -->

                </div><!-- /#feed -->

            </div>
        </div>
    </div>
</div>

</div><!-- /.well -->


</div><!-- /.-profile -->

