



<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: objectif_commercial
//Created : 11-11-2018
//View
 $objectif_commercial= new Mobjectif_commercial();
 $objectif_commercial->id_objectif_commercial = Mreq::tp('id');
 $objectif_commercial->get_objectif_commercial();
 
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$objectif_commercial->get_objectif_commercial())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_objectifs->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 ?>
 <div class="pull-right tableTools-container">
 	<div class="btn-group btn-overlap">


 		<?php 
 		TableTools::btn_add('objectif_commercial', 'Liste objectif_commercial', Null, $exec = NULL, 'reply');      
 		?>		
 	</div>
 </div>
 <div class="page-header">
 	<h1>
 		Détails du objectif_commercial :   -  <?php $objectif_commercial->s('id'); ?> - 

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
 								<i class="green ace-icon fa fa-installer bigger-120"></i>
 								objectif_commercial 
 							</a>
 						</li>
 						
 						
 					</ul>
 					
 					<div class="tab-content no-border padding-24">
 						<div id="home" class="tab-pane in active">
 							<div class="row">
 								<div class="col-xs-12 col-sm-6">
 									<h4 class="blue">
 										<span class="middle">Renseignements objectif_commercial</span>
 									</h4>
 									
 									<div class="profile-user-info">
 										<div class="profile-info-row">
 											<div class="profile-info-name">Désignation</div>
 											<div class="profile-info-value">
 												<span><?php  $objectif_commercial->s("description")  ?></span>
 											</div>
 										</div>
 									</div>
 									<div class="profile-user-info">
 										<div class="profile-info-row">
 											<div class="profile-info-name">Objectif</div>
 											<div class="profile-info-value">
 												<span><?php  $objectif_commercial->s("objectif")  ?></span>
 											</div>
 										</div>
 									</div>
 									<div class="profile-user-info">
 										<div class="profile-info-row">
 											<div class="profile-info-name">Réalisation</div>
 											<div class="profile-info-value">
 												<span><?php  $objectif_commercial->s("realise")  ?></span>
 											</div>
 										</div>
 									</div>
 									<div class="profile-user-info">
 										<div class="profile-info-row">
 											<div class="profile-info-name">Service</div>
 											<div class="profile-info-value">
 												<span><?php  $objectif_commercial->s("service")  ?></span>
 											</div>
 										</div>
 									</div>
 									<div class="profile-user-info">
 										<div class="profile-info-row">
 											<div class="profile-info-name">Date début</div>
 											<div class="profile-info-value">
 												<span><?php  $objectif_commercial->s("date_s")  ?></span>
 											</div>
 										</div>
 									</div>
 									<div class="profile-user-info">
 										<div class="profile-info-row">
 											<div class="profile-info-name">Date fin</div>
 											<div class="profile-info-value">
 												<span><?php  $objectif_commercial->s("date_e")  ?></span>
 											</div>
 										</div>
 									</div>
 									
 									
 									
 								</div><!-- /.col -->
 							</div>
 							
 							
 						</div><!-- /#home -->
 						
 					</div><!-- /.row -->
 					
 				</div><!-- /#feed -->
 				
 			</div>
 		</div>
 	</div>
 </div>
 
 
</div><!-- /.well -->


</div><!-- /.-profile -->

