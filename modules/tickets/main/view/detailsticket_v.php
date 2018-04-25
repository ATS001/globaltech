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

if($ticket->get_action_ticket()){
    $list_action = $ticket->list_action;
}else{
    $list_action = false;

}
    


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
                                                <i class="ace-icon fa fa-caret-right green"></i>Client :
                                                <b style="color:green"><?php $ticket->s("client") ?></b>
                                            </li>
                                            <?php if ($ticket->g("projet") != NULL) { ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Projet :
                                                    <b style="color:green"><?php $ticket->s("projet") ?></b>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date prévisionnelle :
                                                <b style="color:green"><?php $ticket->s("date_previs") ?></b>
                                            </li>
                                            <?php if ($ticket->g("date_realis") != NULL) { ?>
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

                                            <?php if ($ticket->g("prd") != NULL) { ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Produit :
                                                    <b style="color:green"><?php $ticket->s("prd") ?></b>
                                                </li>
                                            <?php } ?>

                                            <?php if ($ticket->g("technicien") != NULL) { ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Technicien :
                                                    <b style="color:green"><?php $ticket->s("technicien") ?></b>
                                                </li>                                            
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Date affectation :
                                                    <b style="color:green"><?php $ticket->s("date_affectation") ?></b>
                                                </li>
                                            <?php } ?>
                                                 <?php if ($ticket->g("code_cloture") != NULL AND $ticket->g("observation") ) { ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Décision :
                                                    <b style="color:green"><?php $ticket->s("code_cloture") ?></b>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Observation :
                                                    <b style="color:green"><?php $ticket->s("observation") ?></b>
                                                </li>
                                                <?php } ?>

                                        </ul>

                                                </div>

                                            </div><!-- /.col -->


                                            <div class="col-sm-8">
                                                <div>
                                                    <div class="space-6"></div>
                                                    <div class="sab">
                                                        <?php $ticket->s("message") ?>
                                                    </div>
                                                </div>
                                            </div><!-- /.col -->
                                        </div>

                                    </div><!-- /#home -->
                           
                            <?php  //if ($ticket->g("technicien") != NULL) { ?>
                            <div class="row">
                                <div id="timeline-1">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                            <!-- #section:pages/timeline -->
                                            
                                                
                                                <?php 
                                                        
                                                if(!$list_action){
                                                    echo "</br><b>Pas d'action en ce moment    </b>";
                                                }else{?>
                                                <div class="timeline-container">

                                                <?php foreach ($list_action as $key => $value) { ?>
                                                <div class="timeline-items cn_rmv<?php echo $value['id']?>">
                                                    <div class="timeline-item clearfix">
                                                        <div class="timeline-info">
                                                            <i class="timeline-indicator ace-icon fa fa-star btn btn-warning no-hover green"></i>
                                                        </div>

                                                        <div class="widget-box transparent">
                                                            <div class="widget-header widget-header-small">
                                                                <h5 class="widget-title smaller"><?php echo $value['nom']?></h5>

                                                                <span class="widget-toolbar no-border">
                                                                    <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                                                    <?php echo $value['date_action']?>
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
                                                                    <?php echo $value['message']?>
                                                                    <div class="space-6"></div>

                                                                    <div class="widget-toolbox clearfix">
                                                                        <div class="pull-right action-buttons">
                                                                            <div class="space-4"></div>

                                                                            <div>
                                                                                <?php 
                                                                                $etat1 = Msetting::get_set('etat_ticket', 'resolution_encours');
                                                                                if($ticket->g("etat")== $etat AND $value["etat"] == '0') { ?>
                                                                                <a href="#" class="this_url"  rel="editaction" data="<?php echo Minit::crypt_tp('id', $value['id'])?>">
                                                                                    <i class="ace-icon fa fa-pencil blue bigger-125"></i>
                                                                                </a>

                                                                                <a href="#" class="this_exec" cn_rmv="<?php echo $value['id']?>" rel="deleteactionticket" data="<?php echo Minit::crypt_tp('id', $value['id'])?>">
                                                                                    <i class="ace-icon fa fa-times red bigger-125"></i>
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
                                                <?php }//foreach
                                                }//End IF 
                                                
                                                if($ticket->g("etat") == 1){
TableTools::btn_add('addaction', 'Ajouter une action', MInit::crypt_tp('id',Mreq::tp('id')));
                                                }
                                                
                                                ?>
                                            
                                                                        

                                                    

                                                    
                                               
                                            </div><!-- /.timeline-container -->

                                            

                                            

                                            <!-- /section:pages/timeline -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php //} ?>
                        </div><!-- /#home -->
                        <div id="action" class="tab-pane in">
                            
                        </div><!-- /#home -->
                    </div><!-- /tab-content -->



                </div><!-- /#feed -->

            </div>
        </div>
    </div>

<?php

 if($ticket->g("etat") == 1){ 
$form = new Mform('resolution', 'resolution', '', 'tickets', '0', null);
$form->input_hidden('id',$ticket->g("id"));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

$decision[]= array("required", "true", "Veuillez choisir une décision ");
$form->select_table('Décision', 'code_cloture', 6, 'code_cloture', 'id', 'id', 'code_cloture', $indx = '------', 
                    $selected = NULL, $multi = NULL, $where = 'etat=0', $decision, NULL);

//$decision = array('Motif 1' => 'Motif 1' , 'Motif 2' => 'Motif 2' ,'Motif 3'=>'Motif 3');
//$form->select('Décision', 'decision', 2, $decision, $indx = NULL ,$selected = NULL, $multi = NULL);

$oserv[]= array("required", "true", "Veuillez saisir une observation ");
$form->input("Observation", "observation", "text" ,"9", null, $oserv, null, $readonly = null);

$form->button('Enregistrer');
//Form render
$form->render();
 }

?>
</div>

</div><!-- /.well -->


</div><!-- /.-profile -->

<script type="text/javascript">
    /*$('body').on('click', '.cn_rmv', function() {
     
     var $cosutm_noeud = $(this).attr('cn_rmv') != "" ? 'cn_rmv'+$(this).attr('cn_rmv') : "";

     $('.')
     
});*/
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