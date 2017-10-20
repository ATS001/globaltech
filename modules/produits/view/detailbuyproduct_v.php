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
    		Détails de l'achat du produit : <?php  $info_achat->printattribute('reference'); ?> 

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
                                Achat
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="col-xs-12 col-sm-4"></div>

                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                        <b>Produit Info</b>
                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled  spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Référence produit
                                            <b style="color:green"><?php $info_achat->printattribute('reference'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Quantité
                                            <b style="color:green"><?php $info_achat->printattribute('qte'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Prix d'achat
                                            <b style="color:green"><?php  $info_achat->printattribute('prix_achat'); ?></b>
                                        </li>

                                        <li>
                                       
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Prix de vente
                                            <b style="color:green"><?php $info_achat->printattribute('prix_vente'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Date d'achat 
                                            <b style="color:green"><?php $info_achat->printattribute('date_achat'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Date de fin de validité 
                                            <b style="color:green"><?php $info_achat->printattribute('date_validite'); ?></b>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div><!-- /.col --> 
                            
                            
                                
                            
                            <div class="col-xs-12 col-sm-4"></div>
                            
                            
                        </div><!-- /.row -->

                    </div><!-- /#home -->


                </div>
            </div>
        </div>





    </div><!-- /.achat-profile -->
