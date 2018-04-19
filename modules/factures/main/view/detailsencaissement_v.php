<?php
$info_encaissement = new Mfacture();
$info_encaissement->id_encaissement = Mreq::tp('id');
$info_encaissement->get_encaissement_info();
$justif = $info_encaissement->encaissement_info['pj'];
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('encaissements', 'Liste des encaissements', MInit::crypt_tp('id', $info_encaissement->Shw('idfacture', 1)), $exec = NULL, 'reply');
        ?>



    </div>
</div>
<div class="page-header">
    <h1>
        Détails de l'encaissement : <?php $info_encaissement->printattribute('reference'); ?> 

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
                            Encaissement
                        </a>
                    </li>

                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">
                        <div class="col-xs-12 col-sm-4"></div>

                        <div class="col-xs-12 col-sm-4">
                            <div class="profile-achat-info">

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Référence </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_encaissement->printattribute('reference'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Désignation </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_encaissement->printattribute('designation'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Facture </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_encaissement->printattribute('facture'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Montant </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_encaissement->printattribute('montant'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Date </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_encaissement->printattribute('date_encaissement'); ?></span>
                                    </div>
                                </div>
                                <?php
                                if ($justif != null) {
                                    ?>
                                    <div class="center">                                        
                                        <a class="iframe_pdf" rel="<?php $info_encaissement->printattribute('pj') ?>">
                                            <p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>
                                                <?php $info_encaissement->printattribute('reference') ?> </p>
                                        </a>							
                                    </div>  
                                    <?php
                                }
                                else 
                                { ?>
                                    <div class="profile-info-row">
                                    <div class="profile-info-name"> Pièce jointe </div>

                                    <div class="profile-info-value">
                                
                                        <span>Pièce jointe non disponible</span>
                                    </div>
                                </div>
                                <?php }
                                ?> 

                            </div>

                        </div><!-- /.col -->

                        <div class="col-xs-12 col-sm-4"></div>


                    </div><!-- /.row -->

                </div><!-- /#home -->


            </div>
        </div>
    </div>





</div><!-- /.-profile -->
