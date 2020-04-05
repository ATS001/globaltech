<div class="widget-box transparent">
									<div class="widget-header widget-header-large">
										<h3 class="widget-title grey lighter">
											<i class="ace-icon fa fa-adress-card-o green"></i>
											<?php if($info_devis->s('db') != null) { ?>
											Devis révisé: <?php $info_devis->s('db')?>
										<?php } else { Client: $info_devis->s('denomination'); } ?>
										</h3>

										<!-- #section:pages/invoice.info -->
										<div class="widget-toolbar no-border invoice-info">
											<span class="invoice-info-label">Devis:</span>
											<span class="red"><?php $info_devis->s('reference') ?></span>

											<br />
											<span class="invoice-info-label">Date:</span>
											<span class="blue"><?php $info_devis->s('date_devis') ?></span>
											<br />
											<?php if( $info_devis->g('ref_bc') != null){?>
												<span class="invoice-info-label">B.Commande:</span>
												<span class="blue"><?php $info_devis->s('ref_bc') ?></span>
											<?php }?>
										</div>


										<div class="widget-toolbar hidden-480">

											<a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'devis') ?>" data="<?php echo MInit::crypt_tp('id', $info_devis->id_devis) ?>">
												<i class="ace-icon fa fa-print"></i>
											</a>

										</div>



										<!-- /section:pages/invoice.info -->
									</div>

									<div class="widget-body">
										<div class="widget-main padding-24">
											<div class="row">
												<div class="col-sm-6">
													<div class="row">
														<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
															<b>Informations Client</b>
														</div>
													</div>

													<div>
														<ul class="list-unstyled spaced">
															<li>
																<i class="ace-icon fa fa-caret-right blue"></i>
																Identifiant:
																<b class="blue pull-right"><?php echo $info_devis->g('denomination').'  #'.$info_devis->g('reference_client')?></b>
															</li>

															<li>
																<i class="ace-icon fa fa-caret-right blue"></i>
																Adresse:
																<b class="blue pull-right"><?php echo $info_devis->g('adresse').'  '.$info_devis->g('bp').' '.$info_devis->g('ville').'  '.$info_devis->g('pays')?></b>
															</li>

															<li>
																<i class="ace-icon fa fa-caret-right blue"></i>
																Email:
																<b class="blue pull-right"><?php $info_devis->s('email')?></b>
															</li>
															<li>
																<i class="ace-icon fa fa-caret-right blue"></i>
																Téléphone:
																<b class="blue pull-right"><?php $info_devis->s('tel')?></b>
															</li>
															<?php if($info_devis->g('projet') != null){?>
																<li>
																	<i class="ace-icon fa fa-caret-right blue"></i>
																	Projet:
																	<b class="blue pull-right"><?php $info_devis->s('projet')?></b>
																</li>
															<?php }?>
															<?php if($info_devis->g('nif') != null){?>
																<li>
																	<i class="ace-icon fa fa-caret-right blue"></i>
																	Identifiant Fiscal:
																	<b class="blue pull-right"><?php $info_devis->s('nif')?></b>
																</li>
															<?php }?>

															<li class="divider"></li>


														</ul>
													</div>
												</div><!-- /.col -->

												<div class="col-sm-6">
													<div class="row">
														<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
															<b>Informations Devis</b>
														</div>
													</div>

													<div>
														<ul class="list-unstyled  spaced">
															<li>
																<i class="ace-icon fa fa-caret-right green"></i>
																Commercial:
																<b class="blue pull-right"><?php $info_devis->s('commercial');?></b>
															</li>

															<li>
																<i class="ace-icon fa fa-caret-right green"></i>
																Total:
																<b class="blue pull-right"><?php $info_devis->s('total_no_remise'); echo ' ';$info_devis->s('devise') ?></b>
															</li>
															<li>
																<i class="ace-icon fa fa-caret-right green"></i>
																Total Remises <?php $info_devis->s('valeur_remise')?> %:
																<b class="blue pull-right"><?php $info_devis->s('total_remise'); echo ' ';$info_devis->s('devise')?></b>
															</li>
															<li>
																<i class="ace-icon fa fa-caret-right green"></i>
																Total hors Taxes:
																<b class="blue pull-right"><?php $info_devis->s('totalht'); echo ' ';$info_devis->s('devise')?></b>
															</li>



															<li>
																<i class="ace-icon fa fa-caret-right green"></i>
																Total TVA:
																<b class="blue pull-right"><?php $info_devis->s('totaltva'); echo ' ';$info_devis->s('devise')?></b>
															</li>



															<li>
																<i class="ace-icon fa fa-caret-right green"></i>
																Total TTC:
																<b class="blue pull-right"><?php $info_devis->s('totalttc'); echo ' ';$info_devis->s('devise')?></b>
															</li>
														</ul>
													</div>
												</div><!-- /.col -->
											</div><!-- /.row -->

											<div class="space"></div>

											<div>
												<?php print $info_devis->Gettable_detail_devis();?>


											</div>

											<div class="hr hr8 hr-double hr-dotted"></div>

											<div class="row">
												<div class="col-sm-5 pull-right">
													<h4 class="pull-right">
														Montant Total :
														<span class="red"><?php $info_devis->s('totalttc'); echo ' ';$info_devis->s('devise')?></span>
													</h4>
												</div>
												<div class="col-sm-7 pull-left">
													<b class="red">
														<?php
														$devise = $info_devis->g('devise');


														$obj = new nuts($info_devis->g('totalttc'), $devise);
														$ttc_lettre = $obj->convert("fr-FR");
														echo 'Soit: '.$ttc_lettre;
														?>
													</b>
												</div>
											</div>

											<div class="space-6"></div>
											<div class="well">
												<?php $info_devis->s('claus_comercial')?>
											</div>
										</div>
									</div>
								</div>
