<?php
//Get all Devis info 
$info_objectif_service = new Mobjectif_service();
$action = new TableTools();
$info_objectif_service->id_objectif_service = Mreq::tp('id');
$info_objectif_service->get_objectif_service();

//Set ID of Module with POST id
$info_objectif_service->id_objectif_service = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_objectif_service->Get_detail_objectif_service_show())
{ 	
 	// returne message error red to client 
	exit('3#'.$info_objectif_service->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


$tab_details_objectif_service = view::tab_render('objectif_service', 'Objectif', $add_set=NULL, 'paper-plane-o' , $active = true, 'home');
$tab_liste_devis = view::tab_render('devis', 'Liste Devis', $add_set=NULL, 'bookmark' , false, 'bl');
$tab_liste_factures = view::tab_render('factures', 'Factures', $add_set=NULL, 'file' , false, 'factures');
$tab_liste_encaissement = view::tab_render('encaissements', 'Encaissements', $add_set=NULL, 'money' , false, 'encaissement');


?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php 
		TableTools::btn_action('objectif_service', $info_objectif_service->id_objectif_service, 'detail_bjectif_service');
		TableTools::btn_add('objectif_service','Liste des Objectifs', Null, $exec = NULL, 'reply'); 

		?>

	</div>
</div><!-- /.tableTools-container -->
<div class="page-header">
	<h1>
		Détails Objectif: <?php $info_objectif_service->s('description') . ' - ' .$info_objectif_service->s('id_objectif').' -' ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>

	</h1>
</div><!-- /.page-header -->

<div class="row">
	<?php Mmodul::get_statut_etat_line('objectif_service', $info_objectif_service->g('etat')); ?>
	<div id="main_div">
		<div id="user-profile-2" class="user-profile">
			<div class="tabbable">
				<ul class="nav nav-tabs padding-18">
					<!-- <li class="active">
						<a data-toggle="tab" href="#home">
							<i class="green ace-icon fa fa-money bigger-120"></i>
							Devis
						</a>
					</li> -->
                    
					<?php 
					echo $tab_details_objectif_service['tab_index']; 
					echo $tab_liste_devis['tab_index']; 
					echo $tab_liste_factures['tab_index']; 
					echo $tab_liste_encaissement['tab_index']; 
					
					?>
					
				</ul>

				<div class="tab-content no-border padding-24">
					<?php 
					 

					 if($tab_details_objectif_service['tb_rl'])
					 {
					 	echo $tab_details_objectif_service['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	include 'detail_objectif_v.php';
					 	echo $tab_details_objectif_service['tce'];
					 }

					 if($tab_liste_devis['tb_rl'])
					 {
					 	echo $tab_liste_devis['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	echo $info_objectif_service->get_list_devis_for_objectif();
					 	echo $tab_liste_devis['tce'];
					 }

					 if($tab_liste_factures['tb_rl'])
					 {
					 	echo $tab_liste_factures['tcs'];
					 	
					 	echo $info_objectif_service->get_list_factures_for_objectif();
					 	echo $tab_liste_factures['tce'];
					 }

					 if($tab_liste_encaissement['tb_rl'])
					 {
					 	echo $tab_liste_encaissement['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	echo $info_objectif_service->get_list_encaissemen_for_objectif();
					 	echo $tab_liste_encaissement['tce'];
					 }

					 


					?>

				</div><!-- /.tab-content no-border -->

			</div><!-- /#tabbable -->
		</div><!-- /.user-profile-2 -->
    </div><!-- /#main_div -->
</div><!-- /#main row -->


<!-- page specific plugin scripts -->
<script type="text/javascript">


</script>

