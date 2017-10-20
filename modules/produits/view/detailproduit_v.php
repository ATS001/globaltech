    <?php
    $info_produit = new Mproduit();
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
            Détails du produit : <?php $info_produit->printattribute('reference'); ?> 

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

                         <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                        <b>Produit Info</b>
                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled  spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Type de produit
                                            <b style="color:green"><?php $info_produit->printattribute('type_produit'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Catégorie du produit
                                            <b style="color:green"><?php $info_produit->printattribute('categorie_produit'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Unité de vente
                                            <b style="color:green"><?php $info_produit->printattribute('unite_vente'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Référence
                                            <b style="color:green"><?php $info_produit->printattribute('reference'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Désignation
                                            <b style="color:green"><?php $info_produit->printattribute('designation'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Prix de vente
                                            <b style="color:green"><?php $info_produit->printattribute('prix_vente'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Stock minimal
                                            <b style="color:green"><?php $info_produit->printattribute('stock_min'); ?></b>
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
    </div><!-- /.produit-profile -->
