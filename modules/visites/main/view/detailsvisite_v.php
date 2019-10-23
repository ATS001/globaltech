<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//View
$visites = new Mvisites();
$visites->id_visites = Mreq::tp('id');
$visites->get_visites_infos();

?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_add('visites', 'Liste visites', Null, $exec = NULL, 'reply');
        ?>		
    </div>
</div>
<div class="page-header">
    <h1>
        Détails du visites:     <?php $visites->s('id'); ?> 

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
                                visites 
                            </a>
                        </li>


                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    
                                    <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                        <b>Visite Info</b>
                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled  spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Commerciale
                                            <b class="blue pull-right"><?php $visites->s('com'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Raison sociale
                                            <b class="blue pull-right"><?php $visites->s('raison_sociale'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Nature visite
                                            <b class="blue pull-right"><?php $visites->s('nature_visite'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Objet visite
                                            <b class="blue pull-right"><?php $visites->s('objet_visite'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Date visite
                                            <b class="blue pull-right"><?php $visites->s('date_visite'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Interlocuteur
                                            <b class="blue pull-right"><?php $visites->s('interlocuteur'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Fonction interlocuteur
                                            <b class="blue pull-right"><?php $visites->s('fonction_interloc'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Coordonées interlocuteur
                                            <b class="blue pull-right"><?php $visites->s('coordonees_interloc'); ?></b>
                                        </li> 
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Commentaire
                                            <b class="blue pull-right"><?php $visites->s('commentaire'); ?></b>
                                        </li> 
                                    </ul>
                                </div>
                            </div><!-- /.col -->
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>