													<div class="widget-body">
										<div class="widget-main padding-24">
													<div class="tab-content no-border">
														<div class="row">
															<div class="col-xs-12 col-sm-6">
																<h4 class="blue">
																	<span class="middle">Renseignements objectif_service</span>
																</h4>

																<div class="profile-user-info">
																	<div class="profile-info-row">
																		<div class="profile-info-name">Désignation</div>
																		<div class="profile-info-value">
																			<span><?php  $info_objectif_service->s("description")  ?></span>
																		</div>
																	</div>
																</div>
																<div class="profile-user-info">
																	<div class="profile-info-row">
																		<div class="profile-info-name">Objectif</div>
																		<div class="profile-info-value">
																			<span><?php  $info_objectif_service->s("objectif")  ?></span>
																		</div>
																	</div>
																</div>
																<div class="profile-user-info">
																	<div class="profile-info-row">
																		<div class="profile-info-name">Réalisation</div>
																		<div class="profile-info-value">
																			<span><?php  $info_objectif_service->s("realise")  ?></span>
																		</div>
																	</div>
																</div>
																<div class="profile-user-info">
																	<div class="profile-info-row">
																		<div class="profile-info-name">Service</div>
																		<div class="profile-info-value">
																			<span><?php  $info_objectif_service->s("service")  ?></span>
																		</div>
																	</div>
																</div>
																<div class="profile-user-info">
																	<div class="profile-info-row">
																		<div class="profile-info-name">Date début</div>
																		<div class="profile-info-value">
																			<span><?php  $info_objectif_service->s("date_s")  ?></span>
																		</div>
																	</div>
																</div>
																<div class="profile-user-info">
																	<div class="profile-info-row">
																		<div class="profile-info-name">Date fin</div>
																		<div class="profile-info-value">
																			<span><?php  $info_objectif_service->s("date_e")  ?></span>
																		</div>
																	</div>
																</div>



															</div><!-- /.col -->
															<div class="col-xs-12 col-sm-6">
																
																	<?php 
//Best Product
$chart = new MHighchart();
$chart->titre = 'Top Produits demandés';
$chart->items = 'Fcfa';
$chart->chart_only = true;

$data_array	 =  array(
	array('name' => 'Réalisé',  'nbr' => $info_objectif_service->g('realise_int')) , 
	array('name' => 'Reste',  'nbr' => $info_objectif_service->g('reste_int')) , 
	
);
$chart->Pie_render_from_array($data_array, $info_objectif_service->g('objectif_int'), 12);

																	?>
																
															</div>
														</div> <!-- row -->
													</div> <!-- tab_content -->
												</div> <!-- widget-main -->
											</div><!-- widget-body -->
