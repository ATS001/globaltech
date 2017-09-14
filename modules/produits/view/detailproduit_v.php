<?php 
 $info_produit= new Mproduit();
 $info_produit->id_produit = Mreq::tp('id');
 $info_produit->get_produit_info();


 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php 
              TableTools::btn_add('produits', 'Liste des produits', Null, $exec = NULL, 'reply');      
		 ?>		
	</div>
</div>
<div class="page-header">
	<h1>
		Détails du produit : <?php  $info_produit->printattribute('ref'); ?> 

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
                            <div class="profile-produit-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Référence </div>

                                    <div class="profile-info-value">
                                        <span><?php $info_produit->printattribute('ref'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Désignation </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_produit->printattribute('designation'); ?></span>
                                    </div>
                                </div>
                                
                               

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Stock minimale </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_produit->printattribute('stock_min'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Catégorie du produit </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_produit->printattribute('categorie_produit'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Unité de vente </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_produit->printattribute('unite_vente'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Type de produit </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_produit->printattribute('type_produit'); ?></span>
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





</div><!-- /.produit-profile -->
