
<?php
//Get all satellite info
 $info_vsat_satellite= new Mvsat_satellite();
//Set ID of Module with POST id
 $info_vsat_satellite->id_vsat_satellite = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_vsat_satellite->get_vsat_satellite())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_vsat_satellite->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 
?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('vsat_satellite', 'Liste des satellites', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_vsat_satellite->Shw('satellite',1).' -'.$info_vsat_satellite->id_vsat_satellite.'-)' ;?>
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


$form = new Mform('editvsat_satellite', 'editvsat_satellite',$info_vsat_satellite->Shw('id',1), 'vsat_satellite', '0');
$form->input_hidden('id', $info_vsat_satellite->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));



//Nom Satellite
$satellite_array[]  = array('required', 'true', 'Insérer un satellite' );
$satellite_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Satellite', 'satellite', 'text' ,6 , $info_vsat_satellite->Shw('satellite',1), $satellite_array);


//Nom Position orbitale
$position_orbitale_array[]  = array('required', 'true', 'Insérer la position orbitale' );
$position_orbitale_array[]  = array('minlength', '1', 'Minimum 1' );
$form->input('Position Orbitale', 'position_orbitale', 'text' ,6 , $info_vsat_satellite->Shw('position_orbitale',1), 
	$position_orbitale_array);

//Contractor
$contractor_array[]  = array('required', 'true', 'Insérer un fournisseur' );
$contractor_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Fournisseur', 'contractor', 'text' ,6 , $info_vsat_satellite->Shw('contractor',1), $contractor_array);

//Contractor
$pay_operator_array[]  = array('required', 'true', 'Choisir un pays' );
$pay_operator_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Pays opérateur', 'pay_operator', 'text' ,6 , $info_vsat_satellite->Shw('pay_operator',1),
	 $pay_operator_array);


//Button submit 
$form->button('Modifier le satellite');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
