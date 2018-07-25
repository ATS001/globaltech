<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
$ticket = new Mticket_frs();
$ticket->id_tickets = Mreq::tp('id');
$ticket->get_ticket_frs();

if (!MInit::crypt_tp('id', null, 'D') or ! $ticket->get_ticket_frs()) {
    // returne message error red to client
    exit('3#' . $ticket->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

if ($ticket->get_action_ticket()) {
    $list_action = $ticket->list_action;
} else {
    $list_action = false;
}
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_action('ticket_frs', $ticket->id_tickets, 'detailsticket_frs');
        TableTools::btn_add('ticket_frs', 'Liste des tickets', Null, $exec = NULL, 'reply');
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
                                <i class="green ace-icon fa fa-bookmark bigger-120"></i>
                                Ticket
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">
                                <div class="col-sm-4">

                                    <div>
                                        <ul class="list-unstyled ">

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Fournisseur :
                                                <b class="blue pull-right"><?php $ticket->s("fournisseur") ?></b>      
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date de création :
                                                <b class="blue pull-right"><?php $ticket->s("credat") ?></b>
                                            </li>



                                            <?php if ($ticket->g("technicien") != NULL) { ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Technicien :
                                                    <b class="blue pull-right"><?php $ticket->s("technicien") ?></b>
                                                </li>                                            
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Date affectation :
                                                    <b class="blue pull-right"><?php $ticket->s("date_affectation") ?></b>
                                                </li>
                                            <?php } ?>
                                            <?php if ($ticket->g("code_cloture") != NULL AND $ticket->g("observation")) { ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Code clôture :
                                                    <b class="blue pull-right"><?php $ticket->s("code_cloture") ?></b>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Résolution :
                                                    <b class="blue pull-right"><?php $ticket->s("observation") ?></b>
                                                </li>
                                            <?php } ?>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date incident :
                                                <b class="blue pull-right"><?php $ticket->s("date_incident") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Nature incident :
                                                <b class="blue pull-right"><?php
                                                    if ($ticket->g("nature_incident") == "Autres")
                                                        $ticket->s("autre_nt");
                                                    else {
                                                        $ticket->s("nature_incident");
                                                    }
                                                    ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>PEC Fournisseur :
                                                <b class="blue pull-right"><?php
                                                    if ($ticket->g("prise_charge_frs") == "Autres")
                                                        $ticket->s("autre_pecf");
                                                    else {
                                                        $ticket->s("prise_charge_frs");
                                                    }
                                                    ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>PEC Globaltech :
                                                <b class="blue pull-right"><?php
                                                    if ($ticket->g("prise_charge_glbt") == "Autres")
                                                        $ticket->s("autre_pecg");
                                                    else {
                                                        $ticket->s("prise_charge_glbt");
                                                    }
                                                    ?></b>
                                            </li>

                                        </ul>

                                    </div>

                                </div><!-- /.col -->


                                <div class="col-sm-8">
                                    <div>
                                        <div class="space-6"></div>
                                        <div class="sab">
                                            <?php $ticket->s("description") ?>
                                        </div>
                                    </div>
                                </div><!-- /.col -->
                            </div>

                        </div><!-- /#home -->

                        <?php //if ($ticket->g("technicien") != NULL) {   ?>
                        <div class="row">
                            <div id="timeline-1">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                        <!-- #section:pages/timeline -->


                                        <?php
                                        if (!$list_action) {
                                            echo "</br><b>Pas d'action en ce moment    </b>";
                                        } else {
                                            ?>
                                            <div class="timeline-container">

                                                <?php foreach ($list_action as $key => $value) { ?>
                                                    <div class="timeline-items cn_rmv<?php echo $value['id'] ?>">
                                                        <div class="timeline-item clearfix">
                                                            <div class="timeline-info">
                                                                <i class="timeline-indicator ace-icon fa fa-star btn btn-warning no-hover green"></i>
                                                            </div>

                                                            <div class="widget-box transparent">
                                                                <div class="widget-header widget-header-small">
                                                                    <h5 class="widget-title smaller"><?php echo $value['nom'] ?></h5>

                                                                    <span class="widget-toolbar no-border">
                                                                        <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                                                        <?php echo $value['date_action'] ?>
                                                                    </span>

                                                                    <span class="widget-toolbar">
                                                                        <!-- <a href="#" data-action="reload">
                                                                            <i class="ace-icon fa fa-refresh"></i>
                                                                        </a> -->

                                                                        <a href="#" data-action="collapse">
                                                                            <i class="ace-icon fa fa-chevron-up"></i>
                                                                        </a>
                                                                    </span>
                                                                </div>

                                                                <div class="widget-body">
                                                                    <div class="widget-main">
                                                                        <?php echo $value['message'] ?>
                                                                        <div class="space-6"></div>

                                                                        <div class="widget-toolbox clearfix">
                                                                            <div class="pull-right action-buttons">
                                                                                <div class="space-4"></div>

                                                                                <div>
                                                                                    <?php
                                                                                    $etat1 = Msetting::get_set('etat_ticket', 'resolution_encours');
                                                                                    if ($ticket->g("etat") == $etat1 AND $value["etat"] == '0' and session::get('userid') == $value["creusr"]) {
                                                                                        ?>
                                                                                        <a href="#" class="this_url"  rel="editaction_frs" data="<?php echo Minit::crypt_tp('id', $value['id']) ?>">
                                                                                            <i class="ace-icon fa fa-pencil blue bigger-125"></i>
                                                                                        </a>

                                                                                        <a href="#" class="this_exec" cn_rmv="<?php echo $value['id'] ?>" rel="deleteaction_frs" data="<?php echo Minit::crypt_tp('id', $value['id']) ?>">
                                                                                            <i class="ace-icon fa fa-times red bigger-125"></i>
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                    <?php if ($value["etat"] == '0') { ?>
                                                                                        <a href="#" class="this_url"  rel="detailsaction_frs" data="<?php echo Minit::crypt_tp('id', $value['id']) ?>">
                                                                                            <i class="ace-icon fa fa-search  blue bigger-125"></i>
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.timeline-items -->
                                                    <?php
                                                }//foreach
                                            }//End IF 

                                            if ($ticket->g("etat") == 1) {
                                                TableTools::btn_add('addaction', 'Ajouter une action', MInit::crypt_tp('id', Mreq::tp('id')));
                                            }
                                            ?>

                                        </div><!-- /.timeline-container -->


                                        <!-- /section:pages/timeline -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php //}   ?>
                    </div><!-- /#home -->
                    <div id="action" class="tab-pane in">

                    </div><!-- /#home -->
                </div><!-- /tab-content -->



            </div><!-- /#feed -->

        </div>
    </div>
</div>

<?php
if ($ticket->g("etat") == 1) {
    $form = new Mform('resolution_frs', 'resolution_frs', '', 'ticket_frs', '0', null);
    $form->input_hidden('id', $ticket->g("id"));
    $form->input_hidden('idc', Mreq::tp('idc'));
    $form->input_hidden('idh', Mreq::tp('idh'));

    $decision[] = array("required", "true", "Veuillez choisir le code de clôture ");
    $form->select_table('Code clôture', 'code_cloture', 6, 'code_cloture', 'id', 'id', 'code_cloture', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=0', $decision, NULL);


    $array_message[] = array("required", "true", "Veuillez insérer le détails de résolution ");
    $form->input_editor('Résolution', 'observation', 8, NULL, $array_message, $input_height = 100);

    $form->button('Clôturer ticket');
//Form render
    $form->render();
}
?>
</div>

</div><!-- /.well -->


</div><!-- /.-profile -->

<script type="text/javascript">

</script>
<style>
    .sab {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #e3e3e3;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
    }

</style>