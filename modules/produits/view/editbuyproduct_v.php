<?php 
defined('_MEXEC') or die;

//Get all achat info 
 $info_achat = new Machat();
//Set ID of Module with POST id
 $info_achat->id_achat = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_achat->get_achat_produit())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_achat->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('buyproducts','Liste des achats', MInit::crypt_tp('id',$info_achat->Shw('idproduit',1)), $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier un achat de produit
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

$form = new Mform('editbuyproduct', 'editbuyproduct',$info_achat->Shw('id',1),'buyproducts&'.MInit::crypt_tp('id', $info_achat->Shw('idproduit',1)), '0');
$form->input_hidden('idproduit', $info_achat->Shw('idproduit',1));
$form->input_hidden('id', $info_achat->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//quantité
$qte_array[]  = array('required', 'true', 'Insérez une quantité' );
$qte_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Quantité', 'qte', 'text' ,6, $info_achat->Shw('qte',1), $qte_array);

//Prix achat
$pa_array[]  = array('required', 'true', 'Insérez le prix achat' );
$pa_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Prix achat', 'prix_achat', 'text' ,6, $info_achat->Shw('prix_achat',1), $pa_array);

//Prix vente
$pv_array[]  = array('required', 'true', 'Insérez le prix de vente' );
$pv_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Prix vente', 'prix_vente', 'text' ,6, $info_achat->Shw('prix_vente',1), $pv_array);

//Date d'achat
$array_dachat[]= array('required', 'true', 'Insérer la date achat');
$form->input_date('Date achat', 'date_achat', 4,$info_achat->Shw('date_achat',1), $array_dachat);

//Date de fin de validité
$array_dfinval[]= array('required', 'true', 'Insérer la date de fin de validité');
$form->input_date('Date fin de validité', 'date_validite', 4,$info_achat->Shw('date_validite',1), $array_dfinval);


$form->button('Modifier achat');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
