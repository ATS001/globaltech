<?php 

 $info_rev= new Mrev();
 $info_rev->id_rev = Mreq::tp('id');
 $info_rev->get_rev();

$photo = Minit::get_file_archive($info_rev->rev_info['pj_image']);
$formulaire = $info_rev->rev_info['pj'];
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('revendeurs', 'Liste Revendeurs', Null, $exec = NULL, 'reply');      
		?>	

	</div>
</div>
<div class="page-header">
	<h1>
		Détails du revendeur : <?php  $info_rev->s('denomination'); ?> 

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
								<i class="green ace-icon fa fa-vendor bigger-120"></i>
								Revendeur 
							</a>
						</li>

						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
						


								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Renseignements du revendeur</span>
										
									</h4>

									<div class="profile-user-info">
										

										<div class="profile-info-row">
											
											<div class="profile-info-name"> Raison Sociale  </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('denomination') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
										
											<div class="profile-info-name">Registre de Commerce</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('piece_identite') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Numéro d'agrément</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('num_agrement') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											
											<div class="profile-info-name">Secteur d'activité</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('qualification') ?></span>
											</div>
										</div>
										

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Coordonées du revendeur</span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name">Ville</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('villes') ?></span>
											</div>
										</div>
										
										<div class="profile-info-row">
											<div class="profile-info-name">Adresse</div>

											<div class="profile-info-value">
												<span><?php  $info_rev->s('adresse')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Email</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('email') ?></span>
											</div>
										</div>
									
										<div class="profile-info-row">
											<div class="profile-info-name"> Téléphone </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('tel') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Fax </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_rev->s('fax') ?></span>
											</div>
										</div>

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Activités du revendeur </br></span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name"> Installation VSAT </div>
											<?php  
												  if ($info_rev->Shw('vsat',1) == 1)
													{
														$vsat='OUI';
													}
													else
													{
														$vsat='NON';
													}
											 ?>
											<div class="profile-info-value">
												<span><?php  echo $vsat;  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Installation UHF/VHF </div>
											<?php  
												  if ($info_rev->Shw('uhf_vhf',1) == 1)
													{
														$uhf_vhf='OUI';
													}
													else
													{
														$uhf_vhf='NON';
													}
											 ?>
											<div class="profile-info-value">
												<span><?php  echo $uhf_vhf;  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Installation GSM </div>
											<?php  
												  if ($info_rev->Shw('gsm',1) == 1)
													{
														$gsm='OUI';
													}
													else
													{
														$gsm='NON';
													}
											 ?>
											<div class="profile-info-value">
												<span><?php  echo $gsm;  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Installation BLR </div>
											<?php  
												  if ($info_rev->Shw('blr',1) == 1)
													{
														$blr='OUI';
													}
													else
													{
														$blr='NON';
													}
											 ?>
											<div class="profile-info-value">
												<span><?php  echo $blr;  ?></span>
											</div>
										</div>
										
									

									</div>

								</div><!-- /.col -->
							</div><!-- /.row -->
							
							<div class="center">
			
											<span class="profile-picture">
										    	<img width="180" height="200" class="editable img-responsive" alt=<?php $info_rev->s('denomination')   ?> id="avatar2" src="<?php echo $photo ?>" />
									        </span>	
									    
							</div>
							<div class="center">
								<a class="iframe_pdf" rel=<?php echo $formulaire; ?>><p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>Formulaire de : <?php  $info_rev->s('denomination') ?> </p></a>							
							</div>
									
						</div><!-- /#home -->

							</div><!-- /.row -->

							</div><!-- /#feed -->

						</div>
					</div>
				</div>
			</div>


		</div><!-- /.well -->


	</div><!-- /.user-profile -->