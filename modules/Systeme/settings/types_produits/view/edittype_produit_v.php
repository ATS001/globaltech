<?php
//Get all categorie info
 $info_type_produit= new Mtype_produit();
//Set ID of Module with POST id
 $info_type_produit->id_type_produit = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_type_produit->get_type_produit())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_type_produit->log .'<br>Les informations sont erronées contactez l\'administrateur');
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
		Modifier type de produit 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_type_produit->Shw('type_produit',1).' -'.$info_type_produit->id_type_produit.'-)';?>
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

$form = new Mform('edittype_produit', 'edittype_produit', $info_type_produit->Shw('id',1), 'types_produits','1');
$form->input_hidden('id', $info_type_produit->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//Type de produit
$type_prod_array[]  = array('required', 'true', 'Insérez un type' );
$form->input('Type', 'type_produit', 'text' ,6, $info_type_produit->Shw('type_produit',1), $type_prod_array);

$form->button('Modifier le type');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>




