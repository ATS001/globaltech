<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 25-10-2019
//View
 $prospects= new Mprospects();
 $prospects->id_prospect = Mreq::tp('id');
 $prospects->get_prospect();
 ?>
 <div class="pull-right tableTools-container">
 	<div class="btn-group btn-overlap">
 		<?php TableTools::btn_action('prospects', $prospects->id_prospect, 'detailsprospect');
 		TableTools::btn_add('prospects', 'Liste des prospects', Null, $exec = NULL, 'reply');      
 		?>		
 	</div>
 </div>
 
<div class="page-header">
	<h1>
            Détails du prospect:     <?php $prospects->s('raison_sociale'); ?> 
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- ajax layout which only needs content area -->
<div class="row">
 <?php Mmodul::get_statut_etat_line('prospects', $prospects->g('etat'));
//echo $actions;
?>
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="space-6"></div>

		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<!-- #section:pages/invoice -->
				<div class="widget-box transparent">
					<div class="widget-header widget-header-large">
						<h3 class="widget-title grey lighter">
							<i class="ace-icon fa fa-adress-card-o green"></i>
							Prospect: <?php echo $prospects->g('reference')?>
						</h3>

						<!-- #section:pages/invoice.info -->
						<div class="widget-toolbar no-border invoice-info">
							<table>
                                 <tr><td>
							<span class="invoice-info-label">Date:</span>
									</td>
                            		<td>&nbsp;&nbsp;
							<b class="blue pull-right"><?php echo $prospects->s('date_prospect'); 
                                                       
                                                        ?></span>
                                   </td>                      
                                 </tr>
                            </table>
						</div>

						<!-- /section:pages/invoice.info -->
					</div>

					<div class="widget-body">
						<div class="widget-main padding-24">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
											<b>Prospect Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled spaced">
											<li>
											<i class="ace-icon fa fa-caret-right blue"></i> Raison Sociale                            
                                             <b class="blue pull-right"> <?php $prospects->s('raison_sociale');?> </b>                             							
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right blue"></i> Commercial
                                            <b class="blue pull-right"><?php $prospects->s('commercial');?>  </b>                                                                                                              
											</li>
											
											<li>
											<i class="ace-icon fa fa-caret-right blue"></i> CA Prévisionnel
                                            <b class="blue pull-right"><?php $prospects->s('ca_previsionnel');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right blue"></i> Pondération
                                            <b class="blue pull-right"><?php $prospects->s('ponderation');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right blue"></i>CA Pondéré
                                            <b class="blue pull-right"><?php $prospects->s('ca_pondere');?></b>
											</li>											
										</ul>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
											<b>Offre Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled  spaced">
											<li>    
                 							<i class="ace-icon fa fa-caret-right green"></i> Offre
                    						<b class="blue pull-right"><?php   $prospects->s('lib_offre');?></b>     
											</li>											
											
											<li>
											<i class="ace-icon fa fa-caret-right green"></i>Date Entrée
                                            <b class="blue pull-right"><?php $prospects->s('date_e');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right green"></i>Date Cible
                                            <b class="blue pull-right"><?php $prospects->s('date_c');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right green"></i>Statut Deal
                                            <b class="blue pull-right"><?php $prospects->s('statut_deal');?></b>
											</li>
											
											<li>
											<i class="ace-icon fa fa-caret-right green"></i>Commentaires
                                            <b class="blue pull-right"><?php $prospects->s('commentaires');?></b>
											</li>
										</ul>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->

							
							<div>
								
							</div> 

						
						</div>
					</div>
				</div>

				<!-- /section:pages/invoice -->
			</div>
		</div>

		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->