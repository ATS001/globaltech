<?php
$uhf_vhf_clients_info = new Muhf_vhf_clients;
$uhf_vhf_clients_info->id_uhf_vhf_clients = Mreq::tp('id');
$uhf_vhf_clients_info->get_uhf_vhf_client_info();

$photo = Minit::get_file_archive($uhf_vhf_clients_info->uhf_vhf_clients_info['pj_images']);
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('uhf_vhf_stations', 'Liste des stations UHF/VHF', Null, $exec = NULL, 'reply'); ?>
        <?php
        TableTools::btn_add('uhf_vhf_clients', 'Liste des clients', MInit::crypt_tp('id', $uhf_vhf_clients_info->Shw('station_base', 1)), $exec = NULL, 'reply');
        ?>
    </div>
</div>
<div class="page-header">
    <h1>
        Consultation de la station de <?php $uhf_vhf_clients_info->printattribute('r_social'); ?> 
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
                            Station BLR 
                        </a>
                    </li>
                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">
                        <div class="row">
                            <div class="col-xs-12 col-sm-9">


                                <div class="profile-user-info">

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Site mère </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $uhf_vhf_clients_info->printattribute('site') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Longitude </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $uhf_vhf_clients_info->printattribute('longi') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Latitude </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $uhf_vhf_clients_info->printattribute('latit') ?></span>
                                        </div>
                                    </div>


                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Marque </div>

                                        <div class="profile-info-value">
                                            <span><?php $uhf_vhf_clients_info->printattribute('marque') ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Modèle </div>

                                        <div class="profile-info-value">
                                            <span><?php $uhf_vhf_clients_info->printattribute('modele') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Hauteur </div>

                                        <div class="profile-info-value">
                                            <span><?php $uhf_vhf_clients_info->printattribute('hauteur') ?></span>
                                        </div>
                                    </div>

                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                    </div><!-- /#home -->

                </div>
            </div>
        </div>

    </div>