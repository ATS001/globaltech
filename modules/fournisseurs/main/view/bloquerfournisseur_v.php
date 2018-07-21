<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fournisseurs
//Created : 19-07-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('fournisseurs','Liste des fournisseurs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un fournisseurs
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
 
$form = new Mform('bloquerfournisseur', 'bloquerfournisseur', '', 'fournisseurs', '0', null);

//Date Example
//$array_date[]= array('required', 'true', 'Insérer la date de ...');
//$form->input_date('Date', 'date_', 4, date('d-m-Y'), $array_date);
//Select Table Example


//$select_array[]  = array('required', 'true', 'Choisir un ....');
//$form->select_table('Select ', 'select', 8, 'table', 'id', 'text' , 'text', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $select_array, null);



//Select Simple Example
//$field_opt = array('O' => 'OUI' , 'N' => 'NON' );
//$form->select('Label Field', 'field', 2, $field_opt, $indx = NULL ,$selected = NULL, $multi = NULL);

//Separate Zone title
//$form->bloc_title('Zone separated');


//Input Example
//$form->input('Label field', 'field', 'text' ,'class', '0', null, null, $readonly = null);
//For more Example see form class

//Code fournisseur ==> 
	$array_reference[]= array("required", "true", "Insérer Code fournisseur ...");
	$form->input("Code fournisseur", "reference", "text" ,"9", null, $array_reference, null, $readonly = null);
//Denomination du client ==> 
	$array_denomination[]= array("required", "true", "Insérer Denomination du client ...");
	$form->input("Denomination du client", "denomination", "text" ,"9", null, $array_denomination, null, $readonly = null);
//Raison social ==> 
	$array_r_social[]= array("required", "true", "Insérer Raison social ...");
	$form->input("Raison social", "r_social", "text" ,"9", null, $array_r_social, null, $readonly = null);
//Registre de commerce ==> 
	$array_r_commerce[]= array("required", "true", "Insérer Registre de commerce ...");
	$form->input("Registre de commerce", "r_commerce", "text" ,"9", null, $array_r_commerce, null, $readonly = null);
//Id fiscale ==> 
	$array_nif[]= array("required", "true", "Insérer Id fiscale ...");
	$form->input("Id fiscale", "nif", "text" ,"9", null, $array_nif, null, $readonly = null);
//Nom ==> 
	$array_nom[]= array("required", "true", "Insérer Nom ...");
	$form->input("Nom", "nom", "text" ,"9", null, $array_nom, null, $readonly = null);
//Prénom ==> 
	$array_prenom[]= array("required", "true", "Insérer Prénom ...");
	$form->input("Prénom", "prenom", "text" ,"9", null, $array_prenom, null, $readonly = null);
//Sexe ==> 
	$array_civilite[]= array("required", "true", "Insérer Sexe ...");
	$form->input("Sexe", "civilite", "text" ,"9", null, $array_civilite, null, $readonly = null);
//Adresse ==> 
	$array_adresse[]= array("required", "true", "Insérer Adresse ...");
	$form->input("Adresse", "adresse", "text" ,"9", null, $array_adresse, null, $readonly = null);
//Pays ==> 
	$array_id_pays[]= array("required", "true", "Insérer Pays ...");
	$form->input("Pays", "id_pays", "text" ,"9", null, $array_id_pays, null, $readonly = null);
//Ville ==> 
	$array_id_ville[]= array("required", "true", "Insérer Ville ...");
	$form->input("Ville", "id_ville", "text" ,"9", null, $array_id_ville, null, $readonly = null);
//Telephone ==> 
	$array_tel[]= array("required", "true", "Insérer Telephone ...");
	$form->input("Telephone", "tel", "text" ,"9", null, $array_tel, null, $readonly = null);
//Fax ==> 
	$array_fax[]= array("required", "true", "Insérer Fax ...");
	$form->input("Fax", "fax", "text" ,"9", null, $array_fax, null, $readonly = null);
//Boite postale ==> 
	$array_bp[]= array("required", "true", "Insérer Boite postale ...");
	$form->input("Boite postale", "bp", "text" ,"9", null, $array_bp, null, $readonly = null);
//E-mail ==> 
	$array_email[]= array("required", "true", "Insérer E-mail ...");
	$form->input("E-mail", "email", "text" ,"9", null, $array_email, null, $readonly = null);
//compte bancaire du fournisseur ==> 
	$array_rib[]= array("required", "true", "Insérer compte bancaire du fournisseur ...");
	$form->input("compte bancaire du fournisseur", "rib", "text" ,"9", null, $array_rib, null, $readonly = null);
//Devise de facturation du client ==> 
	$array_id_devise[]= array("required", "true", "Insérer Devise de facturation du client ...");
	$form->input("Devise de facturation du client", "id_devise", "text" ,"9", null, $array_id_devise, null, $readonly = null);
//pj ==> 
	$array_pj[]= array("required", "true", "Insérer pj ...");
	$form->input("pj", "pj", "text" ,"9", null, $array_pj, null, $readonly = null);
//photo du client ==> 
	$array_pj_photo[]= array("required", "true", "Insérer photo du client ...");
	$form->input("photo du client", "pj_photo", "text" ,"9", null, $array_pj_photo, null, $readonly = null);


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

		