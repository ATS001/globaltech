<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();

$info_tickets = new Mtickets();
//Set ID of Module with POST id
$info_tickets->id_tickets = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $info_tickets->get_tickets()) {
    // returne message error red to client 
    exit('3#' . $info_user->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
?>

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

<?php TableTools::btn_add('tickets', 'Liste des tickets', Null, $exec = NULL, 'reply'); ?>

    </div>
</div>
<div class="page-header">
    <h1>
        Ajouter un tickets
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
                $form = new Mform('affecttechnicien', 'affecttechnicien', '', 'tickets', '0', null);
$form->input_hidden('id', $info_tickets->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//var_dump($info_tickets);

//Technicien ==> 
$array_technicien[] = array("required", "true", "Choisir un technicien");
$form->select_table('Technicien', 'id_technicien', 6, 'users_sys', 'id', 'id', 'CONCAT(users_sys.lnom," ",users_sys.fnom)', $indx = '------',$selected=NULL, $multi = NULL, $where = 'etat=1', $array_technicien, NULL);

// Client
$client_array[] = array('required', 'true', 'Choisir un Client');
$form->select_table('Client ', 'id_client', 8, 'clients', 'id', 'id', 'denomination', $indx = '------', $info_tickets->g('id_client'), $multi = NULL, $where = 'etat=1', $client_array, NULL);

//Projet ==> 
$array_projet[] = array("required", "true", "Insérer le projet");
$form->input("Projet", "projet", "text", "9", $info_tickets->g('projet'), $array_projet, null, $readonly = TRUE);

//Date prévisionnelle ==> 
$date_prev[] = array('required', 'true', 'Insérer une date prévisionnelle');
$form->input_date('Date prévisionnelle', 'date_previs', 2, $info_tickets->g('date_previs'), $date_prev);

//Type Produit
//Type produit old
$form->input_hidden('type_produit_old', $info_tickets->g('type_produit'));
$hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categorie_produit" name="categorie_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="' . ((6 * 100) / 12) . '" ><option value="' . $info_tickets->g('categorie_produit') . '" >' . $info_tickets->g('categorie_produit') . '</option></select>';
$type_produit_array[] = array('required', 'true', 'Choisir un Type Produit');
$form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'type_produit', 'type_produit',NULL,$info_tickets->g('type_produit'), $multi = NULL, $where = 'etat = 1', $type_produit_array, $hard_code_type_produit); //Produit

//Message
$array_message[] = array("required", "true", "Insérer un message ");
$form->input_editor('Description', 'message', 8, $info_tickets->g('message'), $array_message, $input_height = 200);

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


    });
</script>	

