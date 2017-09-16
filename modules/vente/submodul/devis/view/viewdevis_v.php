<?php
//Get all Devis info 
$info_devis = new Mdevis();
//Set ID of Module with POST id
$info_devis->id_devis = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_devis->get_devis())
{ 	
 	// returne message error red to client 
	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Get all client info
$info_client = new Mclients();
$info_client->id_client = $info_devis->g('id_client');
if(!$info_client->get_client())
{
	exit('3#'.$info_client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//var_dump($info_client->client_info);

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
							Client: <?php $info_client->s('r_social')?>
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
							<a href="#" class="iframe_pdf" rel="1">
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
											<b>Company Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled spaced">
											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>Street, City
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>Zip Code
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>State, Country
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>
												Phone:
												<b class="red">111-111-111</b>
											</li>

											<li class="divider"></li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i>
												Paymant Info
											</li>
										</ul>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
											<b>Customer Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled  spaced">
											<li>
												<i class="ace-icon fa fa-caret-right green"></i>Street, City
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i>Zip Code
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i>State, Country
											</li>

											<li class="divider"></li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i>
												Contact Info
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
										Total amount :
										<span class="red">$395</span>
									</h4>
								</div>
								<div class="col-sm-7 pull-left"> Extra Information </div>
							</div>

							<div class="space-6"></div>
							<div class="well">
								Thank you for choosing Ace Company products.
								We believe you will be satisfied by our services.
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
	var scripts = [null, null]
	$('.page-content-area').ace_ajax('loadScripts', scripts, function() {
	  //inline scripts related to this page
	});
</script>

