<?php 
$info_prm= new Mprms();
$info_prm->id_prm =Mreq::tp('id');
$info_prm->get_prm();

$formulaire = $info_prm->prm_info['pj'];
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php 
              TableTools::btn_add('prm', 'Liste Permissionnaires', Null, $exec = NULL, 'reply');      
		 ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Détails du permissionnaire : <?php  $info_prm->s('r_social');?> 
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
								<i class="green ace-icon fa fa-user bigger-120"></i>
								Permissionnaire 
							</a>
						</li>

						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
						


								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Renseignements du permissionnaire</span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name"> Raison sociale </div>

											<div class="profile-info-value">
												<span><?php  $info_prm->s('r_social')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Sigle </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('sigle') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Catégorie</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('cat') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Secteur d'activité</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('sect') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Registre de commerce</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('rc') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name">N° d'Identifaication Fiscale</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('nif') ?></span>
											</div>
										</div>
										

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Coordonées du permissionnaire</span>
										
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name">Groupe Multi national</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('typ_group') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name">Pays du siège mère</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('pays') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name">Adresse</div>

											<div class="profile-info-value">
												<span><?php  $info_prm->s('adresse')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Boite Postal </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('bp') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Ville</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('villes') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Email </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('email') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Téléphone </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('tel') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Fax </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('fax') ?></span>
											</div>
										</div>

									</div>

								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-4">
									<h4 class="blue">
										<span class="middle">Personne à contacter</span>
										
									</h4>

									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name"> Nom & Prénom </div>

											<div class="profile-info-value">
												<span><?php  $info_prm->s('nom_p')  ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Adresse Personne </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('adresse_p') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Qualite</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('qualite_p') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Nationalité </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('nationalite') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> N° Téléphone </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('tel_p') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Email Personnel </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $info_prm->s('email_p') ?></span>
											</div>
										</div>

									</div>

								</div><!-- /.col -->

							</div><!-- /.row -->
 							
							<div class="center">
								<a class="iframe_pdf" rel=<?php echo $formulaire; ?>><p class="lead"><i class="ace-icon fa fa-file-pdf-o red"></i>Formulaire de : <?php  $info_prm->s('r_social') ?> </p></a>							
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