<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 02-04-2018
//View
//Get all tickets info 
$info_tickets = new Mtickets();
//Set ID of Module with POST id
$info_tickets->id_tickets = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_tickets->get_tickets()) {
    // returne message error red to client 
    exit('3#' . $info_user->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//var_dump($info_tickets->tickets_info);
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('tickets', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Modifier le tickets: <?php $info_tickets->s('id') ?>
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
                $form = new Mform('edittickets_frs', 'edittickets_frs', '1', 'tickets', '0', null);
                $form->input_hidden('id', $info_tickets->g('id'));
                $form->input_hidden('idc', Mreq::tp('idc'));
                $form->input_hidden('idh', Mreq::tp('idh'));

//Fournisseur ==> 
                $frn_array[] = array('required', 'true', 'Choisir un fournisseur');
                $form->select_table('Fournisseur', 'id_fournisseur', 4, 'fournisseurs', 'id', 'denomination', 'denomination', $indx = '------',$info_tickets->g('id_fournisseur'), $multi = NULL, $where = 'etat=1', $frn_array, NULL);

//Date incident ==> 
                $date_incident[] = array('required', 'true', 'Insérer la date incident');
                $form->input_date('Date incident', 'date_incident', 4,$info_tickets->g('date_previs'), $date_incident);

//Select nature incident
                $nature_incident = array('Coupure de connexion' => 'Coupure de connexion',
                    'Intermittence de connexion' => 'Intermittence de connexion',
                    'BGP down' => 'BGP down',
                    'Lien PtP down' => 'Lien PtP down',
                    'Interférences' => 'Interférences',
                    'Bande passante' => 'Bande passante',
                    'CRC Errors' => 'CRC Errors',
                    'Autres' => 'Autres');
                $form->select('Nature incident', 'nature_incident', 4, $nature_incident, $indx = NULL,$info_tickets->g('nature_incident'), $multi = NULL);


//Prise en charge par fournisseur 
                $pec_frs = array('Equipe Noc' => 'Equipe Noc',
                    'Autres' => 'Autres');
                $form->select('PEC Fournisseur', 'prise_charge_frs', 4, $pec_frs, $indx = NULL, $info_tickets->g('prise_charge_frs'), $multi = NULL);

//Prise en charge par Globaltech 
                $pec_glbt = array('Support Technique' => 'Support Technique',
                    'Autres' => 'Autres');
                $form->select('PEC Globaltech', 'prise_charge_glbt', 4, $pec_glbt, $indx = NULL, $info_tickets->g('prise_charge_glbt'), $multi = NULL);

//Description
                $array_desc[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'description', 8,$info_tickets->g('description'), $array_desc, $input_height = 200);


                $form->button('Enregistrer');
//Form render
                $form->render();
                ?>
            </div>
        </div>
    </div>
</div>
<!-- End Add ticket bloc -->

