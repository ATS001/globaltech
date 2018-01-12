<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
//Get all commerciale info 
$info_commerciale = new Mcommerciale();
//Set ID of Module with POST id
$info_commerciale->id_commerciale = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or !$info_commerciale->get_commerciale()) {
    // returne message error red to client
    exit('3#' . $info_user->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('commerciale', 'Liste des commerciale', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier le commerciale: <?php $info_commerciale->s('id') ?>
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
                $form = new Mform('editcommerciale', 'editcommerciale', '1', 'commerciale', '0', null);
                $form->input_hidden('id', $info_commerciale->g('id'));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));


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

                //nom ==>
                $array_nom[] = array("required", "true", "Insérer le nom");
                $form->input("nom", "nom", "text", "6", $info_commerciale->g("nom"), $array_nom, null, $readonly = null);
                //prenom ==>
                $array_prenom[] = array("required", "true", "Insérer le prénom");
                $form->input("prenom", "prenom", "text", "6", $info_commerciale->g("prenom"), $array_prenom, null, $readonly = null);
                //is_glbt ==>
                $array_is_glbt = array('Oui' => 'Oui', 'Non' => 'Non');
                $form->input("Interne", "is_glbt", "text", "6", $info_commerciale->g("is_glbt"), $array_is_glbt, null, $readonly = null);
                //cin ==>
                $array_cin[] = array("required", "true", "Insérer le N° CIN");
                $form->input("CIN", "cin", "text", "6", $info_commerciale->g("cin"), $array_cin, null, $readonly = null);
                //rib ==>
                //$array_rib[] = array("required", "true", "Insérer le N° RIB");
                $form->input("RIB", "rib", "text", "6", $info_commerciale->g("rib"),null, null, $readonly = null);
                //tel ==>
                $array_tel[] = array("required", "true", "Insérer le N° Téléphone");
                $form->input("Téléphone", "tel", "text", "6", $info_commerciale->g("tel"), $array_tel, null, $readonly = null);
                //email ==>
                //$array_email[] = array("required", "true", "Insérer email ...");
                $form->input("E-mail", "email", "text", "9", $info_commerciale->g("email"),null, null, $readonly = null);
                //Select Simple Example
                $sexe_opt = array('Masculin' => 'Masculin', 'Féminin' => 'Féminin');
                $form->select('Sexe', 'sexe', 2, $sexe_opt, $indx = NULL,$info_commerciale->g("sexe"), $multi = NULL);

                //Photo
                $form->input('Photo', 'photo', 'file', 6, 'Image_employe', null);
                $form->file_js('photo', 1000000, 'image',$info_commerciale->g("photo"),1);

                //contrat
                $form->input('Pièce justificative', 'pj', 'file', 8, 'pièce_justificative.pdf', null);
                $form->file_js('pj', 1000000, 'pdf', $info_commerciale->g("pj"), 1);


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
    $(document).ready(function () {

//JS Bloc    

    });
</script>	

		