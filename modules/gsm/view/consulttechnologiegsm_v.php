<?php 

 $info_gsm_technologie= new Mgsm_technologie();
 $info_gsm_technologie->id_technologie = Mreq::tp('id');
 $info_gsm_technologie->get_technologie();

 //Get id gsm station
 $id_gsm=$info_gsm_technologie->Shw('id_site_gsm',1);
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php  
             TableTools::btn_add('gsm_technologie','Liste Technologies GSM', MInit::crypt_tp('id',$id_gsm), $exec = NULL, 'reply'); 	 
		?>	

	</div>
</div>
<div class="page-header">
	<h1>
		Détails de la technologie GSM : <?php  $info_gsm_technologie->s('num_serie'); ?> 

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
								<i class="green ace-icon fa fa-laptop bigger-120"></i>
								Technologie GSM 
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
										<span class="middle">Renseignements de la technologie</span>
										
									</h4>

									<div class="profile-user-info">
										
										<div class="profile-info-row">
											
											<div class="profile-info-name">Technologie</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_technologie->s('libelle') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											
											<div class="profile-info-name"> Marque BTS</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_technologie->s('marque_bts') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
										
											<div class="profile-info-name">Numéro de Série</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_technologie->s('num_serie') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Modèle Antenne</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_technologie->s('modele_antenne') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											
											<div class="profile-info-name">Nombre Radios</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_gsm_technologie->s('nbr_radio') ?></span>
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
