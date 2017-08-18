<?php 

 $info_gsm_secteur= new Mgsm_secteur();
 $info_gsm_secteur->id_gsm_secteur = Mreq::tp('id');
 $info_gsm_secteur->get_gsm_secteur();

 //Get id technologie gsm
 $id_technologie=$info_gsm_secteur->Shw('id_technologie',1);
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php  
             TableTools::btn_add('gsm_secteur', 'Liste des secteurs', MInit::crypt_tp('id',$id_technologie), $exec = NULL, 'reply'); 	 
		?>	

	</div>
</div>
<div class="page-header">
	<h1>
		Détails du secteur GSM : <?php  $info_gsm_secteur->s('num_secteur'); ?> 

		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12" >


		<div>
			<div id="user-profile-2" class="user-profile">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#home">
								<i class="green ace-icon fa fa-bullhorn bigger-120"></i>
								Secteur GSM 
							</a>
						</li>
						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
						
								<div class="col-xs-12 col-sm-3">
								</div>

								<div class="col-xs-12 col-sm-6">
									<h4 class="blue">
										<span class="middle">Renseignements du secteur</span>
										
									</h4>

									<div class="profile-user-info">
										
										<div class="profile-info-row">
											
											<div class="profile-info-name">Secteur</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span>Secteur <?php  $info_gsm_secteur->s('num_secteur') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											
											<div class="profile-info-name"> H.B.A </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_secteur->s('hba') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
										
											<div class="profile-info-name">Azimut</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_secteur->s('azimuth') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Tilt mécanique </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_secteur->s('tilt_mecanique') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											
											<div class="profile-info-name">Tilt électrique</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_secteur->s('tilt_electrique') ?></span>
											</div>
										</div>

								</div><!-- /.col -->
								
								<div class="col-xs-12 col-sm-3">
								</div>
								
							</div><!-- /.row -->
							
							

						</div><!-- /#home -->
						

					</div>

				</div>
			</div>


		</div><!-- /.well -->


	</div><!-- /.user-profile -->
