	<?php

	 
	 $info_produit = new Mproduit();
	 $info_produit->id_produit = Mreq::tp('id');
	 
	 
	if(!MInit::crypt_tp('id', null, 'D')  or !$info_produit->get_produit())
	 { 	

	 	exit('0#'.$info_produit->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
	 }

	    $id_produit = Mreq::tp('id');
	   
	?>

	<div class="pull-right tableTools-container">
		<div class="btn-group btn-overlap">
						
			<?php TableTools::btn_add('buyproducts','Liste des achats', MInit::crypt_tp('id', $id_produit), $exec = NULL, 'reply'); ?>
						
		</div>
	</div>
	<div class="page-header">
		<h1>
			Ajouter un achat de produit
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
			</small>
		</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<div class="clearfix">
				
			</div>
			<div class="table-header">
				Formulaire: "<?php echo ACTIV_APP; ?>"

			</div>
			<div class="widget-content">
				<div class="widget-box">
					
	<?php

	$form = new Mform('addbuyproduct', 'addbuyproduct','', 'buyproducts&'.MInit::crypt_tp('id', $id_produit), '0');
	$form->input_hidden('idproduit',   $id_produit);

	//quantité
	$qte_array[]  = array('required', 'true', 'Insérez une quantité' );
	$qte_array[]  = array('number', 'true', 'Entrez un nombre valide' );
	$form->input('Quantité', 'qte', 'text' ,6, null, $qte_array);

	//Prix achat
	$pa_array[]  = array('required', 'true', 'Insérez le prix achat' );
	$pa_array[]  = array('number', 'true', 'Entrez un nombre valide' );
	$form->input('Prix achat', 'prix_achat', 'text' ,6, null, $pa_array);

	//Prix vente
	$pv_array[]  = array('required', 'true', 'Insérez le prix de vente' );
	$pv_array[]  = array('number', 'true', 'Entrez un nombre valide' );
	$form->input('Prix vente', 'prix_vente', 'text' ,6, null, $pv_array);

	//Date d'achat
	$array_dachat[]= array('required', 'true', 'Insérer la date achat');
	$form->input_date('Date achat', 'date_achat', 4, date('d-m-Y'), $array_dachat);

	//Date de fin de validité
	$array_dfinval[]= array('required', 'true', 'Insérer la date de fin de validité');
	$form->input_date('Date de validité', 'date_validite', 4, date('d-m-Y'), $array_dfinval);


	$form->button('Enregistrer achat');

	//Form render
	$form->render();
	?>
				</div>
			</div>
		</div>
	</div>
