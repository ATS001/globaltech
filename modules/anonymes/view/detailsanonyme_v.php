<?php
$anonyme_info = new Manonyme();

$anonyme_info->id_anonyme = Mreq::tp('id');
$anonyme_info->get_anonyme();

$photo = Minit::get_file_archive($anonyme_info->anonyme_info['pj_images']);
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('anonymes', 'Liste des anonymes', Null, $exec = NULL, 'reply');
        ?>


    </div>
</div>
<div class="page-header">
    <h1>
        Consultation anonyme

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
                            Station Anonyme
                        </a>
                    </li>

                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">

                        <div class="col-xs-12 col-sm-9">
                            <div class="profile-anonyme-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Titre </div>

                                    <div class="profile-info-value">
                                        <span><?php $anonyme_info->printattribute('titre'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Longitude </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $anonyme_info->printattribute('longi'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Latitude </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $anonyme_info->printattribute('latit'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Technologie </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $anonyme_info->printattribute('technologie'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Date de visite </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $anonyme_info->printattribute('date_visite'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Remarques </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span>
                                            <textarea rows="4" cols="50" disabled="disable">
                                                <?php
                                                $anonyme_info->printattribute('remarque');
                                                ?>
                                            </textarea>
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div><!-- /.col -->
                    </div><!-- /.row -->

                </div><!-- /#home -->


            </div>
        </div>
    </div>





</div><!-- /.anonyme-profile -->
