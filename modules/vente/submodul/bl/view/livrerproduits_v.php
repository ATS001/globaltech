<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 02-05-2018
//View
$bl= new Mbl();
$bl->id_bl = Mreq::tp('id');
$bl->get_bl();
$bl->bl_info;

$bl->get_details_bl();
$d_bl = $bl->d_bl_info;

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">


		<?php 
		TableTools::btn_add('bl', 'Liste bl', Null, $exec = NULL, 'reply');      
		?>		
	</div>
</div>
<div class="page-header">
	<h1>
		Détails du Bon de Livraison:     <?php $bl->s('reference'); ?> 

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
								Détails 
							</a>
						</li>


					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
								<div class="col-xs-12 col-sm-6">
									<h4 class="blue">
										<span class="middle">Renseignements BL</span>
									</h4>
									<div class="widget-toolbar hidden-480">
								
								        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'bl') ?>" data="<?php echo MInit::crypt_tp('id', $bl->id_bl) ?>">
										    <i class="ace-icon fa fa-print"></i>
									    </a>

								    </div>       
								
								

								<!-- /section:pages/invoice.info -->
							</div>

									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name">Réference</div>
											<div class="profile-info-value">
												<span><?php  $bl->s("reference")  ?></span>
											</div>
										</div>
									</div>
									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name">Client</div>
											<div class="profile-info-value">
												<span><?php  $bl->s("client")  ?></span>
											</div>
										</div>
									</div>
									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name">Projet</div>
											<div class="profile-info-value">
												<span><?php  $bl->s("projet")  ?></span>
											</div>
										</div>
									</div>
									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name">Réf BDC client</div>
											<div class="profile-info-value">
												<span><?php  $bl->s("ref_bc")  ?></span>
											</div>
										</div>
									</div>
									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name">Réf Devis</div>
											<div class="profile-info-value">
												<span><?php  $bl->s("refdevis")  ?></span>
											</div>
										</div>
									</div>
									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name">Date BL</div>
											<div class="profile-info-value">
												<span><?php  $bl->s("date_bl")  ?></span>
											</div>
										</div>
									</div>



								</div><!-- /.col -->
							</div>


						</div><!-- /#home -->

					</div><!-- /.row -->
					<div class="space"></div>

									<div>

										<span>
                                    <?php
                                    if ($d_bl == null)
                                        echo '<B>Aucune ligne trouvée</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px">
                                            <th>
                                                Item
                                            </th>
                                            <th>
                                                Référence
                                            </th>
                                            <th>
                                                Description
                                            </th>
                                            <th>
                                                Qte commandée
                                            </th>
                                            <th>
                                                Qte livrée
                                            </th>
                                            <th>
                                                Reste à livrer
                                            </th>
                                            <?php
//                                                                                         if($bl == null)
//                                                                                             echo '<B>Aucun enregistrement trouvé</B> ';
//                                                                                         else {
                                            foreach ($d_bl as $bon_livr) {
                                                ?>
                                                <tr>	
                                                    <td>
                                                        <span><?php echo $bon_livr['1']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $bon_livr['4']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $bon_livr['5']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $bon_livr['6']; ?></span>
                                                    </td>
                                                     <td>
                                                        <span><?php echo $bon_livr['7']; ?></span>
                                                    </td>
                                                     <td>
                                                        <span><?php echo $bon_livr['8']; ?></span>
                                                        <?php
$form = new Mform('livrerproduits', 'livrerproduits', '1', 'bl', '0', null);


//reference ==> 
	$array_reference[]= array("required", "true", "Insérer reference ...");
	$form->input(null, "reference", "text" ,"9", null, $array_reference , null, $readonly = null);
//Form render
$form->render();

?>
                                                    </td>
                                                </tr>


                                                <?php
                                            }
                                        }
                                        ?>

                                    </table>
                                </span>
										
									</div>
									<?php 

$form->button('Enregistrer');
//Form render
$form->render();
?>

				</div><!-- /#feed -->

			</div>
		</div>
	</div>
</div>


</div><!-- /.well -->


</div><!-- /.-profile -->

