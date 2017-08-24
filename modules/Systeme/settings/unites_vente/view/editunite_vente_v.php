<?php
//Get all categorie info
 $info_unite_vente= new Munite_vente();
//Set ID of Module with POST id
 $info_unite_vente->id_unite_vente = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_unite_vente->get_unite_vente())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_unite_vente->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php 
              TableTools::btn_add('types_produits', 'Liste des types', Null, $exec = NULL, 'reply');      
		 ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier unité de vente 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_unite_vente->Shw('unite_vente',1).' -'.$info_unite_vente->id_unite_vente.'-)';?>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP ; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
			 	
<?php

$form = new Mform('editunite_vente', 'editunite_vente', $info_unite_vente->Shw('id',1), 'unites_vente','1');
$form->input_hidden('id', $info_unite_vente->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//Unité de vente
$unite_vente_array[]  = array('required', 'true', 'Insérez une unité' );
$form->input('Unité de vente', 'unite_vente', 'text' ,6, $info_unite_vente->Shw('unite_vente',1), $unite_vente_array);


//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>




