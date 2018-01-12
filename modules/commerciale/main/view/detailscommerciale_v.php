<?php
//First check target no Hack
if (!defined('_MEXEC')) die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
$commerciale = new Mcommerciale();
$commerciale->id_commerciale = Mreq::tp('id');
$commerciale->get_commerciale();

if (!MInit::crypt_tp('id', null, 'D') or !$commerciale->get_commerciale()) {
    // returne message error red to client
    exit('3#' . $commerciale->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

$pj = $commerciale->commerciale_info['pj'];
$photo = Minit::get_file_archive($commerciale->commerciale_info['photo']);
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_add('commerciale', 'Liste commerciale', Null, $exec = NULL, 'reply');
        ?>
    </div>
</div>
<div class="page-header">
    <h1>
        Détails du commerciale: <?php $commerciale->s('id'); ?>

        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div>


<div class="row">
    <div class="col-xs-12">
        <div>
            <div id="user-profile-2" class="user-profile">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-18">
                        <li class="active">
                            <a data-toggle="tab" href="#home">
                                <i class="green ace-icon fa fa-installer bigger-120"></i>
                                Commerciale
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">

                                    <div>
                                        <h3 class="widget-title grey lighter">
                                            <?php if ($photo != null) {
                                                ?>


                                                <span class="profile-picture">
										    	<img width="200" height="300" class="editable img-responsive"
                                                     alt=<?php $commerciale->s('nom') ?> id="avatar2"
                                                     src="<?php echo $photo ?>"/>
									        </span>


                                                <?php
                                            }
                                            ?>
                                        </h3>
                                        <ul class="list-unstyled spaced">

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Nom :
                                                <b style="color:green"><?php $commerciale->s("nom") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Prénom :
                                                <b style="color:green"><?php $commerciale->s("prenom") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Interne :
                                                <b style="color:green"><?php $commerciale->s("is_glbt") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>CIN :
                                                <b style="color:green"><?php $commerciale->s("cin") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>RIB:
                                                <b style="color:green"><?php $commerciale->s("rib") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Téléphone :
                                                <b style="color:green"><?php $commerciale->s("tel") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>E-mail :
                                                <b style="color:green"><?php $commerciale->s("email") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Sexe :
                                                <b style="color:green"><?php $commerciale->s("sexe") ?></b>
                                            </li>
                                            <?php if( $pj != null){
                                            ?>
                                            <li>


                                                        <i class="ace-icon fa fa-caret-right green"></i>Pièce :
                                                        <a href="#" class="iframe_pdf" rel=<?php echo $pj; ?>>
                                                            <i class="ace-icon fa fa-print"></i>
                                                        </a>


                                            </li>

                                                <?php
                                            }
                                            ?>

                                        </ul>
                                    </div>

                                </div><!-- /.col -->
                            </div>


                        </div><!-- /#home -->

                    </div><!-- /.row -->

                </div><!-- /#feed -->

            </div>
        </div>
    </div>
</div>

</div><!-- /.well -->


</div><!-- /.-profile -->

