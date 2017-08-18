<?php 
 $info_instal= new Minstal();
 $info_instal->id_instal = Mreq::tp('id');
 $info_instal->get_instal();

$photo = Minit::get_file_archive($info_instal->instal_info['pj_image']);
$formulaire = $info_instal->instal_info['pj'];
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php 
              TableTools::btn_add('installateurs', 'Liste Installateurs', Null, $exec = NULL, 'reply');      
		 ?>		
	</div>
</div>
<div class="page-header">
	<h1>
		Détails de l'installateur : <?php  $info_instal->s('denomination'); ?> 

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
								Installateur 
							</a>
						</li>

						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
						


								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Renseignements de l'installateur</span>
										
									</h4>

									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name"> Type de l'installateur</div>

											<div class="profile-info-value">
												<span><?php  $info_instal->s('type_instal')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<?php  
												  if ($info_instal->Shw('type_instal',1)== 'PHYSIQUE')
													{
														$denomination='Nom & Prénom';
													}
													else
													{
														$denomination='Raison Sociale';
													}
											 ?>
											<div class="profile-info-name"> <?php echo $denomination ?> </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('denomination') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<?php  
												  if ($info_instal->Shw('type_instal',1)== 'PHYSIQUE')
													{
														$piece_identite='CIN';
													}
													else
													{
														$piece_identite='Registre de Commerce';
													}
											 ?>
											<div class="profile-info-name"><?php echo $piece_identite ?></div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('piece_identite') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Numéro d'agrément</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('num_agrement') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<?php  
												  if ($info_instal->Shw('type_instal',1)== 'PHYSIQUE')
													{
														$qualification='Qualification de l\'installateur';
													}
													else
													{
														$qualification='Secteur d\'activité';
													}
											 ?>
											<div class="profile-info-name"><?php echo $qualification ?></div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('qualification') ?></span>
											</div>
										</div>
										

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Coordonées de l'installateur</span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name">Ville</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('villes') ?></span>
											</div>
										</div>
										
										<div class="profile-info-row">
											<div class="profile-info-name">Adresse</div>

											<div class="profile-info-value">
												<span><?php  $info_instal->s('adresse')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Email</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('email') ?></span>
											</div>
										</div>
									
										<div class="profile-info-row">
											<div class="profile-info-name"> Téléphone </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('tel') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Fax </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_instal->s('fax') ?></span>
											</div>
										</div>

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Activités de l'installateur </br></span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name"> Installation VSAT </div>
											<?php  
												  if ($info_instal->Shw('vsat',1) == 1)
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
												  if ($info_instal->Shw('uhf_vhf',1) == 1)
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
												  if ($info_instal->Shw('gsm',1) == 1)
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
												  if ($info_instal->Shw('blr',1) == 1)
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
										    	<img width="180" height="200" class="editable img-responsive" alt=<?php $info_instal->s('denomination')   ?> id="avatar2" src="<?php echo $photo ?>" />
									        </span>
							
							</div>
							<div class="center">
								<a class="iframe_pdf" rel=<?php echo $formulaire; ?>><p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>Formulaire de : <?php  $info_instal->s('denomination')  ?> </p></a>							
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