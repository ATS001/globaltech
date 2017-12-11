<?php
$info_facture = new Mfacture();
$info_facture->id_facture = Mreq::tp('id');


if (!MInit::crypt_tp('id', null, 'D') or ! $info_facture->get_facture()) {

    exit('0#' . $info_facture->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

$id_facture = Mreq::tp('id');
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

<?php TableTools::btn_add('complements', 'Liste des compléments', MInit::crypt_tp('id', $id_facture), $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Ajout complément facture : <?php $info_facture->printattribute_fact('reference'); ?>
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
$form = new Mform('addcomplement', 'addcomplement', '', 'complements&' . MInit::crypt_tp('id', $id_facture), '0');
$form->input_hidden('idfacture', $id_facture);

//Désignation
$des_array[] = array('required', 'true', 'Insérez la désignation');
$form->input('Désignation', 'designation', 'text', 6, null, $des_array);

//Type de station
$type_array  = array('Réduction' => 'Réduction', 'Pénalité' => 'Pénalité' );
$form->select('Type', 'type', 3, $type_array, Null,'Réduction', $multi = NULL );


//Montant
$mt_array[] = array('required', 'true', 'Insérez le montant');
$mt_array[] = array('number', 'true', 'Entrez un montant valide');
$form->input('Montant', 'montant', 'text', 6, null, $mt_array);


$form->button('Enregistrer');

//Form render
$form->render();
?>
            </div>
        </div>
    </div>
</div>
