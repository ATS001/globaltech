<?php
$info_facture = new Mfacture();
$info_facture->id_facture = Mreq::tp('id');
$info_facture->get_facture_info();
$info_facture->get_complement_by_facture();
$complements=$info_facture->complement_info;
$info_facture->get_encaissement();
$encaissements=$info_facture->encaissement_info;

?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('factures', 'Liste des factures', NULL, $exec = NULL, 'reply');
        ?>        

    </div>
</div>
<div class="page-header">
    <h1>
        Détails de la facture : <?php $info_facture->printattribute_fact('ref'); ?> 

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
                            Facture
                        </a>
                    </li>
                    
                    <li>
                            <a data-toggle="tab" href="#feed">
                                <i class="red ace-icon fa fa-adjust bigger-120"></i>
                                Compléments
                            </a>
                        </li>

                        <li>
                            <a data-toggle="tab" href="#feed1">
                            <i class="blue ace-icon fa fa-inbox bigger-120"></i>
                                Encaissements
                            </a>
                        </li>

                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in active">
                        <div class="col-xs-12 col-sm-4"></div>

                        <div class="col-xs-12 col-sm-4">
                            <div class="profile-achat-info">
                                <div class="widget-toolbar hidden-480">
                                    <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'facture') ?>" data="<?php echo MInit::crypt_tp('id', $info_facture->id_facture) ?>">
                                        <i class="ace-icon fa fa-print"></i>
                                    </a>
                                </div>   
                                <!--<div class="widget-toolbar hidden-480">
                                                                                        <a href="#" class="iframe_pdf" rel="<?php $info_facture->printattribute_fact('facture_pdf') ?>">
                                                                                                <i class="ace-icon fa fa-print"></i>
                                                                                        </a>
                                                                                </div> -->
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Référence </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('ref'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Totale HT </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('total_ht'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Totale TVA </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('total_tva'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Total TTC </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('total_ttc'); ?></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Totale payé </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('total_paye'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Reste </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('reste'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Client </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('client'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> TVA </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('tva'); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Date facture </div>

                                    <div class="profile-info-value">
                                            <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                        <span><?php $info_facture->printattribute_fact('date_facture'); ?></span>
                                    </div>
                                </div>

                            </div>

                        </div><!-- /.col -->

                        <div class="col-xs-12 col-sm-4"></div>


                    </div><!-- /.row -->
                    
                    <div id="feed" class="tab-pane">
                            <div class="profile-info-row">
                                                         <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span>
                                        <?php 
                                                                                         if($complements == null)
                                                                                             echo '<B>Aucun complément trouvé</B> ';
                                                                                         else {?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px">
										<th>
											ID
										</th>
                                                                                <th>
											Désignation
										</th>
                                                                                <th>
											Type
										</th>
                                                                                <th>
											Montant
										</th>
											<?php 
//                                                                                         if($complements == null)
//                                                                                             echo '<B>Aucun enregistrement trouvé</B> ';
//                                                                                         else {
											foreach($complements as $cmpl ) {?>
											<tr>	
												<td>
													<span><?php echo $cmpl['0']; ?></span>
												</td>
                                                                                                <td>
													<span><?php echo $cmpl['1']; ?></span>
												</td>
                                                                                                <td>
													<span><?php echo $cmpl['2']; ?></span>
												</td>
                                                                                                <td>
													<span><?php echo $cmpl['3']; ?></span>
												</td>
											</tr>
                                                                                        
                                                                                     
                                                                                         <?php }
                                                                                         
                                                                                         } ?>
											
	     </table>
                                    </span>
                                </div>
                            </div>

                             
                        </div><!-- /#feed -->
                        
                        
                        <div id="feed1" class="tab-pane">
                            <div class="profile-info-row">
                                                         <div class="profile-info-value">
                                        <!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
                                    <span>
                                        <?php 
                                                                                         if($encaissements == null)
                                                                                             echo '<B>Aucun encaissement trouvé</B> ';
                                                                                         else {?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px">
										<th>
											ID
										</th>
                                                                                <th>
											Référence
										</th>
                                                                                <th>
											Montant
										</th>
                                                                                <th>
											Date
										</th>
											<?php 
											foreach($encaissements as $encs ) {?>
											<tr>	
												<td>
													<span><?php echo $encs['0']; ?></span>
												</td>
                                                                                                <td>
													<span><?php echo $encs['1']; ?></span>
												</td>
                                                                                                <td>
													<span><?php echo $encs['4']; ?></span>
												</td>
                                                                                                <td>
													<span><?php echo $encs['6']; ?></span>
												</td>
											</tr>
                                                                                        
                                                                                     
                                                                                        <?php }} ?>
											
	     </table>
                                    </span>
                                </div>
                            </div>

                             
                        </div><!-- /#feed -->
                        
                </div><!-- /#home -->

                

            </div>
        </div>
    </div>





</div><!-- /.-profile -->
