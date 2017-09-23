<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => View

//Get all contrats_frn info 
$info_contrats_frn = new Mcontrats_fournisseurs();
//Set ID of Module with POST id
$info_contrats_frn->id_contrats_frn = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_contrats_frn->get_contrats_frn()) {
    // returne message error red to client 
    exit('3#' . $info_contrats_frn->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
 
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
        Modifier le Contrat: <?php  $info_contrats_frn->s('reference',1); ?>
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
$form = new Mform('editcontrat_frn', 'editcontrat_frn', '', 'contrats_fournisseurs', '0', null);
$form->input_hidden('id', $info_contrats_frn->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//Fournisseur
$frn_array[]  = array('required', 'true', 'Choisir un fournisseur');
$form->select_table('Fournisseur', 'id_fournisseur', 8, 'fournisseurs', 'id', 'denomination' , 'denomination', $indx = '------' ,$selected=$info_contrats_frn->Shw('id_fournisseur',1),$multi=NULL, $where='etat=1', $frn_array);

//Date effet
$array_date_effet[] = array('required', 'true', 'Insérer la date effet');
$form->input_date('Date effet', 'date_effet', 4, $info_contrats_frn->Shw('date_effet',1), $array_date_effet);

//Date fin
$array_date_fin[] = array('required', 'true', 'Insérer la date de fin');
$form->input_date('Date de fin', 'date_fin', 4, $info_contrats_frn->Shw('date_fin',1), $array_date_fin);

//Commentaire
$form->input_editor('Commentaire', 'commentaire', 8, $info_contrats_frn->Shw('commentaire',1), $js_array = null, $input_height = 50);

//pj_id
$form->input('Contrat fournisseur', 'pj', 'file', 6, 'contrats_fournisseur.pdf', null);
$form->file_js('pj', 1000000, 'pdf', $info_contrats_frn->Shw('pj',1), 1);



$form->button('Enregistrer');
//Form render
$form->render();
?>
            </div>
        </div>
    </div>
</div>