<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();


$ticket = new Mtickets();
//Set ID of Module with POST id
$ticket->id_tickets = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false.
if (!MInit::crypt_tp('id', null, 'D') or !$ticket->get_tickets()) {
    // returne message error red to client
    exit('3#' . $ticket->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$id_ticket=$ticket->g("id");
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

<?php TableTools::btn_add('tickets', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Ajouter une action
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
                $form = new Mform('addaction', 'addaction', '', 'detailsticket&'.MInit::crypt_tp('id', $id_ticket), '0', null);
                $form->input_hidden('id_ticket', $id_ticket);
//Date action ==> 
                $date_act[] = array('required', 'true', 'Insérer une date ');
                $form->input_date('Date', 'date_action', 2, date('d-m-Y'), $date_act);


//image
                $form->input('Photo', 'photo', 'file', 9, null, null);
                $form->file_js('photo', 1000000, 'image');

                //PJ
                $form->input('Pièce jointe', 'pj', 'file', 8, null, null);
                $form->file_js('pj', 1000000, 'pdf');


//Message
                $array_message[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'message', 8, NULL, $array_message, $input_height = 200);




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

