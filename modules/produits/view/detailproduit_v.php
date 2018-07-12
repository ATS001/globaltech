    <?php
    $info_produit = new Mproduit();
    $info_produit->id_produit = Mreq::tp('id');
    $info_produit->get_produit_info();
    $info_produit->get_mouvement_info();
    $mouvement=$info_produit->mouvement_info;
    
    ?>
    <div class="pull-right tableTools-container">
        <div class="btn-group btn-overlap">


            <?php
            TableTools::btn_action('produits', $info_produit->id_produit, 'detailproduit');
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
                        <?php if($info_produit->produit_info["idtype"] != "2" AND $info_produit->produit_info["idtype"] != "3"){ ?>
        
                        <li>
                            <a data-toggle="tab" href="#feed1">
                                <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                Mouvement Stock
                            </a>
                        </li>
                        <?php }   ?>
                    </ul>

                    <div class="tab-content no-border padding-18">
                        <div id="home" class="tab-pane in active">
                            <div class="col-xs-12 col-sm-4"></div>

                         <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                        <b>Informations Produit</b>
                                    </div>
                                </div>

                                <div>
                                    <ul class="list-unstyled  spaced">
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Type de produit
                                            <b class="blue pull-right"><?php $info_produit->printattribute('type_produit'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Catégorie du produit
                                            <b class="blue pull-right"><?php $info_produit->printattribute('categorie_produit'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i> Unité de vente
                                            <b class="blue pull-right"><?php $info_produit->printattribute('unite_vente'); ?></b>
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Référence
                                            <b class="blue pull-right"><?php $info_produit->printattribute('reference'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Désignation
                                            <b class="blue pull-right"><?php $info_produit->printattribute('designation'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Prix de vente
                                            <b class="blue pull-right"><?php $info_produit->printattribute('prix_vente'); ?></b>
                                        </li>
                                        <li>
                                            <i class="ace-icon fa fa-caret-right green"></i>
                                            Stock minimal
                                            <b class="blue pull-right"><?php $info_produit->printattribute('stock_min'); ?></b>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div><!-- /.col -->   
                            
                                              <div class="col-xs-12 col-sm-4"></div>


                        </div><!-- /.row -->
                        
                         <div id="feed1" class="tab-pane">

                            <div class="profile-info-row">
                                <div class="profile-info-value">
                                    <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span>
                                        <?php
                                        if ($mouvement == null)
                                            echo '<B>Aucune mouvement trouvé</B> ';
                                        else {
                                            ?>
                                            <table class="table table-striped table-bordered table-hover"
                                                   style="width: 820px">
                                                <th align="center" style="width: 20px">
                                                    ID
                                                </th>
                                                <th style="width:300px">
                                                    Entrepôt
                                                </th>
                                                <th align="center" style="width: 120px">
                                                    Référence
                                                </th>
                                                <th align="center" style="width: 800px">
                                                    Désignation
                                                </th>
                                                <th align="center" style="width: 100px">
                                                    Quantité
                                                </th>
                                                <th align="center" style="width: 120px">
                                                    Date
                                                </th>
                                                <th align="center" style="width: 200px">
                                                    Source
                                                </th>
                                                <th align="center" style="width: 120px">
                                                    Mouvement
                                                </th>
                                                <?php
                                                foreach ($mouvement as $cmpl) {
                                                    ?>
                                                    <tr>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['id'] != null) { ?>
                                                                <span><?php echo $cmpl['id']; ?></span>
        <?php } else echo '-'; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($cmpl['entrepot'] != null) { ?>
                                                                <span><?php echo $cmpl['entrepot']; ?></span>
        <?php } else echo '-'; ?>
                                                        </td>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['reference'] != null) { ?>
                                                                <span><?php echo $cmpl['reference']; ?></span>
        <?php } else echo '-'; ?>
                                                        </td>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['designation'] != null) { ?>
                                                                <span><?php echo $cmpl['designation']; ?></span>
        <?php } else echo '-'; ?>
                                                        </td>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['qte'] != null) { ?>
                                                                <span><?php echo $cmpl['qte']; ?></span>
        <?php } else echo '-'; ?>

                                                        </td>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['DATE'] != null) { ?>
                                                                <span><?php echo $cmpl['DATE']; ?></span>
        <?php } else echo '-'; ?>

                                                        </td>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['source'] != null) { ?>
                                                                <span><?php echo $cmpl['source']; ?></span>
        <?php } else echo '-'; ?>

                                                        </td>
                                                        <td align="center" valign="top">
                                                            <?php if ($cmpl['mouvement'] != null) { ?>
                                                                <span><?php echo $cmpl['mouvement']; ?></span>
        <?php } else echo '-'; ?>

                                                        </td>
                                                        
                                                    </tr>


                                                    <?php
                                                }
                                            }
                                            ?>

                                        </table>
                                    </span>
                                </div>
                            </div>


                        </div><!-- /#feed1 -->

                    </div><!-- /#home -->


                </div>
            </div>
        </div>
    </div><!-- /.produit-profile -->
