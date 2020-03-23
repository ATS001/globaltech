<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 29-08-2019
//View
//Get all visites info
 $info_visites = new Mvisites();
//Set ID of Module with POST id
 $info_visites->id_visites = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false.
 if(!MInit::crypt_tp('id', null, 'D') or !$info_visites->get_visites())
 {
 	// returne message error red to client
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

		<?php TableTools::btn_add('visites','Liste des visites', Null, $exec = NULL, 'reply'); ?>

	</div>
</div>
<div class="page-header">
	<h1>
		Modifier la visite: <?php $info_visites->s('id')?>
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
$form = new Mform('editvisite', 'editvisite', '1', 'visites', '0', null);
$form->input_hidden('id', $info_visites->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));



//Commerciale ==>
	$commercial_array[]  = array('required', 'true', 'Choisir un Commercial');
        $form->select_table('Commerciale', 'commerciale', 6, 'commerciaux', 'id', 'CONCAT(nom," ",prenom)' , 'CONCAT(nom," ",prenom)' , $indx = '------' ,$info_visites->g('commerciale'),$multi=NULL, $where='etat=1', $commercial_array, null);

//Raison sociale ==>
	$array_raison_sociale[]= array("required", "true", "Insérer Raison sociale ");
	$form->input("Raison sociale", "raison_sociale", "text" ,"9", $info_visites->g('raison_sociale'), $array_raison_sociale, null, $readonly = null);
//Client / Prospect ==>
	$array_nature_visite = array('Client' => 'Client', 'Prospect' => 'Prospect');
        $form->select('Client / Prospect', 'nature_visite', 2,$array_nature_visite, $indx = NULL,$info_visites->g('nature_visite'), $multi = NULL);

//if($info_visites->g('id_client') != null){
        //Clients ==>
                    $form->select_table('Client', 'id_client', 6, 'clients', 'id', 'denomination', 'denomination', $indx = '------', $info_visites->g('id_client'), $multi = NULL, $where = 'etat=1', null, NULL);
//}
//if($info_visites->g('id_prospects') != null){
        //Prospects ==>
            $form->select_table('Prospect', 'id_prospects', 6, 'prospects', 'id', 'reference' , 'reference' , $indx = '------' ,$info_visites->g('id_prospects'),$multi=NULL, $where='etat=1',null, null);
//}
//Objet Visite ==>
	$array_objet_visite[]= array("required", "true", "Insérer Objet de la Visite ");
	$form->input("Objet Visite", "objet_visite", "text" ,"9", $info_visites->g('objet_visite'), $array_objet_visite, null, $readonly = null);
//Date Visite ==>
	$array_date_visite[]= array("required", "true", "Insérer Date Visite");
        $form->input_date('Date Visite', 'date_visite', 2, $info_visites->g('date_visite'), $array_date_visite);

//Interlocuteur ==>
	$array_interlocuteur[]= array("required", "true", "Insérer Interlocuteur");
	$form->input("Interlocuteur", "interlocuteur", "text" ,"9", $info_visites->g('interlocuteur'), $array_interlocuteur, null, $readonly = null);
//Fonction Interlocuteur ==>
	$array_fonction_interloc[]= array("required", "true", "Insérer Fonction Interlocuteur ...");
	$form->input("Fonction Interlocuteur", "fonction_interloc", "text" ,"9", $info_visites->g('fonction_interloc'), $array_fonction_interloc, null, $readonly = null);
//Coordonnées Interlocuteur ==>
	$array_coordonees_interloc[]= array("required", "true", "Insérer Coordonnées Interlocuteur");
	$form->input("Coordonnées Interlocuteur", "coordonees_interloc", "text" ,"9", $info_visites->g('coordonees_interloc'), $array_coordonees_interloc, null, $readonly = null);
//Commentaire ==>
	$array_commentaire[]= array("required", "true", "Insérer Commentaire");
$form->input_editor('Commentaire', 'commentaire', 8, $info_visites->g('commentaire'), $array_commentaire, $input_height = 200);

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

  if ($("#nature_visite option:selected").text() == 'Client') {

 document.getElementById("xid_prospectsx").style.display = 'none';
 document.getElementById("xid_clientx").style.display = 'block';
 }else {
 document.getElementById("xid_clientx").style.display = 'none';
 document.getElementById("xid_prospectsx").style.display = 'block';
 }


 $('#nature_visite').on('change', function () {
 if ($("#nature_visite option:selected").text() == 'Client') {

document.getElementById("xid_prospectsx").style.display = 'none';
document.getElementById("xid_clientx").style.display = 'block';
}else {
document.getElementById("xid_clientx").style.display = 'none';
document.getElementById("xid_prospectsx").style.display = 'block';
}
});
});
</script>
