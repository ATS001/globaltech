<?php
defined('_MEXEC') or die;

//Get all complement info 
$info_complement = new Mfacture();
//Set ID of Module with POST id
$info_complement->id_complement = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_complement->get_complement()) {
    // returne message error red to client 
    exit('3#' . $info_complement->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('complements', 'Liste des complements', MInit::crypt_tp('id', $info_complement->Shw2('idfacture', 1)), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier un complement
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
                $form = new Mform('editcomplement', 'editcomplement', $info_complement->Shw2('id', 1), 'complements&' . MInit::crypt_tp('id', $info_complement->Shw2('idfacture', 1)), '0');
                $form->input_hidden('idfacture', $info_complement->Shw2('idfacture', 1));
                $form->input_hidden('id', $info_complement->Shw2('id', 1));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));

//Désignation
                $des_array[] = array('required', 'true', 'Insérez la désignation');
                $form->input('Désignation', 'designation', 'text', 6, $info_complement->Shw2('designation', 1), $des_array);

//Type de station
                $type_array = array('Réduction' => 'Réduction', 'Pénalité' => 'Pénalité');
                $form->select('Type', 'type', 3, $type_array, Null, $info_complement->Shw2('type', 1), $multi = NULL);


//Montant
                $mt_array[] = array('required', 'true', 'Insérez le montant');
                $mt_array[] = array('number', 'true', 'Entrez un montant valide');
                $form->input('Montant', 'montant', 'text', 6, $info_complement->Shw2('montant', 1), $mt_array);


                $form->button('Modifier complément');

                //Form render
                $form->render();
                ?>
            </div>
        </div>
    </div>
</div>
