<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();

$ticket = new Mtickets();
//Set ID of Module with POST id
$ticket->id_tickets = Mreq::tp('id');
//$ticket->get_tickets();

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or ! $ticket->get_tickets()) {
    // returne message error red to client 
    exit('3#' . $ticket->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
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


<?php
$form = new Mform('affecttechnicien', 'affecttechnicien', '', 'tickets', '0', null);


//Technicien ==> 
$array_technicien[] = array("required", "true", "Choisir un technicien");
$form->select_table('Technicien', 'id_technicien', 6, 'users_sys', 'id', 'id', 'CONCAT(users_sys.lnom," ",users_sys.fnom)', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=1', $array_technicien, NULL);

//var_dump($info_tickets);
?>
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



                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <div id="user-profile-2" class="user-profile">


                                <div class="tab-content no-border padding-24">
                                    <div id="home" class="tab-pane in active">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">

                                                <div>
                                                    <ul class="list-unstyled spaced">
                                                        <?php if($ticket->s("technicien") != NULL){?>
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Technicien précèdent :
                                                            <b style="color:green"><?php $ticket->s("technicien") ?></b>
                                                        </li> 
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Date affectation :
                                                            <b style="color:green"><?php $ticket->s("date_affectation") ?></b>
                                                        </li>
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Nombre de jour :
                                                            <b style="color:green"><?php $ticket->s("nbrj") ?></b>
                                                        </li> 
                                                       <?php }?>
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Client :
                                                            <b style="color:green"><?php $ticket->s("client") ?></b>
                                                        </li>
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Projet :
                                                            <b style="color:green"><?php $ticket->s("projet") ?></b>
                                                        </li>                                                       
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Type produit:
                                                            <b style="color:green"><?php $ticket->s("type_produit") ?></b>
                                                        </li>
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Catégorie produit :
                                                            <b style="color:green"><?php $ticket->s("categorie_produit") ?></b>
                                                        </li> 
                                                        <li>
                                                            <i class="ace-icon fa fa-caret-right green"></i>Date prévisionnelle :
                                                            <b style="color:green"><?php $ticket->s("date_previs") ?></b>
                                                        </li>
                                                        
                                                                                                              
                                                    </ul>
                                                    
                                                </div>

                                            </div><!-- /.col -->
                                        </div>


                                    </div><!-- /#home -->


                                </div><!-- /.row -->



                            </div>
                        </div>
                    </div>
                </div>




<?php
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

