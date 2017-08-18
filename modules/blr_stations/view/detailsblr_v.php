<?php
$blr_stations_info = new Mblr_stations;

$blr_stations_info->id_blr_stations = Mreq::tp('id');
$blr_stations_info->get_blr_stations_info();

$photo = Minit::get_file_archive($blr_stations_info->blr_stations_info['pj_images']);
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('blr_stations', 'Liste des stations BLR', Null, $exec = NULL, 'reply'); ?>
        <?php TableTools::btn_add('blr_clients', 'Liste des clients', MInit::crypt_tp('id', Mreq::tp('id')), $exec = NULL, 'reply'); ?>


    </div>
</div>
<div class="page-header">
    <h1>
        Consultation de la station BLR

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
                                Station BLR
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
                                            <div class="profile-info-name"> Permissionnaire </div>

                                            <div class="profile-info-value">
                                                <span><?php $blr_stations_info->printattribute('r_social') ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> site </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $blr_stations_info->printattribute('site') ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Longitude </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $blr_stations_info->printattribute('longi') ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Latitude </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $blr_stations_info->printattribute('latit') ?></span>
                                            </div>
                                        </div>


                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Ville </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $blr_stations_info->printattribute('ville') ?></span>
                                            </div>
                                        </div>	
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Marque </div>

                                            <div class="profile-info-value">
                                                <span><?php $blr_stations_info->printattribute('marque') ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Modèle </div>

                                            <div class="profile-info-value">
                                                <span><?php $blr_stations_info->printattribute('modele') ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> N°de série </div>

                                            <div class="profile-info-value">
                                                <span><?php $blr_stations_info->printattribute('num_serie') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.col -->

                                <div class="col-xs-12 col-sm-6">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Hauteur </div>

                                        <div class="profile-info-value">
                                            <span><?php $blr_stations_info->printattribute('hauteur') ?></span>
                                        </div>
                                    </div>


                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Puissance </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_stations_info->printattribute('puissance') ?></span>
                                        </div>
                                    </div>	

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Fréquence </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_stations_info->printattribute('frequence') ?></span>
                                        </div>
                                    </div>	

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Modulation </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_stations_info->printattribute('modulation') ?></span>
                                        </div>
                                    </div>	

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Nombre de clients </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_stations_info->printattribute('nbr_clients') ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Type de station</div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $blr_stations_info->printattribute('type_station') ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Date de visite</div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php date( "Y-m-d", strtotime( $blr_stations_info->printattribute('date_visite') ));?></span>
                                        </div>
                                    </div>


                                </div><!-- /.col -->

                            </div><!-- /.row -->

                            <div class="center">
                                <a class="iframe_pdf" rel="<?php $blr_stations_info->printattribute('pj') ?>">
                                    <p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>Formulaire de : <?php $blr_stations_info->printattribute('site') ?> </p>
                                </a>							
                            </div>

                        </div><!-- /#home -->

                        <div id="feed" class="tab-pane">
                            <?php echo MInit::get_pictures_gallery($blr_stations_info->Shw('pj_images', 1), true); ?>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div><!-- /.well -->


</div><!-- /.user-profile -->
