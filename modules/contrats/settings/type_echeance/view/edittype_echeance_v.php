<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => View
 defined('_MEXEC') or die;
//Get all compte info 
 $info_type_echeance = new Mtype_echeance();
//Set ID of Module with POST id
 $info_type_echeance->id_type_echeance = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_type_echeance->get_type_echeance())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_type_echeance->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('type_echeance', 'Liste des type_echeance', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le type_echeance 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_type_echeance->Shw('type_echeance',1).' -'.$info_type_echeance->id_type_echeance.'-)' ;
		//var_dump($info_ville->get_ville());
		?>
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


$form = new Mform('edittype_echeance', 'edittype_echeance', $info_type_echeance->id, 'type_echeance' , ' ');

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations type_echeance');

$form->input_hidden('id', $info_type_echeance->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//type_echeance
$type_echeance_array[]  = array('required', 'true', 'Insérer le type_echeance' );
$type_echeance_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Type échéance', 'type_echeance', 'text' ,6, $info_type_echeance->Shw('type_echeance',1), $type_echeance_array);


$form->step_end();
//Button submit 
$form->button('Enregistrer Modifications');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
