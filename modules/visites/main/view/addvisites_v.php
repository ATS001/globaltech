<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('visites','Liste des visites', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un visites
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
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
 
$form = new Mform('addvisites', 'addvisites', '', 'visites', '0', null);


//Commerciale ==> 
	$commercial_array[]  = array('required', 'true', 'Choisir un Commercial');
        $form->select_table('Commerciale', 'commerciale', 6, 'commerciaux', 'id', 'CONCAT(nom," ",prenom)' , 'CONCAT(nom," ",prenom)' , $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat=1', $commercial_array, null);

//Raison sociale ==> 
	$array_raison_sociale[]= array("required", "true", "Insérer Raison sociale ");
	$form->input("Raison sociale", "raison_sociale", "text" ,"9", null, $array_raison_sociale, null, $readonly = null);
//Client / Prospect ==> 
	$array_nature_visite = array('Client' => 'Client', 'Prospect' => 'Prospect');
        $form->select('Client / Prospect', 'nature_visite', 2, $array_nature_visite, $indx = NULL, $selected = 'Client', $multi = NULL);

//Objet Visite ==> 
	$array_objet_visite[]= array("required", "true", "Insérer Objet Visite ...");
	$form->input("Objet Visite", "objet_visite", "text" ,"9", null, $array_objet_visite, null, $readonly = null);
//Date Visite ==> 
	$array_date_visite[]= array("required", "true", "Insérer Date Visite");
        $form->input_date('Date Visite', 'date_visite', 2, date('d-m-Y'), $array_date_visite);

//Interlocuteur ==> 
	$array_interlocuteur[]= array("required", "true", "Insérer Interlocuteur ...");
	$form->input("Interlocuteur", "interlocuteur", "text" ,"9", null, $array_interlocuteur, null, $readonly = null);
//Fonction Interlocuteur ==> 
	$array_fonction_interloc[]= array("required", "true", "Insérer Fonction Interlocuteur ...");
	$form->input("Fonction Interlocuteur", "fonction_interloc", "text" ,"9", null, $array_fonction_interloc, null, $readonly = null);
//Coordonnées Interlocuteur ==> 
	$array_coordonees_interloc[]= array("required", "true", "Insérer Coordonnées Interlocuteur ...");
	$form->input("Coordonnées Interlocuteur", "coordonees_interloc", "text" ,"9", null, $array_coordonees_interloc, null, $readonly = null);
//Commentaire ==> 
	$array_commentaire[]= array("required", "true", "Insérer Commentaire ...");
$form->input_editor('Commentaire', 'commentaire', 8, NULL, $array_commentaire, $input_height = 200);

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

		