<?php
//Get all contrat info 
$info_contrat = new Mcontrat();
//Set ID of Module with POST id
$info_contrat->id_contrat = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_contrat->get_contrat_info())
{ 	
 	// returne message error red to client 
	exit('3#'.$info_contrat->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}
//Get all client info
//$info_devis = new MDevis();
//$info_devis->id_devis = $info_contrat->g('iddevis');
$info_contrat->id_devis = $info_contrat->s('iddevis');

//if(!$info_devis->get_devis_info())
if(!$info_contrat->get_devis_info())
{
	exit('3#'.$info_contrat->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_action('contrats', $info_contrat->id_contrat, 'detailcontrat');
                TableTools::btn_add('contrats','Liste des abonnements', Null, $exec = NULL, 'reply'); ?>
         

	</div>
</div>
<div class="page-header">
	<h1>
            Détails abonnement: <?php echo $info_contrat->s('reference');?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- ajax layout which only needs content area -->
<div class="row">
<?php Mmodul::get_statut_etat_line('contrats', $info_contrat->s('etat'));
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
							Devis: <?php echo $info_contrat->g('reference')?>
						</h3>

						<!-- #section:pages/invoice.info -->
						<div class="widget-toolbar no-border invoice-info">
							<table>
								<tr><td>
							<span class="invoice-info-label">Abonnement:</span>
                            		</td>
                            		<td>&nbsp;&nbsp;
                            <b class="red pull-right"><?php echo $info_contrat->s('reference');
                                                                        
                                                          ?></span>
                                    </td>                      
                                 </tr>
                                 <tr><td>
							<span class="invoice-info-label">Date:</span>
									</td>
                            		<td>&nbsp;&nbsp;
							<b class="blue pull-right"><?php echo $info_contrat->s('date_contrat'); 
                                                       
                                                        ?></span>
                                   </td>                      
                                 </tr>
                            </table>
						</div>

                        <?php if($info_contrat->s('pj') != null){?>
                         <div class="widget-toolbar hidden-480">
							<a href="#" class="iframe_pdf" rel="<?php echo $info_contrat->s('pj') ?>">
								<i class="ace-icon fa fa-print"></i>
							</a>
						</div>       
                       <?php } ?>
						

						<!-- /section:pages/invoice.info -->
					</div>

					<div class="widget-body">
						<div class="widget-main padding-24">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
											<b>Abonnement Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled spaced">
											<li>
											<i class="ace-icon fa fa-caret-right blue"></i> Date d'effet                            
                                             <b class="blue pull-right"> <?php echo $info_contrat->s('date_effet');?> </b>                             							
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right blue"></i> Date de fin
                                            <b class="blue pull-right"><?php echo $info_contrat->s('date_fin');?>  </b>          
                                                                                                    
											</li>

											<li>    
                 							<i class="ace-icon fa fa-caret-right blue"></i> Type échéance
                    						<b class="blue pull-right"><?php if($info_contrat->s('periode_fact')=='D'){ echo $info_contrat->s('type_echeance')." => Début";}
                     else {
                         echo $info_contrat->s('type_echeance')." => Fin";
                     }
                     ?></b>     
											</li>											

											<li>
											<i class="ace-icon fa fa-caret-right blue"></i>
												Commentaire
                                            <b class="blue pull-right"><?php echo $info_contrat->s('commentaire');?></b>
											</li>
										</ul>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
											<b>Devis Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled  spaced">
											<li>
											<i class="ace-icon fa fa-caret-right green"></i> Référence
                                            <b class="blue pull-right"><?php echo $info_contrat->g('reference');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right green"></i> Date devis
                                            <b class="blue pull-right"><?php echo $info_contrat->g('date_devis');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right green"></i>Client
                                            <b class="blue pull-right"><?php echo $info_contrat->g('client');?></b>
											</li>

											<li>
											<i class="ace-icon fa fa-caret-right green"></i>
											Tél Client
                                            <b class="blue pull-right"><?php echo $info_contrat->g('tel');?></b>
											</li>
										</ul>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->

							<div class="space"></div>
							<?php 
							if ($info_contrat->contrat_info['type_echeance'] == "Autres"){
							?>
							<div>
							<?php print $info_contrat->Gettable_echeance_contrat_autres();?>

								
							</div>
							<?php }else { ?> <div>
							<?php print $info_contrat->Gettable_echeance_contrat();?>

								
							</div> <?php } ?>

						
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