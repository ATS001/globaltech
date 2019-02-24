<?php
//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
$site = new Msites();
$site->id_sites = Mreq::tp('id');
$site->get_sites();


if (!MInit::crypt_tp('id', null, 'D') or ! $site->get_sites()) {
    // returne message error red to client
    exit('3#' . $site->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

$photo = Minit::get_file_archive($site->sites_info['photo']);
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">


        <?php
        TableTools::btn_action('sites', $site->id_sites, 'detailssite');
        TableTools::btn_add('sites', 'Liste sites', Null, $exec = NULL, 'reply');
        ?>
    </div>
</div>
<div class="page-header">
    <h1>
        Détails du site: <?php $site->s('reference'); ?>

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
                                Site
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
                                                         alt=<?php $site->s('reference') ?> id="avatar2"
                                                         src="<?php echo $photo ?>"/>
                                                </span>

                                                <?php
                                            }
                                            ?>
                                        </h3>
                                        <ul class="list-unstyled spaced">

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Référence :
                                                <b style="color:green"><?php $site->s("reference") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Type :
                                                <b style="color:green"><?php $site->s("type_site") ?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Client :
                                                <b style="color:green"><?php $site->s("client") ?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Date mise en service :
                                                <b style="color:green"><?php $site->s("date_mes") ?></b>
                                            </li>
                                            
                                            <?php if ($site->sites_info["basestation"] != null) {
                                                ?>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Base station:
                                                    <b style="color:green"><?php $site->s("basestation") ?></b>
                                                </li>
                                            <?php } ?>

                                            <?php if ($site->sites_info["secteur"] != null) {
                                                ?>                                            
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Secteur :
                                                    <b style="color:green"><?php $site->s("secteur") ?></b>
                                                </li>
                                            <?php } ?>

                                            <?php if ($site->sites_info["antenne"] != null) {
                                                ?>   
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Antenne :
                                                    <b style="color:green"><?php $site->s("antenne") ?></b>
                                                </li>
                                            <?php } ?>

                                            <?php if ($site->sites_info["modem"] != null) {
                                                ?> 
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Modem :
                                                    <b style="color:green"><?php $site->s("modem") ?></b>
                                                </li>
                                            <?php } ?>


                                            <?php if ($site->sites_info["sn_modem"] != null) {
                                                ?> 
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>SN Modem :
                                                    <b style="color:green"><?php $site->s("sn_modem") ?></b>
                                                </li>
                                            <?php } ?>


                                            <?php if ($site->sites_info["bande"] != null) {
                                                ?> 
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Bande :
                                                    <b style="color:green"><?php $site->s("bande") ?></b>
                                                </li>
                                            <?php } ?>


                                            <?php if ($site->sites_info["satellite"] != null) {
                                                ?> 
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Satellite :
                                                    <b style="color:green"><?php $site->s("satellite") ?></b>
                                                </li>                                            
                                            <?php } ?>

                                            <?php if ($site->sites_info["lnb"] != null) {
                                                ?> 
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>LNB :
                                                    <b style="color:green"><?php $site->s("lnb") ?></b>
                                                </li>
                                            <?php } ?>

                                            <?php if ($site->sites_info["buc"] != null) {
                                                ?> 
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>BUC :
                                                    <b style="color:green"><?php $site->s("buc") ?></b>
                                                </li>
                                            <?php } ?>

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

