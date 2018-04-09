<?php
//Get all proforma info 
$info_proforma = new Mproforma();
//$action = new TableTools();
$info_proforma->id_proforma = Mreq::tp('id');
//$info_proforma->get_proforma();
//$action->line_data = $info_proforma->proforma_info;
//$actions = $action->action_profil_view('proforma', 'proforma', Minit::crypt_tp('id', Mreq::tp('id')), $info_proforma->g('creusr') , 'deleteproforma');
//Set ID of Module with POST id
$info_proforma->id_proforma = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_proforma->Get_detail_proforma_show())
	{ 	
 	// returne message error red to client 
		exit('3#'.$info_proforma->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}

	?>
	<div class="pull-right tableTools-container">
		<div class="btn-group btn-overlap">

			<?php 
            TableTools::btn_action('proforma', $info_proforma->id_proforma, 'viewproforma');
			TableTools::btn_add('proforma','Liste des proforma', Null, $exec = NULL, 'reply'); 

			?>

		</div>
	</div>
	<div class="page-header">
		<h1>
			Détails proforma: <?php $info_proforma->s('reference')?>
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
			</small>
			
		</h1>
	</div><!-- /.page-header -->
	<!-- /.page-header -->
	<div class="row">
<?php Mmodul::get_statut_etat_line('proforma', $info_proforma->g('etat'))?>
		<div>
			<div id="user-profile-2" class="user-profile">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#home">
								<i class="green ace-icon fa fa-money bigger-120"></i>
								proforma
							</a>
						</li>
						

					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="col-xs-12 col-sm-4"></div>

							<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<!-- #section:pages/invoice -->
					<div class="widget-box transparent">
						<div class="widget-header widget-header-large">
							<h3 class="widget-title grey lighter">
								<i class="ace-icon fa fa-adress-card-o green"></i>
								Client: <?php $info_proforma->s('denomination')?>
							</h3>

							<!-- #section:pages/invoice.info -->
							<div class="widget-toolbar no-border invoice-info">
								<span class="invoice-info-label">proforma:</span>
								<span class="red"><?php $info_proforma->s('reference') ?></span>

								<br />
								<span class="invoice-info-label">Date:</span>
								<span class="blue"><?php $info_proforma->s('date_proforma') ?></span>
								<br />
								
							</div>

							
							<div class="widget-toolbar hidden-480">

								<a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'proforma') ?>" data="<?php echo MInit::crypt_tp('id', $info_proforma->id_proforma) ?>">
									
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
														<b class="blue pull-right"><?php echo $info_proforma->g('denomination').'  #'.$info_proforma->g('reference_client')?></b>
													</li>

													<li>
														<i class="ace-icon fa fa-caret-right blue"></i>
														Adresse: 
														<b class="blue pull-right"><?php echo $info_proforma->g('adresse').'  '.$info_proforma->g('bp').' '.$info_proforma->g('ville').'  '.$info_proforma->g('pays')?></b>
													</li>

													<li>
														<i class="ace-icon fa fa-caret-right blue"></i>
														Email: 
														<b class="blue pull-right"><?php $info_proforma->s('email')?></b>
													</li>
													<li>
														<i class="ace-icon fa fa-caret-right blue"></i>
														Téléphone: 
														<b class="blue pull-right"><?php $info_proforma->s('tel')?></b>
													</li>
                                                    
													<?php if($info_proforma->g('nif') != null){?>
                                                    <li>
														<i class="ace-icon fa fa-caret-right blue"></i>
														Identifiant Fiscal: 
														<b class="blue pull-right"><?php $info_proforma->s('nif')?></b>
													</li>
													<?php }?>
													
													<li class="divider"></li>

													
												</ul>
											</div>
										</div><!-- /.col -->

										
									</div><!-- /.row -->

									<div class="space"></div>

									<div>
										<?php print $info_proforma->Gettable_detail_proforma();?>

										
									</div>

									<div class="hr hr8 hr-double hr-dotted"></div>

									

									<div class="space-6"></div>
									<div class="well">
										<?php $info_proforma->s('claus_comercial')?>
									</div>
								</div>
							</div>
						</div>

						<!-- /section:pages/invoice -->
					</div>
				</div>

				<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->

							<div class="col-xs-12 col-sm-4"></div>


						</div><!-- /.row -->

					</div><!-- /#home -->


				</div>
			</div>
		</div>
	</div><!-- /.produit-profile -->
	

		<!-- page specific plugin scripts -->
		<script type="text/javascript">

		</script>

