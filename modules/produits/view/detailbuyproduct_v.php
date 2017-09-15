    <?php 
     $info_achat= new Machat();
     $info_achat->id_achat = Mreq::tp('id');
     $info_achat->get_achat_produit_info();


     ?>
    <div class="pull-right tableTools-container">
    	<div class="btn-group btn-overlap">
    					
    		                   
                   
                   <?php
        TableTools::btn_add('buyproducts', 'Liste des achats', MInit::crypt_tp('id', $info_achat->Shw('idproduit', 1)), $exec = NULL, 'reply');
        ?>
    		 	
    	</div>
    </div>
    <div class="page-header">
    	<h1>
    		Détails de l'achat : <?php  $info_achat->printattribute('ref'); ?> 

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
                                Produit
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="col-xs-12 col-sm-4"></div>

                            <div class="col-xs-12 col-sm-4">
                                <div class="profile-achat-info">
                                    
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Produit </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $info_achat->printattribute('idproduit'); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Quantité </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $info_achat->printattribute('qte'); ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Prix d'achat </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $info_achat->printattribute('prix_achat'); ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Prix de vente </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $info_achat->printattribute('prix_vente'); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Date d'achat </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $info_achat->printattribute('date_achat'); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Date de fin de validité </div>

                                        <div class="profile-info-value">
                                                <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                            <span><?php $info_achat->printattribute('date_validite'); ?></span>
                                        </div>
                                    </div>

                                    
                                </div>

                            </div><!-- /.col -->
                            
                            <div class="col-xs-12 col-sm-4"></div>
                            
                            
                        </div><!-- /.row -->

                    </div><!-- /#home -->


                </div>
            </div>
        </div>





    </div><!-- /.achat-profile -->
