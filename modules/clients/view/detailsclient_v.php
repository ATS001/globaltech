<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => View

//Get all contrats_frn info 
 $client= new Mclients();
//Set ID of Module with POST id
 $client->id_client = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$client->get_client())
{ 	
 	// returne message error red to client 
	exit('3#'.$client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
$pj    	 = $client->client_info['pj'];
$photo   = Minit::get_file_archive($client->client_info['pj_photo']);

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php 
              TableTools::btn_add('clients', 'Liste Clients', Null, $exec = NULL, 'reply');      
		 ?>	
         

	</div>
</div>
<div class="page-header">
	<h1>
            Détails du client: <?php $client->s('reference')?>    <?php $client->s('denomination'); ?> 
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
							<?php if ($photo != null)
							{ 
							?>

							
							
											<span class="profile-picture">
										    	<img width="50" height="50" class="editable img-responsive" alt=<?php $client->s('denomination')   ?> id="avatar2" src="<?php echo $photo ?>" />
									        </span>	

							
							<?php 
						    }
							?>
							<i class="ace-icon fa fa-adress-card-o green"></i>
							Client : <?php echo $client->s('reference')?>
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
											<b>Renseignements Client</b>
										</div>
									</div>

									<div>
										
										<ul class="list-unstyled spaced">
											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Référence                                                                                               
                                                  <b style="color:blue"> <?php  $client->s('reference')  ?> </b>                                        
									
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Dénomination                                                                                               
                                                  <b style="color:blue"> <?php echo $client->s('denomination');?> </b>                                        
									
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Catégorie
                                                   <b style="color:blue"><?php echo $client->s('categorie_client');?>  </b>                                      
                                                                                                    
											</li>										

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Raison Sociale

                                                    <b style="color:blue"><?php echo $client->s('r_social');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Registre Commerce

                                                    <b style="color:blue"><?php echo $client->s('r_commerce');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> N° Identifiant Fiscal

                                                    <b style="color:blue"><?php echo $client->s('nif');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Pays

                                                    <b style="color:blue"><?php echo $client->s('pays');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Ville

                                                    <b style="color:blue"><?php echo $client->s('ville');?></b>
											</li>

										</ul>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-6">
									<div class="row">
										<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
											<b>Informations du Représentant</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled  spaced">
											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Nom
                                                   <b style="color:green"><?php echo $client->s('nom');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Prénom
                                                   <b style="color:green"><?php echo $client->s('prenom');?></b>
											</li>


											<li>
												<i class="ace-icon fa fa-caret-right green"></i>Civilité 
                                                    <b style="color:green"><?php echo $client->s('civilite');?></b>
											</li>
											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Adresse 
                                                    <b style="color:green"><?php echo $client->s('adresse');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Téléphone 
                                                    <b style="color:green"><?php echo $client->s('tel');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Fax 
                                                    <b style="color:green"><?php echo $client->s('fax');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Boite postale 
                                                    <b style="color:green"><?php echo $client->s('bp');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Email 
                                                    <b style="color:green"><?php echo $client->s('email');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Devise 
                                                    <b style="color:green"><?php echo $client->s('devise');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> TVA 
                                                    <b style="color:green"><?php echo $client->s('tva');?></b>
											</li>

										</ul>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->

						<!-- 	<div class="space"></div> -->

							

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

							<!-- <div class="space-6"></div>
							<div class="well">
								Thank you for choosing Ace Company products.
								We believe you will be satisfied by our services.
							</div> -->
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