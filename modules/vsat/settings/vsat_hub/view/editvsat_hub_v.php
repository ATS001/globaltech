
<?php
//Get all Hub info
 $vsat_hub_info= new Mvsat_hub();
//Set ID of Module with POST id
 $vsat_hub_info->id_vsat_hub = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$vsat_hub_info->get_vsat_hub())
 { 	
 	//returne message error red to client 
 	exit('3#'.$vsat_hub_info->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 
?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('vsat_hub', 'Liste des hubs', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$vsat_hub_info->Shw('operateur',1).' -'.$vsat_hub_info->id_vsat_hub.'-)' ;?>
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


$form = new Mform('editvsat_hub', 'editvsat_hub',$vsat_hub_info->Shw('id',1), 'vsat_hub', '0');
$form->input_hidden('id', $vsat_hub_info->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Nom opérateur
$operateur_array[]  = array('required', 'true', 'Insérer un opérateur' );
$operateur_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Opérateur', 'operateur', 'text' ,6 , $vsat_hub_info->Shw('operateur',1), $operateur_array);


//Pays de l'opérateur
$pays_hub_array[]  = array('required', 'true', 'Choisir le pays');
$form->select_table("Pays", 'pays_hub', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '---------' ,$vsat_hub_info->Shw('pays_hub',1), $multi = NULL, $where = NULL, $pays_hub_array);



//Ville
$ville_hub_array[]  = array('required', 'true', 'Insérer une ville');
$ville_hub_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Ville', 'ville_hub', 'text' ,6 , $vsat_hub_info->Shw('ville_hub',1), $ville_hub_array);

//Email
$email_hub_array[]  = array('required', 'true', 'Insérer un émail' );
$email_hub_array[]  = array('minlength', '6', 'Minimum 6 caractères' );
$form->input('E-mail', 'email_hub', 'text' ,6 , $vsat_hub_info->Shw('email_hub',1), $email_hub_array);




//Button submit 
$form->button('Modifier le hub');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
