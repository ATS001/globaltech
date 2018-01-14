<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('commerciale', 'Liste des commerciale', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Ajouter un commerciale
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

                $form = new Mform('addcommerciale', 'addcommerciale', '', 'commerciale', '0', null);

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
                $array_nom[] = array("required", "true", "Insérer le Nom");
                $form->input("Nom", "nom", "text", "9", null, $array_nom, null, $readonly = null);
                //prenom ==>
                $array_prenom[] = array("required", "true", "Insérer le prénom");
                $form->input("Prénom", "prenom", "text", "9", null, $array_prenom, null, $readonly = null);
                //is_glbt ==>
                $array_is_glbt = array('Oui' => 'Oui', 'Non' => 'Non');
                $form->input("Interne", "is_glbt", "text", "9", null, $array_is_glbt, null, $readonly = null);
                //cin ==>
                $array_cin[] = array("required", "true", "Insérer le N° CIN");
                $form->input("CIN", "cin", "text", "9", null, $array_cin, null, $readonly = null);
                //rib ==>
                $array_rib[] = array("required", "true", "Insérer le N° RIB");
                $form->input("RIB", "rib", "text", "9", null, $array_rib, null, $readonly = null);
                //tel ==>
                $array_tel[] = array("required", "true", "Insérer le N° Téléphone");
                $form->input("Téléphone", "tel", "text", "9", null, $array_tel, null, $readonly = null);
                //email ==>
                $form->input("E-mail", "email", "text", "9", null,null, null, $readonly = null);

                //Select Simple Example
                $sexe_opt = array('M' => 'Masculin', 'F' => 'Féminin');
                $form->select('Sexe', 'sexe', 2, $sexe_opt, $indx = NULL, $selected = 'M', $multi = NULL);

                //image
                $form->input('Photo', 'photo', 'file', 9, null, null);
                $form->file_js('photo', 1000000, 'image');

                //PJ
                $form->input('Pièce justificative', 'pj', 'file', 8, null, null);
                $form->file_js('pj', 1000000, 'pdf');

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

//JS bloc   

    });
</script>	

		