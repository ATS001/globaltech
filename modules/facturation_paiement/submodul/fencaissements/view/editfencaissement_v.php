<?php
defined('_MEXEC') or die;

//Get all encaissement info
$info_encaissement = new Mfencaissements();
//Set ID of Module with POST id
$info_encaissement->id_encaissement = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false.
if (!MInit::crypt_tp('id', null, 'D') or ! $info_encaissement->get_encaissement()) {
    // returne message error red to client
    exit('3#' . $info_encaissement->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$info_encaissement->id_facture= $info_encaissement->encaissement_info["idfacture"];
$info_encaissement->get_facture();
$info_encaissement->getDevise();
$info_encaissement->getDeviseSociete();


if (($info_encaissement->devise_facture != $info_encaissement->devise_societe)) {
    $taux_change = new Mtaux_change();
    $taux_change->get_taux_change_by_devise($info_encaissement->facture_info["id_devise"]);
    $taux_devise = $taux_change->taux_change_devise["conversion"];
  }


?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('fencaissement', 'Liste des encaissements',NULL, $exec = NULL, 'reply');
        $info_encaissement->id_facture = $info_encaissement->Shw('idfacture', 1);
        $info_encaissement->get_facture();
        ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier encaissement facture : <?php $info_encaissement->printattribute_fact('reference'); ?>
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
                $form = new Mform('editfencaissement', 'editfencaissement', $info_encaissement->Shw('id', 1), 'fencaissement&' . MInit::crypt_tp('id', $info_encaissement->Shw('idfacture', 1)), '0');
                $form->input_hidden('idfacture', $info_encaissement->Shw('idfacture', 1));
                $form->input_hidden('id', $info_encaissement->Shw('id', 1));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));

                if (($info_encaissement->devise_facture != $info_encaissement->devise_societe)) {

                    $form->input_hidden('taux_devise', $taux_devise);
                }
                $form->input_hidden('ste_devise', $info_encaissement->devise_societe);
                $form->input_hidden('devise_facture', $info_encaissement->devise_facture);

                //Justification
                $form->input('Justification', 'pj', 'file', 6, 'Justif_enc.pdf', null);
                $form->file_js('pj', 1000000, 'pdf', $info_encaissement->Shw('pj', 1), 1);

                //Depositaire
                $depos_array[] = array('required', 'true', 'Insérez le dépositaire');
                $form->input('Dépositaire', 'depositaire', 'text', 6, $info_encaissement->Shw('depositaire', 1), $depos_array);

//Désignation
                $des_array[] = array('required', 'true', 'Insérez la désignation');
                $form->input('Désignation', 'designation', 'text', 6, $info_encaissement->Shw('designation', 1), $des_array);

                //mode de payment
                $mode_array = array('Espèce' => 'Espèce', 'Chèque' => 'Chèque', 'Virement' => 'Virement');
                $form->select('Mode de payement', 'mode_payement', 3, $mode_array, Null, $info_encaissement->g('mode_payement'), $multi = NULL);

//Réf de la pièce de payement
                $form->input('Référence', 'ref_payement', 'text', 6, $info_encaissement->Shw('ref_payement', 1), NULL);


                //Montant devise externe
                if($info_encaissement->devise_facture != $info_encaissement->devise_societe){
                $mt_devise_ext_array[] = array('number', 'true', 'Entrez un montant valide');
                $form->input('Montant en Devise', 'montant_devise_ext', 'text', 6, $info_encaissement->Shw('montant_devise_ext', 1), $mt_devise_ext_array);
                }

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

<script type="text/javascript">
    $(document).ready(function () {

        $('#montant_devise_ext').on('input change', function () {

            var $montant_devise_ext = $(this).val();

            if ($('#ste_devise').val() != $('#devise_facture').val())
            {
                $('#montant').val(Math.round($('#montant_devise_ext').val()*$('#taux_devise').val()));
            }

        });

    });

</script>
