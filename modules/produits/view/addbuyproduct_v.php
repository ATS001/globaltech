	<?php

        //chck if called with client ID then suggest task for after exec
//id_clnt crypted => id client
//tsk_aft crypted => Task after exec
//
$after_exec     = 'produits';
$id_produit        = MReq::tp('id_produit');
$tsk_aft        = MReq::tp('tsk_aft');
$name_client    = null;
$title          = 'Ajouter Achat';
$btn_return_txt = 'Liste des achats';
$btn_task       = 'contrats';
$btn_setting    = null;

if($id_produit != null && $tsk_aft != null){
    if(!MInit::crypt_tp('id_produit', null, 'D')){
          Minit::big_message('ID produit n\'est pas correcte', 'danger');
          die();
    }
    if(!MInit::crypt_tp('tsk_aft', null, 'D')){
          Minit::big_message('Erreur Système #aft_exec', 'danger');
          die();
    }
    $after_exec = $tsk_aft.'&'.MInit::crypt_tp('id', $id_produit);
    $name_produit = Machat::get_produit_ref($id_produit);
    $title .=  ' pour le produit :'.$name_produit;
    $btn_return_txt = 'Détail Produit '.$name_produit;
    $btn_task = $tsk_aft;
    $btn_setting = MInit::crypt_tp('id', $id_produit);
}

	 
	 $info_produit = new Mproduit();
	 $info_produit->id_produit = Mreq::tp('id_produit');
	 
	 
	if(!MInit::crypt_tp('id_produit', null, 'D')  or !$info_produit->get_produit())
	 { 	

	 	exit('0#'.$info_produit->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	 }

	    $id_produit = Mreq::tp('id_produit');
	   
	?>

	<div class="pull-right tableTools-container">
		<div class="btn-group btn-overlap">
			                 
<?php TableTools::btn_add($btn_task, $btn_return_txt, $btn_setting, $exec = NULL, 'reply'); ?>

		
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

	$form = new Mform('addbuyproduct', 'addbuyproduct','', $after_exec, '0');
	$form->input_hidden('idproduit',   $id_produit);
        
	//quantité
	$qte_array[]  = array('number', 'true', 'Entrez un nombre valide' );
	$form->input('Quantité', 'qte', 'text' ,6, null, $qte_array);

        //Serial number	
	$form->input('Serial number', 'serial_number', 'text' ,6, null, NULL);
        
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
	$form->input_date('Date de validité', 'date_validite', 4, date('d-m-Y'), '');
        
        //Numéro de série
                $form->input('N° de série', 'pj', 'file', 8, null, null);
                $form->file_js('pj', 1000000, 'xls');


	$form->button('Enregistrer achat');

	//Form render
	$form->render();
	?>
				</div>
			</div>
		</div>
	</div>
