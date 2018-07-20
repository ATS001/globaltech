<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//View
 $ticket_frs= new Mticket_frs();
 $ticket_frs->id_ticket_frs = Mreq::tp('id');
 $ticket_frs->get_ticket_frs();
 ?>
 <div class="pull-right tableTools-container">
 	<div class="btn-group btn-overlap">


 		<?php 
 		TableTools::btn_add('ticket_frs', 'Liste ticket_frs', Null, $exec = NULL, 'reply');      
 		?>		
 	</div>
 </div>
 <div class="page-header">
 	<h1>
 		Détails du ticket_frs:     <?php $ticket_frs->s('id'); ?> 

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
 								ticket_frs 
 							</a>
 						</li>


 					</ul>

 					<div class="tab-content no-border padding-24">
 						<div id="home" class="tab-pane in active">
 							<div class="row">
                                <div class="col-xs-12 col-sm-6">
 									<h4 class="blue">
 										<span class="middle">Renseignements ticket_frs</span>
 									</h4>
 									
 										<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Fournisseur</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("id_fournisseur")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Date incident</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("date_incident")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Nature incident</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("nature_incident")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Description</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("description")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Prise en charge Fournisseur</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("prise_charge_frs")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Prise en charge Globaltech</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("prise_charge_glbt")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Technicien</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("id_technicien")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Date affectation</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("date_affectation")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Code clôture</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("code_cloture")  ?></span>
 									</div>
 								</div>
 							</div>
	<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Observation</div>
 									<div class="profile-info-value">
 										<span><?php  $ticket_frs->s("observation")  ?></span>
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

