<?php
$prm_info = new Mprms();

$prm_info->id_prm = Mreq::tp('id');
$prm_info->get_prm();

$photo = Minit::get_file_archive($prm_info->prm_info['logo']);
?>
<div class="page-header">
    <h1>
        Page de profil de <?php $prm_info->printattribute('sigle') ?>
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div>
<!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="hr dotted"></div>

        <div>
            <div id="prm-profile-2" class="prm-profile">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-18">
                        <li class="active">
                            <a data-toggle="tab" href="#home">
                                <i class="green ace-icon fa fa-prm bigger-120"></i>
                                Profil 
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">
                                <div class="col-xs-12 col-sm-3 center">
                                    <span class="profile-picture">
                                        <img width="180" height="200" class="editable img-responsive" alt="Alex's Avatar" id="avatar2" src="<?php echo $photo ?>" />
                                    </span>

                                    <div class="space space-4"></div>

                                </div><!-- /.col -->

                                <div class="col-xs-12 col-sm-9">
                                    <div class="profile-prm-info">
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Sigle </div>

                                            <div class="profile-info-value">
                                                <span><?php $prm_info->printattribute('sigle'); ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Raison sociale </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $prm_info->printattribute('r_social'); ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Catégorie </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $prm_info->printattribute('categorie'); ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Sécteur d'activité </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $prm_info->printattribute('secteur_activ'); ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Registre de commerce </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $prm_info->printattribute('rc'); ?></span>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> NIF </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('nif'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Multi-nationale </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('multi_national'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        
                                         <div class="profile-info-row">
                                            <div class="profile-info-name"> Pays du siège </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('pay_siege'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                         <div class="profile-info-row">
                                            <div class="profile-info-name"> Adresse </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('adresse'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Boite postale </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('bp'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Ville </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('ville'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> E-mail </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('email'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Téléphone </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('tel'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Fax </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('fax'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Nom personne physique </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('nom_p'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Qualité du représentant </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('qualite_p'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Nationnalité </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('nation_p'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                         <div class="profile-info-row">
                                            <div class="profile-info-name"> Adresse personne physique </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('adresse_p'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                         <div class="profile-info-row">
                                            <div class="profile-info-name"> E-mail représentant </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('email_p'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Tél du représentant </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span>
                                                    <?php $prm_info->printattribute('tel_p'); ?>
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


        </div><!-- /.well -->


    </div><!-- /.prm-profile -->
