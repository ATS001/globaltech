<?php 
//SYS GLOBAL TECH
// Modul: fournisseurs => View

 defined('_MEXEC') or die; 
 //Get all compte info 
 $info_fournisseur = new Mfournisseurs();
//Set ID of Module with POST id
 $info_fournisseur->id_fournisseur = Mreq::tp('id');
 //var_dump(Mreq::tp('id'));

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_fournisseur->get_fournisseur())
 { 	
 	// returne message error red to fournisseur 
 	exit('3#'.$info_fournisseur->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('fournisseurs','Liste des fournisseurs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Bloquer Fournisseur
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_fournisseur->Shw('denomination',1).' -'.$info_fournisseur->Shw('reference',1).'-)' ;
		
		?>
	</h1>
</div><!-- /.page-header -->
<!-- Bloc form Add Devis-->
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
 
$form = new Mform('bloquerfournisseur', 'bloquerfournisseur', '', 'fournisseurs', '0', null);
//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');

$form->input_hidden('id', $info_fournisseur->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Start Step 1
$form->step_start(1, 'Blocage Client');

//Catégorie client
$motif_array[]  = array('required', 'true', 'Sélectionnez la catégorie' );
$form->select_table('Motif de Blocage', 'id_motif_blocage', 8, 'ref_motif_blocage', 'id', 'motif' , 'motif', $indx = '------' ,
    $selected=NULL,$multi=NULL, $where='type="F" and etat=1', $motif_array);

//Commentaire
$form->input_editor('Commentaire', 'commentaire', 6, $clauses=NULL , $js_array = null,  $input_height = 80);

//End Step 1
$form->step_end();


$form->button('Enregistrer');
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
<!-- End Add devis bloc -->
		
<script type="text/javascript">
$(document).ready(function() {
    
//JS bloc   

});
</script>	

		