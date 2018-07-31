<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 21-04-2018
//View
//Get all tickets info 
$info_tickets = new Mticket_frs();
//Set ID of Module with POST id
$info_tickets->id_action_ticket = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_tickets->get_ticket_action()) {
    // returne message error red to client 
    exit('3#' . $info_tickets->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
$id_ticket = $info_tickets->ticket_action_info["id_ticket_frs"];
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_add('detailsticket_frs', 'Retour', MInit::crypt_tp('id', $id_ticket), $exec = NULL, 'reply');
        ?>		
    </div>
</div>
<div class="page-header">
    <h1>
        Détails de l'action:     <?php $info_tickets->sa('id'); ?> 

        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="clearfix">

        </div>
        <div class="table-header">
            Page: "<?php echo ACTIV_APP; ?>"
        </div>
        <div class="widget-content">
            <div class="widget-box">
                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <div id="user-profile-2" class="user-profile">


                                <div class="tab-content no-border padding-24">
                                    <div id="home" class="tab-pane in active">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div>
                                                    <ul class="list-unstyled spaced">                                                    
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Ticket :
                                                            <b style="color:green"><?php $info_tickets->sa("id_ticket_frs") ?></b>
                                                        </li> 
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Date action :
                                                            <b style="color:green"><?php $info_tickets->sa("date_act") ?></b>
                                                        </li>
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Pièce jointe :
                                                            <a href="#" class="iframe_pdf" rel=<?php $info_tickets->sa("pj") ?> >
                                                                <i class="ace-icon fa fa-file-pdf-o"></i>
                                                            </a>
                                                        </li> 
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Photo :
                                                            <a href="#" class="iframe_pdf" rel=<?php $info_tickets->sa("photo") ?> >
                                                                <i class="ace-icon fa fa-search show_file"></i>
                                                            </a>
                                                        </li> 


                                                        <div class="col-sm-8">
                                                            <div>
                                                                <div class="space-6"></div>
                                                                <div class="sa">
                                                                    <?php $info_tickets->sa("message") ?>
                                                                </div>
                                                            </div>
                                                        </div><!-- /.col -->                                                  

                                                    </ul>

                                                </div>

                                            </div><!-- /.col -->



                                        </div>

                                    </div><!-- /#home -->


                                </div><!-- /.row -->



                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>



</div><!-- /.well -->
</div><!-- /.-profile -->

<style>
    .sa {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #DEE4EA;
        border: 2px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
    }

</style>