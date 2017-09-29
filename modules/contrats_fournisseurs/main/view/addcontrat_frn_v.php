<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => View

 defined('_MEXEC') or die; 
 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('contrats_fournisseurs', 'Liste des Contrats', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
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

$form = new Mform('addcontrat_frn', 'addcontrat_frn','',  'contrats_fournisseurs', null);//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;

//Start Step 1
$form->step_start(1, 'Renseignements Contrat');

//Fournisseur
$devis_array[]  = array('required', 'true', 'Choisir un fournisseur');
$form->select_table('Fournisseur', 'id_fournisseur', 8, 'fournisseurs', 'id', 'denomination' , 'denomination', $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat=1', $devis_array);

//Date effet
$array_date_effet[]= array('required', 'true', 'Insérer la date effet');
$form->input_date('Date effet', 'date_effet', 4, date('d-m-Y'), $array_date_effet);

//Date fin
$array_date_fin[]= array('required', 'true', 'Insérer la date de fin');
$form->input_date('Date de fin', 'date_fin', 4, date('d-m-Y'), $array_date_fin);

//Commentaire
$form->input_editor('Commentaire', 'commentaire', 8, $clauses=NULL , $js_array = null,  $input_height = 50);

//Date notif
$array_date_notif[] = array('required', 'true', 'Insérer la date de notification');
$form->input_date('Date notification', 'date_notif', 4, $info_contrat->s('date_notif'), $array_date_notif);

//pj_id
$form->input('Contrat fournisseur', 'pj', 'file', 6, null, null);
$form->file_js('pj', 1000000, 'pdf');



$form->step_end();
//Button submit 
$form->button('Enregistrer le contrat');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {


	/*$('#date_fin').on('change',function() {
		

    if( $('#date_fin').val() <= $('#date_effet').val() )
    	{
			//alert('test');
    		ajax_loadmessage('La date de fin doit être supérieur de la date d\'effet','nok');
    		//return false;
    	}

    });


	$('#date_effet').on('change',function() {
		

    if( $('#date_effet').val() >= $('#date_fin').val() )
    	{
			//alert('test');
    		ajax_loadmessage('La date d\'effet  doit être inférieur de la date fin','nok');
    		//return false;
    	}

    });*/



});   

</script>	