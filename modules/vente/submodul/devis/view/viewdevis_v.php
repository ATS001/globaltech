<?php
//Get all Devis info 
$info_devis = new Mdevis();
$action = new TableTools();
$info_devis->id_devis = Mreq::tp('id');
$info_devis->get_devis();

//Set ID of Module with POST id
$info_devis->id_devis = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_devis->Get_detail_devis_show())
{ 	
 	// returne message error red to client 
	exit('3#'.$info_devis->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


$tab_details_devis = view::tab_render('devis', 'Devis', $add_set=NULL, 'money' , $active = true, 'home');
$tab_liste_bl = view::tab_render('bl', 'Bons de Livraison', $add_set=NULL, 'money' , false, 'bl');
$tab_liste_factures = view::tab_render('factures', 'Factures', $add_set=NULL, 'money' , false, 'factures');
$tab_liste_encaissement = view::tab_render('encaissements', 'Encaissements', $add_set=NULL, 'money' , false, 'encaissement');

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php 
		TableTools::btn_action('devis', $info_devis->id_devis, 'viewdevis');
		TableTools::btn_add('devis','Liste des Devis', Null, $exec = NULL, 'reply'); 

		?>

	</div>
</div><!-- /.tableTools-container -->
<div class="page-header">
	<h1>
		Détails Devis: <?php $info_devis->s('reference')?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>

	</h1>
</div><!-- /.page-header -->

<div class="row">
	<?php Mmodul::get_statut_etat_line('devis', $info_devis->g('etat')); ?>
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
					echo $tab_details_devis['tab_index']; 
					echo $tab_liste_bl['tab_index']; 
					echo $tab_liste_factures['tab_index']; 
					echo $tab_liste_encaissement['tab_index']; 
					?>
					
				</ul>

				<div class="tab-content no-border padding-24">
					<?php 
					 

					 if($tab_details_devis['tb_rl'])
					 {
					 	echo $tab_details_devis['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	include 'detailsdevis_v.php';
					 	echo $tab_details_devis['tce'];
					 }

					 if($tab_liste_bl['tb_rl'])
					 {
					 	echo $tab_liste_bl['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	echo $info_devis->get_list_bl();
					 	echo $tab_liste_bl['tce'];
					 }

					 if($tab_liste_factures['tb_rl'])
					 {
					 	echo $tab_liste_factures['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	echo 'Liste des factures';
					 	echo $tab_liste_factures['tce'];
					 }

					 if($tab_liste_encaissement['tb_rl'])
					 {
					 	echo $tab_liste_encaissement['tcs'];
					 	//Content (includ file - simple string - function return string)
					 	echo 'Liste des encaissement';
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

