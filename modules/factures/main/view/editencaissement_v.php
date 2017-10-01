<?php
defined('_MEXEC') or die;

//Get all encaissement info 
$info_encaissement = new Mfacture();
//Set ID of Module with POST id
$info_encaissement->id_encaissement = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_encaissement->get_encaissement()) {
    // returne message error red to client 
    exit('3#' . $info_encaissement->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('encaissements', 'Liste des encaissements', MInit::crypt_tp('id', $info_encaissement->Shw('idfacture', 1)), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier un encaissement
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
                $form = new Mform('editencaissement', 'editencaissement', $info_encaissement->Shw('id', 1), 'encaissements&' . MInit::crypt_tp('id', $info_encaissement->Shw('idfacture', 1)), '0');
                $form->input_hidden('idfacture', $info_encaissement->Shw('idfacture', 1));
                $form->input_hidden('id', $info_encaissement->Shw('id', 1));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));


                //Justification
                $form->input('Justification', 'pj', 'file', 6, null, null);
                $form->file_js('pj', 1000000, 'pdf',$info_encaissement->Shw('pj',1),1);

//Désignation
                $des_array[] = array('required', 'true', 'Insérez la désignation');
                $form->input('Désignation', 'designation', 'text', 6, $info_encaissement->Shw('designation', 1), $des_array);

//Montant
                $mt_array[] = array('required', 'true', 'Insérez le montant');
                $mt_array[] = array('number', 'true', 'Entrez un montant valide');
                $form->input('Montant', 'montant', 'text', 6, $info_encaissement->Shw('montant', 1), $mt_array);


                $form->button('Modifier encaissement');

                //Form render
                $form->render();
                ?>
            </div>
        </div>
    </div>
</div>
