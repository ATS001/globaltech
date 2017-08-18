<?php 

 $info_gsm= new Mgsm();
 $info_gsm->id_gsm = Mreq::tp('id');
 $info_gsm->get_gsm();

$formulaire =$info_gsm->gsm_info['pj'];
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php  
              TableTools::btn_add('gsm','Liste Stations GSM', Null, NULL, 'reply');    
		?>	

	</div>
</div>
<div class="page-header">
	<h1>
		Détails de la station GSM : <?php  $info_gsm->s('nom_station'); ?> 

		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12">


		<div>
			<div id="user-profile-2" class="user-profile">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#home">
								<i class="green ace-icon fa fa-gsm bigger-120"></i>
								Station GSM 
							</a>
						</li>
						<li>
							<a data-toggle="tab" href="#feed">
								<i class="black ace-icon fa fa-camera bigger-120"></i>
								Photos de la station
							</a>
						</li>

						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
						


								<div class="col-xs-12 col-sm-6">
									<h4 class="blue">
										<span class="middle">Informations de la station</span>
										
									</h4>

									<div class="profile-user-info">
										

										<div class="profile-info-row">
											
											<div class="profile-info-name"> Nom</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('nom_station') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
										
											<div class="profile-info-name">Adresse</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('adresse') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Ville</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('villes') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											
											<div class="profile-info-name">Longitude</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('longi') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											
											<div class="profile-info-name">Latitude</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('latit') ?></span>
											</div>
										</div>
								

										<div class="profile-info-row">
											<div class="profile-info-name"> Système d'interconnexion </div>
											<?php  
											$interconnexions=' ';
												  if ($info_gsm->Shw('bh_fh',1) == 1)
													{
														$interconnexions='<span class="badge badge-yellow">F.H</span>';
													}
												  if ($info_gsm->Shw('bh_vsat',1) == 1)
													{
														$interconnexions =$interconnexions.'<span class="badge badge-success">VSAT</span>';
													}	
												  if ($info_gsm->Shw('bh_fibre',1) == 1)
													{
														$interconnexions =$interconnexions.'<span class="badge badge-warning">Fibre</span>';
													}
											 ?>
											<div class="profile-info-value">
												<span><?php  echo $interconnexions;  ?></span>
											</div>
										</div>

											<div class="profile-info-row">

											<div class="profile-info-name"> Technolgies déployées </div>
											<?php  
											 $technologies=' ';
												  if ($info_gsm->Shw('tech_2g',1) == 1)
													{
														$technologies='<span class="badge badge-pink">2G</span>';
													}
												  if ($info_gsm->Shw('tech_3g',1) == 1)
													{
														$technologies =$technologies.'<span class="badge badge-purple">3G</span>';
													}	
												  if ($info_gsm->Shw('tech_4g',1) == 1)
													{
														$technologies =$technologies.'<span class="badge badge-info">4G</span>';
													}
												  if ($info_gsm->Shw('tech_cdma',1) == 1)
													{
														$technologies =$technologies.'<span class="badge badge-danger">CDMA</span>';
													}
											 ?>


											<div class="profile-info-value">
												<span><?php  echo $technologies;  ?></span>
											</div>
										</div>

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-6">
									<h4 class="blue">
										<span class="middle">Informations Support et Energie</span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name">Type de Support</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('type_support') ?></span>
											</div>
										</div>
										
										<div class="profile-info-row">
											<div class="profile-info-name">Site Partagé</div>

											<?php  
												  if ($info_gsm->Shw('shared_site',1) == 1)
													{
														$shared_site='OUI';
													}
													else
													{
														$shared_site='NON';
													}
											 ?>

											<div class="profile-info-value">
												<span><?php  echo $shared_site ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Opérateur de partage</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm->s('oper_share') ?></span>
											</div>
										</div>
									
										<div class="profile-info-row">
											<div class="profile-info-name"> Groupe Electrogène </div>

											<?php  
												  if ($info_gsm->Shw('power_generator',1) == 1)
													{
														$power_generator='OUI';
													}
													else
													{
														$power_generator='NON';
													}
											 ?>
											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  echo $power_generator; ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Réseau Eléctrique </div>

											<?php  
												  if ($info_gsm->Shw('power_company',1) == 1)
													{
														$power_company='OUI';
													}
													else
													{
														$power_company='NON';
													}
											?>
											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  echo $power_company; ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Energie Solaire </div>

											<?php  
												  if ($info_gsm->Shw('power_solar',1) == 1)
													{
														$power_solar='OUI';
													}
													else
													{
														$power_solar='NON';
													}
											?>
											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  echo $power_solar  ; ?></span>
											</div>
										</div>

									</div>
								</div><!-- /.col -->
							
							</div><!-- /.row -->
							
							<div class="center">
								<a class="iframe_pdf" rel=<?php echo $formulaire; ?>><p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>Formulaire de : <?php  $info_gsm->s('nom_station') ?> </p></a>							
							</div>

						</div><!-- /#home -->
						
						<div id="feed" class="tab-pane">
							 <?php echo MInit::get_pictures_gallery_table($info_gsm->Shw('pj_images',1) , true); ?>
					    </div>

					</div>

				</div>
			</div>


		</div><!-- /.well -->


	</div><!-- /.user-profile -->
