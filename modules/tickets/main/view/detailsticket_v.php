<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
$ticket = new Mtickets;
$ticket->id_tickets = Mreq::tp('id');
$ticket->get_tickets();

/*
  $commission = new Mcommission();
  $commission->id_commerciale = Mreq::tp('id');
  $commission->get_list_commission_by_commerciale();
  $commissions = $commission->commission_info;
  $commission->get_list_paiement_by_commerciale();
  $paiements=$commission->paiements_info;
 */
//var_dump($paiements);

if (!MInit::crypt_tp('id', null, 'D') or ! $ticket->get_tickets()) {
    // returne message error red to client
    exit('3#' . $ticket->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}
//var_dump($ticket->tickets_info);
//$pj = $commerciale->commerciale_info['pj'];
//$photo = Minit::get_file_archive($commerciale->commerciale_info['photo']);
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_add('tickets', 'Liste des tickets', Null, $exec = NULL, 'reply');
        ?>
    </div>
</div>
<div class="page-header">
    <h1>
        Détails du ticket: <?php $ticket->s('id'); ?>

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
                                Ticket
                            </a>
                        </li>
                        <!--                        <li>
                                                    <a data-toggle="tab" href="#feed1">
                                                        <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                                        Commissions
                                                    </a>
                                                </li>-->
                        <!--                        <li>
                                                    <a data-toggle="tab" href="#feed2">
                                                        <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                                        Paiements
                                                    </a>
                                                </li>-->
                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">

                                    <div>
                                        <ul class="list-unstyled spaced">

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Client :
                                                <b style="color:green"><?php $ticket->s("client") ?></b>
                                            </li>
                                            <?php   if ($ticket->g("projet") != NULL) { ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Projet :
                                                <b style="color:green"><?php $ticket->s("projet") ?></b>
                                            </li>
                                             <?php } ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date prévisionnelle :
                                                <b style="color:green"><?php $ticket->s("date_previs") ?></b>
                                            </li>
                                            <?php   if ($ticket->g("date_realis") != NULL) { ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date_realisation :
                                                <b style="color:green"><?php $ticket->s("date_realis") ?></b>
                                            </li>
                                            <?php } ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Type produit:
                                                <b style="color:green"><?php $ticket->s("typep") ?></b>
                                            </li>
                                            
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Catégorie produit :
                                                <b style="color:green"><?php $ticket->s("categorie_produit") ?></b>
                                            </li>
                                            
                                            <?php   if ($ticket->g("prd") != NULL) { ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Produit :
                                                <b style="color:green"><?php $ticket->s("prd") ?></b>
                                            </li>
                                            <?php  } ?>
                                            
                                            <?php   if ($ticket->g("technicien") != NULL) { ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Technicien :
                                                <b style="color:green"><?php $ticket->s("technicien") ?></b>
                                            </li>                                            
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date affectation :
                                                <b style="color:green"><?php $ticket->s("date_affectation") ?></b>
                                            </li>
                                            <?php  } ?>
                                            
                                        </ul>
                                    </div>

                                </div><!-- /.col -->
                            </div>


                        </div><!-- /#home -->


                    </div><!-- /.row -->

                </div><!-- /#feed -->

            </div>
        </div>
    </div>
</div>

</div><!-- /.well -->


</div><!-- /.-profile -->

