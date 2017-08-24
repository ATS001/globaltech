<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => View

defined('_MEXEC') or die;
//Get all compte info 
 $info_categorie_client = new Mcategorie_client();
//Set ID of Module with POST id
 $info_categorie_client->id_categorie_client = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_categorie_client->get_categorie_client())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_categorie_client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('categorie_client', 'Liste des catégories clients', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier la catégorie client 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_categorie_client->Shw('categorie_client',1).' -'.$info_categorie_client->id_categorie_client.'-)' ;
		
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


$form = new Mform('editcategorie_client', 'editcategorie_client', $info_categorie_client->id, 'categorie_client' , ' ');

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations Catégorie Client');

$form->input_hidden('id', $info_pays->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//categorie_client_array
$cat_array[]  = array('required', 'true', 'Insérer la Catégorie Client' );
$cat_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Pays', 'categorie_client', 'text' ,6, $info_categorie_client->Shw('categorie_client',1), $cat_array);


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
