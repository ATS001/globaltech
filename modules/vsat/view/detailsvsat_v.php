<?php
$vsat_info = new Mvsat;

$vsat_info->id_vsat = Mreq::tp('id');
$vsat_info->get_vsat_info();


$photo = Minit::get_file_archive($vsat_info->vsat_info['pj_images']);
$formulaire = Minit::get_file_archive($vsat_info->vsat_info['pj']);
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('VSAT', 'Liste des stations VSAT', Null, $exec = NULL, 'reply');
        ?>

    </div>
</div>


<div class="page-header">
    <h1>
        Consultation de la station VSAT

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
                                Infos Site
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#feed">
                                <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                HUB & Opérateurs
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#feed1">
                                <i class="blue ace-icon fa  fa-key bigger-120"></i>
                                Infos Techniques
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#feed2">
                                <i class="orange ace-icon fa  fa-arrow-circle-o-down  bigger-120"></i>
                                Config Antenne

                            </a>
                        </li>

                        <li>
                            <a data-toggle="tab" href="#feed3">
                                <i class="yellow ace-icon fa 	fa-exchange  bigger-120"></i>
                                Config Radio

                            </a>
                        </li>

                        <li>
                            <a data-toggle="tab" href="#feed4">
                                <i class="blue ace-icon fa fa-inbox bigger-120"></i>
                                Config Modem

                            </a>
                        </li>

                        <li>
                            <a data-toggle="tab" href="#feed5">
                                <i class="orange ace-icon fa fa-camera bigger-120"></i>
                                Photos de la station

                            </a>
                        </li>

                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">



                                <div class="profile-user-info">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Date de visite </div>

                                        <div class="profile-info-value">
                                            <span><?php $vsat_info->printattribute('last_visite'); ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Permissionnaire </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $vsat_info->printattribute('permissionnaire'); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Nom de station </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $vsat_info->printattribute('nom_station'); ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Revendeur </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $vsat_info->printattribute('revendeur'); ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Installateur </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $vsat_info->printattribute('installateur'); ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Formulaire</div>

                                        <div class="profile-info-value">
                                            <span>
                                                <a class="iframe_pdf" rel="<?php $vsat_info->printattribute('pj') ?>">
                                                    <i class="ace-icon fa fa-file-pdf-o red"></i>Formulaire de : <?php $vsat_info->printattribute('nom_station') ?> 
                                                </a>

                                            </span>
                                        </div>
                                    </div>







                                </div><!-- /.col -->

                            </div><!-- /.row -->



                        </div><!-- /#home -->

                        <div id="feed" class="tab-pane">
                            <div class="profile-info-row">
                                <div class="profile-info-name"> HUB </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('hub'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Satellite </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('satellite'); ?></span>
                                </div>
                            </div> 
                        </div><!-- /#feed -->

                        <div id="feed1" class="tab-pane">
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Ville de station </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('ville'); ?></span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Longitude </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('longi'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Latitude </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('latit'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name">Architecture réseau </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('arch_reseau'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Bande fréquence </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('bande_freq'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Type d'utilisation </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('utilisation'); ?></span>
                                </div>
                            </div>


                            <div class="profile-info-row">
                                <div class="profile-info-name"> Provenance Matériel </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('pays'); ?></span>
                                </div>
                            </div>


                            <div class="profile-info-row">
                                <div class="profile-info-name"> Date d'entrée  </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('dat_entr_materiel'); ?></span>
                                </div>
                            </div>
                        </div><!-- /#feed1 -->


                        <div id="feed2" class="tab-pane">


                            <div class="profile-info-row">
                                <div class="profile-info-name"> Diamètre (m) </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('diametre_antenne'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Marque </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('marque_antenne'); ?></span>
                                </div>
                            </div>

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Azimut </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('azimut'); ?></span>
                                </div>
                            </div>


                            <div class="profile-info-row">
                                <div class="profile-info-name"> Inclinaison </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('tilt'); ?></span>
                                </div>
                            </div>


                            <div class="profile-info-row">
                                <div class="profile-info-name"> Polarisation </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('polarisation'); ?></span>
                                </div>
                            </div>



                        </div><!-- /#feed2 -->


                        <div id="feed3" class="tab-pane">

                            <div class="profile-info-row">
                                <div class="profile-info-name"> Marque  </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('marque_radio'); ?></span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> N° de série Radio </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('ns_radio'); ?></span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Fréquence TX </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('tx_freq'); ?></span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Marque LNB </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('marque_lnb'); ?></span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> N° de série LNB </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('ns_lnb'); ?></span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Fréquence RX </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span><?php $vsat_info->printattribute('rx_freq'); ?></span>
                                </div>
                            </div>

                        </div><!-- /#feed3 -->
                        
                        <div id="feed4" class="tab-pane">
                           
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Marque  </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $vsat_info->printattribute('marque_modem'); ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> N° de série </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $vsat_info->printattribute('ns_modem'); ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> IP </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $vsat_info->printattribute('ip'); ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Débit download </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $vsat_info->printattribute('debit_download'); ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Débit upload </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $vsat_info->printattribute('debit_upload'); ?></span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Coût mensuel </div>

                                            <div class="profile-info-value">
                                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                                <span><?php $vsat_info->printattribute('cout_mensuel'); ?></span>
                                            </div>
                                        </div>



                        </div><!-- /#feed4 -->
                        
                        <div id="feed5" class="tab-pane">
                           <div class="profile-info-row">
                                <div class="profile-info-name"> Remarques </div>

                                <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <blockquote>
                                        <?php
                                          
                                          $vsat_info->printattribute('remarq');
                                            ?>
                                    </blockquote>
                                        
                                            
                                        
                                    
                                </div>
                            </div>
                            <?php echo MInit::get_pictures_gallery($vsat_info->Shw('pj_images', 1), true); ?>


                        </div><!-- /#feed5 -->

                    </div>

                </div>


            </div>
        </div><!-- /.well -->


    </div><!-- /.user-profile -->
