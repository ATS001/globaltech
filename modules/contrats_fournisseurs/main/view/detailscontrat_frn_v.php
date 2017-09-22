<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => View

//Get all contrats_frn info 
$info_contrats_frn = new Mcontrats_fournisseurs();
//Set ID of Module with POST id
$info_contrats_frn->id_contrats_frn = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_contrats_frn->get_contrats_frn())
{ 	
 	// returne message error red to client 
	exit('3#'.$info_contrats_frn->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
$pj     = $info_contrats_frn->contrats_frn_info['pj'];
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add('contrats_fournisseurs','Liste des contrats fournisseurs', Null, $exec = NULL, 'reply'); ?>
         

	</div>
</div>
<div class="page-header">
	<h1>
            Détails du contrat : <?php echo $info_contrats_frn->s('reference');?>
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
							Contrat: <?php echo $info_contrats_frn->s('reference')?>
						</h3>

					

                        <?php if( $pj != null){
                        ?>
                         <div class="widget-toolbar hidden-480">
							<a href="#" class="iframe_pdf" rel=<?php echo $pj; ?>>
								<i class="ace-icon fa fa-print"></i>
							</a>
						</div>       
                       <?php 
                   							    } 
                   	   ?>
						

						<!-- /section:pages/invoice.info -->
					</div>

					<div class="widget-body">
						<div class="widget-main padding-24">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
											<b>Informations Contrat</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled spaced">
											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Référence                                                                                               
                                                  <b style="color:blue"> <?php echo $info_contrats_frn->s('reference');?> </b>                                        
									
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Date d'effet                                                                                               
                                                  <b style="color:blue"> <?php echo $info_contrats_frn->s('date_effet');?> </b>                                        
									
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Date de fin
                                                   <b style="color:blue"><?php echo $info_contrats_frn->s('date_fin');?>  </b>                                      
                                                                                                    
											</li>										

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Commentaire

                                                    <b style="color:blue"><?php echo $info_contrats_frn->s('commentaire');?></b>
											</li>
										</ul>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
											<b>Informations Fournisseur </b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled  spaced">
											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Référence
                                                   <b style="color:green"><?php echo $info_contrats_frn->s('code');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Dénomination
                                                   <b style="color:green"><?php echo $info_contrats_frn->s('denomination');?></b>
											</li>


											<li>
												<i class="ace-icon fa fa-caret-right green"></i>
												Pays 
                                                    <b style="color:green"><?php echo $info_contrats_frn->s('pays');?></b>
											</li>
											<li>
												<i class="ace-icon fa fa-caret-right green"></i>
												Tél 
                                                    <b style="color:green"><?php echo $info_contrats_frn->s('tel');?></b>
											</li>
										</ul>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->

							<div class="space"></div>

							

							<div class="hr hr8 hr-double hr-dotted"></div>

<!--							<div class="row">
								<div class="col-sm-5 pull-right">
									<h4 class="pull-right">
										Total amount :
										<span class="red">$395</span>
									</h4>
								</div>
								<div class="col-sm-7 pull-left"> Extra Information </div>
							</div>-->

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