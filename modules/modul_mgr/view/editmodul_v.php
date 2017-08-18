<?php 
defined('_MEXEC') or die; 
//Get all compte info 
 $info_modul = new Mmodul();
//Set ID of Module with POST id
 $info_modul->id_modul = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_modul->get_modul())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_modul->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php  TableTools::btn_back('modul','Liste Modules','',''); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_modul->Shw('description',1).' -'. $info_modul->id_modul.'-)';?>
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

$form = new Mform('editmodul', 'editmodul', $info_modul->Shw('id',0), 'modul','');
$form->input_hidden('id',  $info_modul->Shw('id'));
$form->input_hidden('id_checker',  MInit::cryptage($info_modul->Shw('id'), 1));
$form->input_hidden('id_app',  $info_modul->Shw('id_app'));

//Titre bloc Modul
$form->bloc_title('Informations Module');
//Nom Module
$modul_array[]  = array('required', 'true', 'Insérer Nom de Module' );
$modul_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$modul_array[]  = array('regex', 'true', 'Insérer Nom de Modul Valid (a_z 0_9)' );
$form->input('Nom Module', 'modul', 'text' ,6 , $info_modul->Shw('modul'), $modul_array);
/*//Répertoire Module
$modul_rep_array[]  = array('required', 'true', 'Insérer Nom de Répertoire' );
$modul_rep_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$modul_rep_array[]  = array('regex', 'true', 'Insérer Nom de Répertoire Valid' );
$form->input('Répertoire Module', 'rep_modul', 'text' ,6 , $info_modul->Shw('rep_modul'), $modul_rep_array);*/
//Déscription Module
$description_array[]  = array('required', 'true', 'Insérer Prénom utilisateur' );
$description_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Déscription', 'description', 'text', 10,  $info_modul->Shw('description'), $description_array);
//Tables de module
$table_array[]  = array('required', 'true', 'Insérer au moin une table utilisée' );
$table_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Tables utilisées', 'tables', 'text', 10, $info_modul->Shw('tables'), $table_array);
$form->input_tag('tables');
//Titre bloc default TASK
$form->bloc_title('Informations Application par défault');

//Nom Application
$app_array[]  = array('required', 'true', 'Insérer Nom d l'."\'".' application' );
$app_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$app_array[]  = array('regex', 'true', 'Insérer Nom d l'."\'".' application Valid' );
$form->input('Nom Application', 'app', 'text', 6,  $info_modul->Shw('app'), $app_array);

//Class en cas de app de base

//$sbclass_array[]  = array('regex', 'true', 'Insérer Classe Valid' );
$sbclass_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Class TDB', 'sbclass', 'text', 6, $info_modul->Shw('sbclass'), $sbclass_array);

//Titre bloc default Services
$form->bloc_title('Les Services par défault de ce module');
//Service
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)
$form->select_table('Services', 'services[]', 10, 'services','id', 'service', 'service', $indx = NULL ,$info_modul->Shw('services'), 1, NULL, NULL);
//Button submit 
$form->button('Enregistrer le Module');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
