<?php 
 $client= new Mclients();
 $client->id_client = Mreq::tp('id');
 $client->get_client();

 $justif     = $client->client_info['pj'];
 $photo      = Minit::get_file_archive($client->client_info['pj_photo']);

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
		Détails du client: <?php $client->s('code')?>    <?php $client->s('denomination'); ?> 

		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">


		<div>
			<div id="user-profile-2" class="user-profile">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#home">
								<i class="green ace-icon fa fa-installer bigger-120"></i>
								Client 
							</a>
						</li>

						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
						


								<div class="col-xs-12 col-sm-6">
									<h4 class="blue">
										<span class="middle">Renseignements Client</span>
										
									</h4>

									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name"> Code</div>

											<div class="profile-info-value">
												<span><?php  $client->s('code')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Dénomination</div>


											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('denomination') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Catégorie</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('categorie_client') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Raison Sociale</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('r_social') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Registre Commerce</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('r_commerce') ?></span>
											</div>
										</div>
										
										<div class="profile-info-row">
											<div class="profile-info-name"> N° Identifiant Fiscal</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('nif') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Pays</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('pays') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Ville</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('ville') ?></span>
											</div>
										</div>


									</div>
								</div><!-- /.col -->


								<div class="col-xs-12 col-sm-6">
									<h4 class="blue">
										<span class="middle">Informations du Représentant</span>
										
									</h4>

									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name"> Nom</div>

											<div class="profile-info-value">
												<span><?php $client->s('nom');?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Prénom</div>

											<div class="profile-info-value">
												<span><?php $client->s('prenom') ;?></span>
											</div>
										</div>


										<div class="profile-info-row">
											<div class="profile-info-name"> Civilité</div>


											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('civilite') ?></span>
											</div>
										</div>

								</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Adresse</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('adresse') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Téléphone</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('tel') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Fax</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('fax') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Boite postale</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('bp') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Email</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('email') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Devise</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('devise') ?></span>
											</div>
										</div>


										<div class="profile-info-row">
											<div class="profile-info-name"> TVA</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $client->s('tva') ?></span>
											</div>
										</div>



									</div>

								</div>

								

									
							<?php if ($justif != null)
							{ 
							?>
							<div class="center">
								<a class="iframe_pdf" rel=<?php echo $justif; ?>><p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>Justifications du client: <?php  $client->s('denomination')  ?> </p></a>							
							</div>
							<?php 
						    }
							?>

							<?php if ($photo != null)
							{ 
							?>

							<div class="center">
							
											<span class="profile-picture">
										    	<img width="180" height="200" class="editable img-responsive" alt=<?php $client->s('denomination')   ?> id="avatar2" src="<?php echo $photo ?>" />
									        </span>	

							</div>
							<?php 
						    }
							?>			


									
						</div><!-- /#home -->

							</div><!-- /.row -->

							</div><!-- /#feed -->

						</div>
					</div>
				</div>
			</div>


		</div><!-- /.well -->


	</div><!-- /.user-profile -->