<?php
//Get all Devis info 
$info_devis = new Mdevis();
//Set ID of Module with POST id
$info_devis->id_devis = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_devis->Get_detail_devis_show())
{ 	
 	// returne message error red to client 
	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//var_dump($info_devis->client_info);

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add('devis','Liste des Devis', Null, $exec = NULL, 'reply'); ?>

	</div>
</div>
<div class="page-header">
	<h1>
		Détails Devis: <?php $info_devis->s('reference')?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- ajax layout which only needs content area -->
<div class="row">
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
							Client: <?php $info_devis->s('denomination')?>
						</h3>

						<!-- #section:pages/invoice.info -->
						<div class="widget-toolbar no-border invoice-info">
							<span class="invoice-info-label">Devis:</span>
							<span class="red"><?php $info_devis->s('reference') ?></span>

							<br />
							<span class="invoice-info-label">Date:</span>
							<span class="blue"><?php $info_devis->s('date_devis') ?></span>
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
												<b class="blue"><?php echo $info_devis->g('denomination').'  #'.$info_devis->g('code')?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>
												Adresse: 
												<b class="blue"><?php echo $info_devis->g('adresse').' BP '.$info_devis->g('bp').' '.$info_devis->g('ville').'  '.$info_devis->g('pays')?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>
												Contact: 
												<b class="blue"><?php echo '@: '.$info_devis->g('email').'  Tél: '.$info_devis->g('tel')?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>
												Identifiant Fiscal: 
												<b class="blue"><?php $info_devis->s('nif')?></b>
											</li>

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
												Total hors Taxes: 
												<b class="blue right"><?php $info_devis->s('totalht')?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i>
												Total Remises: 
												<b class="blue right"><?php $info_devis->s('valeur_remise')?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i>
												Total TVA: 
												<b class="blue right"><?php $info_devis->s('totaltva')?></b>
											</li>

											

											<li>
												<i class="ace-icon fa fa-caret-right green"></i>
												Total TTC: 
												<b class="blue right"><?php $info_devis->s('totalttc')?></b>
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
										<span class="red"><?php $info_devis->s('totalttc')?> FCFA</span>
									</h4>
								</div>
								<div class="col-sm-7 pull-left"> 
									<b class="red">
								<?php 

                                $obj = new nuts($info_devis->g('totalttc'), "FCFA");
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

				<!-- /section:pages/invoice -->
			</div>
		</div>

		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<!-- page specific plugin scripts -->
<script type="text/javascript">
	
</script>

