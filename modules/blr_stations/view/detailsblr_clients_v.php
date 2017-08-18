<?php
$blr_clients_info = new Mblr_clients;
$blr_clients_info->id_blr_clients = Mreq::tp('id');
$blr_clients_info->get_blr_client_info();

$photo = Minit::get_file_archive($blr_clients_info->blr_clients_info['pj_images']);
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('blr_stations', 'Liste des stations BLR', Null, $exec = NULL, 'reply');
        TableTools::btn_add('blr_clients', 'Liste des clients', MInit::crypt_tp('id', $blr_clients_info->Shw('station_base', 1)), $exec = NULL, 'reply');
        ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Consultation de la station

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
                                <i class="green ace-icon fa fa-gsm bigger-120"></i>
                                Client BLR
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#feed">
                                <i class="red ace-icon fa fa-camera bigger-120"></i>
                                Photos de la station
                            </a>
                        </li>


                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">

                                <div class="col-xs-12 col-sm-6">
                                    
                                    <div class="profile-user-info">
                                        
                                        <div class="profile-info-row">
                                        <div class="profile-info-name"> Site mère </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_clients_info->printattribute('site') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Longitude </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_clients_info->printattribute('longi') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Latitude </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_clients_info->printattribute('latit') ?></span>
                                        </div>
                                    </div>


                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Marque </div>

                                        <div class="profile-info-value">
                                            <span><?php $blr_clients_info->printattribute('marque') ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Modèle </div>

                                        <div class="profile-info-value">
                                            <span><?php $blr_clients_info->printattribute('modele') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Hauteur </div>

                                        <div class="profile-info-value">
                                            <span><?php $blr_clients_info->printattribute('hauteur') ?></span>
                                        </div>
                                    </div>
                                        
                                    </div>
                                </div><!-- /.col -->
                                   
                                                        
                                </div><!-- /.row -->

                           
                            </div><!-- /#home -->

                            <div id="feed" class="tab-pane">
                                <?php echo MInit::get_pictures_gallery($blr_clients_info->Shw('pj_images', 1), true); ?>
                            </div>

                        </div>

                    </div>
                </div>


            </div><!-- /.well -->


        </div><!-- /.user-profile -->
