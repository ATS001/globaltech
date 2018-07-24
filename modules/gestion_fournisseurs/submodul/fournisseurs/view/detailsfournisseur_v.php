<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => View

//Get all contrats_frn info 
 $fournisseur= new Mfournisseurs();
//Set ID of Module with POST id
 $fournisseur->id_fournisseur = Mreq::tp('id');

 $fournisseur->get_list_contrats();
 $fournisseur_ctr = $fournisseur->contrats_info;

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$fournisseur->get_fournisseur())
{ 	
 	// returne message error red to fournisseur 
	exit('3#'.$fournisseur->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
$pj    	 = $fournisseur->fournisseur_info['pj'];
$photo   = Minit::get_file_archive($fournisseur->fournisseur_info['pj_photo']);

$tab_contrats_frn = view::tab_render('contrats_frn', 'Contrats', $add_set=NULL, 'book' , $active = true, 'contrats_frn');

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_action('fournisseurs', $fournisseur->id_fournisseur, 'detailsfournisseur');
              TableTools::btn_add('fournisseurs', 'Liste fournisseurs', Null, $exec = NULL, 'reply');      
		 ?>	
         

	</div>
</div>
<div class="page-header">
	<h1>
            Détails du fournisseur: <?php $fournisseur->s('reference')?>    <?php $fournisseur->s('denomination'); ?> 
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- ajax layout which only needs content area -->
<div class="row">
<?php Mmodul::get_statut_etat_line('fournisseurs', $fournisseur->g('etat'));
//echo $actions;
?>

 <div>
            <div id="user-profile-2" class="user-profile">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-18">
                        <li class="active">
                            <a data-toggle="tab" href="#home">
                                <i class="green ace-icon fa fa-user bigger-120"></i>
                                Fournisseur 
                            </a>
                        </li>

                        <?php
                    	if($tab_contrats_frn['tb_rl'])
					 	{ ?>
                         <li>
                            <a data-toggle="tab" href="#contrats_frn">
                                <i class="orange ace-icon fa fa-book bigger-120"></i>
                                Contrats
                            </a>
                        </li> 
                        <?php
                    	}
					 	?>
                   <!--  <?php 
					echo $tab_details_frn['tab_index']; 
					echo $tab_contrats_frn['tab_index']; 
					?> -->

                    </ul>

            <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">

                        <div class="col-sm-10 col-sm-offset-1">
                <!-- #section:pages/invoice -->
                <div class="widget-box transparent">
                    <div class="widget-header widget-header-large">
                        
                        <h3 class="widget-title grey lighter">
                           <?php if ($photo != null)
							{ 
							?>

							
							
											<span class="profile-picture">
										    	<img width="50" height="50" class="editable img-responsive" alt=<?php $fournisseur->s('denomination')   ?> id="avatar2" src="<?php echo $photo ?>" />
									        </span>	

							
							<?php 
						    }
							?>
							<i class="ace-icon fa fa-adress-card-o green"></i>
							Fournisseur : <?php echo $fournisseur->s('reference')?>
                        </h3>
                         <?php
                                    if ($fournisseur->g('etat') == Msetting::get_set('etat_fournisseur', 'fournisseur_bloque') ) {
                                        ?>
                                       <div>
                                            <b class="red pull-right margin-left: 30px"> <?php echo $fournisseur->s('date_blocage')?>&nbsp;&nbsp;&nbsp;</b> 
                                            <b class="grey pull-right"> &nbsp;&nbsp;&nbsp;Date :&nbsp;</b>

                                            <b class="red pull-right margin-left: 30px"> <?php echo $fournisseur->s('commentaire')?></b> 
                                            <b class="grey pull-right">&nbsp;&nbsp;&nbsp; Commentaire :&nbsp;</b>
                                           
                                            <b class="red pull-right margin-left: 30px"> <?php echo $fournisseur->s('motif')?></b> 
                                            <b class="grey pull-right"> Motif de blocage:&nbsp;</b>   
                                        </div>
                        <?php 
                            }
                        ?>

                         <?php if( $pj != null){
                        ?>
                         <div class="widget-toolbar hidden-480">
							<a href="#" class="iframe_pdf" rel=<?php echo $pj; ?>>
								<i class="ace-icon fa fa-print"></i>
							</a>
						</div>       
                       <?php 
                   							    } 
                   	   ?>
                        

                        <!-- /section:pages/invoice.info -->
                    </div><!-- #widget header -->

                    <div class="widget-body">
                        <div class="widget-main padding-24">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                            <b>Renseignements Fournisseur</b>
                                        </div>
                                    </div>

                                    <div>
                                        
                                        <ul class="list-unstyled spaced">
											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Référence                                                                                               
                                                  <b class="blue pull-right"> <?php  $fournisseur->s('reference')  ?> </b>                                        
									
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Dénomination                                                                                               
                                                  <b class="blue pull-right"> <?php echo $fournisseur->s('denomination');?> </b>                                        
									
											</li>										

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Raison Sociale

                                                    <b class="blue pull-right"><?php echo $fournisseur->s('r_social');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Registre Commerce

                                                    <b class="blue pull-right"><?php echo $fournisseur->s('r_commerce');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> N° Identifiant Fiscal

                                                    <b class="blue pull-right"><?php echo $fournisseur->s('nif');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Pays

                                                    <b class="blue pull-right"><?php echo $fournisseur->s('pays');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right blue"></i> Ville

                                                    <b class="blue pull-right"><?php echo $fournisseur->s('ville');?></b>
											</li>

										</ul>
                                    </div>
                                </div><!-- /.col sm 6-->

                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                            <b>Informations du Représentant</b>
                                        </div>
                                    </div>

                                    <div>
                                        <ul class="list-unstyled  spaced">
											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Nom
                                                   <b class="blue pull-right"><?php echo $fournisseur->s('nom');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Prénom
                                                   <b class="blue pull-right"><?php echo $fournisseur->s('prenom');?></b>
											</li>


											<li>
												<i class="ace-icon fa fa-caret-right green"></i>Civilité 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('civilite');?></b>
											</li>
											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Adresse 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('adresse');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Téléphone 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('tel');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Fax 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('fax');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Boite postale 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('bp');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Email 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('email');?></b>
											</li>

											<li>
												<i class="ace-icon fa fa-caret-right green"></i> Devise 
                                                    <b class="blue pull-right"><?php echo $fournisseur->s('devise');?></b>
											</li>

										</ul>
                                    </div>
                                </div><!-- /.col sm 6-->

                            </div><!-- /.row -->
                        </div><!--widget main-->
                    </div><!--widget body-->
                </div><!--widget-box transparent-->

                        </div><!-- /.col sm 10 -->

                        </div><!-- /#home -->
                     
                     <?php
                     if($tab_contrats_frn['tb_rl'])
					 { ?>
					 <div id="contrats_frn" class="tab-pane">
                            <div class="profile-feed row">
                               
                                      <span>
                                    <?php
                                    if ($fournisseur_ctr == null)
                                        echo '<B>Aucun contrat trouvé</B> ';
                                    else {
                                        ?>
                                    <table class="table table-striped table-bordered table-hover" style="width: 800px align:center">
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                ID
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Réference
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Commentaire
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date Effet
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date Fin
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Document
                                            </th>

                                    <?php
                                            foreach ($fournisseur_ctr as $ctr) {
                                                ?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ctr['0']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ctr['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ctr['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ctr['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ctr['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ctr['5']; ?></span>
                                                    </td>

                                                    <td style="text-align: center;" >  
                                                        <?php if( $ctr['6'] != null){ ?>
                                                           <a href="#" class="iframe_pdf" rel=<?php echo $ctr[6]; ?>>
                                                                <i class="ace-icon fa fa-print"></i>
                                                            </a>    
                                                            <?php } ?>
                                                    </td>
                                                </tr>
                                    <?php } } ?>

                                    </table>
                                </span> 

                                 

                            </div><!-- /. contrats_frn row -->

                        </div><!-- /#contrats_frn  -->


					 <?php
					 }
					 ?>

                       

                    </div><!-- /#tab-content -->
                    </div><!-- /#tattable -->
                </div>
            </div><!-- /# user profile -->
        </div>


</div><!-- /.row -->

<!-- page specific plugin scripts -->
<script type="text/javascript">
	var scripts = [null, null]
	$('.page-content-area').ace_ajax('loadScripts', scripts, function() {
	  //inline scripts related to this page
	});
</script>