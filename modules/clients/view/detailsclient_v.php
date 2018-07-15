<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => View

//Get all contrats_frn info 
 $client= new Mclients();
//Set ID of Module with POST id
 $client->id_client = Mreq::tp('id');

 $client->get_list_devis();
 $client_devis = $client->client_info;

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$client->get_client())
{   
    // returne message error red to client 
    exit('3#'.$client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
$pj      = $client->client_info['pj'];
$photo   = Minit::get_file_archive($client->client_info['pj_photo']);

?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_action('clients', $client->id_client, 'detailsclient');
              TableTools::btn_add('clients', 'Liste Clients', Null, $exec = NULL, 'reply');      
         ?> 
         

    </div>
</div>
<div class="page-header">
    <h1>
            Détails du client: <?php $client->s('reference')?>    <?php $client->s('denomination'); ?> 
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div><!-- /.page-header -->
<!-- ajax layout which only needs content area -->
<div class="row">
        <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        

        <div class="hr dotted"></div>



        <div>
            <div id="user-profile-2" class="user-profile">
                <div class="tabbable">
                    <ul class="nav nav-tabs padding-18">
                        <li class="active">
                            <a data-toggle="tab" href="#home">
                                <i class="green ace-icon fa fa-user bigger-120"></i>
                                Client 
                            </a>
                        </li>

                        <li>
                            <a data-toggle="tab" href="#feed">
                                <i class="bleu ace-icon fa fa-money bigger-120"></i>
                                Devis
                            </a>
                        </li>

                         <li>
                            <a data-toggle="tab" href="#feed1">
                                <i class="orange ace-icon fa fa-inbox bigger-120"></i>
                                Abonnements
                            </a>
                        </li>

                         <li>
                            <a data-toggle="tab" href="#feed2">
                                <i class="red ace-icon fa fa-file bigger-120"></i>
                                Factures
                            </a>
                        </li>
                        
                    </ul>

                    <div class="tab-content no-border padding-24">
                        <div id="home" class="tab-pane in active">
            <div class="col-sm-10 col-sm-offset-1">
                <!-- #section:pages/invoice -->
                <div class="widget-box transparent">
                    <div class="widget-header widget-header-large">
                        
                        <h3 class="widget-title grey lighter">
                            <?php if ($photo != null)
                            { 
                            ?>

                            
                            
                                            <span class="profile-picture">
                                                <img width="50" height="50" class="editable img-responsive" alt=<?php $client->s('denomination')   ?> id="avatar2" src="<?php echo $photo ?>" />
                                            </span> 

                            
                            <?php 
                            }
                            ?>
                            <i class="ace-icon fa fa-adress-card-o green"></i>
                            Client : <?php echo $client->s('reference')?>
                        </h3>

                    

                        <?php if( $pj != null){
                        ?>
                         <div class="widget-toolbar hidden-480">
                            <a href="#" class="iframe_pdf" rel=<?php echo $pj; ?>>
                                <i class="ace-icon fa fa-print"></i>
                            </a>
                        </div>       
                       <?php 
                                                } 
                       ?>
                        

                        <!-- /section:pages/invoice.info -->
                    </div>

                    <div class="widget-body">
                        <div class="widget-main padding-24">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                            <b>Renseignements Client</b>
                                        </div>
                                    </div>

                                    <div>
                                        
                                        <ul class="list-unstyled spaced">
                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Référence                                                                                               
                                                  <b class="blue pull-right"> <?php  $client->s('reference')  ?> </b>                                        
                                    
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Dénomination                                                                                               
                                                  <b class="blue pull-right"> <?php echo $client->s('denomination');?> </b>                                        
                                    
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Catégorie
                                                   <b class="blue pull-right"><?php echo $client->s('categorie_client');?>  </b>                                      
                                                                                                    
                                            </li>                                       

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Raison Sociale

                                                    <b class="blue pull-right"><?php echo $client->s('r_social');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Registre Commerce

                                                    <b class="blue pull-right"><?php echo $client->s('r_commerce');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> N° Identifiant Fiscal

                                                    <b class="blue pull-right"><?php echo $client->s('nif');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Pays

                                                    <b class="blue pull-right"><?php echo $client->s('pays');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right blue"></i> Ville

                                                    <b class="blue pull-right"><?php echo $client->s('ville');?></b>
                                            </li>

                                        </ul>
                                    </div>
                                </div><!-- /.col -->

                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                            <b>Informations du Représentant</b>
                                        </div>
                                    </div>

                                    <div>
                                        <ul class="list-unstyled  spaced">
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Nom
                                                   <b class="blue pull-right"><?php echo $client->s('nom');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Prénom
                                                   <b class="blue pull-right"><?php echo $client->s('prenom');?></b>
                                            </li>


                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Civilité 
                                                    <b class="blue pull-right"><?php echo $client->s('civilite');?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Adresse 
                                                    <b class="blue pull-right"><?php echo $client->s('adresse');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Téléphone 
                                                    <b class="blue pull-right"><?php echo $client->s('tel');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Fax 
                                                    <b class="blue pull-right"><?php echo $client->s('fax');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Boite postale 
                                                    <b class="blue pull-right"><?php echo $client->s('bp');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Email 
                                                    <b class="blue pull-right"><?php echo $client->s('email');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Devise 
                                                    <b class="blue pull-right"><?php echo $client->s('devise');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> TVA 
                                                    <b class="blue pull-right"><?php echo $client->s('tva');?></b>
                                            </li>

                                        </ul>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div>
                    </div>
                </div>

                <div class="hr hr8 hr-double hr-dotted"></div>
            </div><!-- /.col sm 10 -->

                        </div><!-- /#home -->

                        <div id="feed" class="tab-pane">
                            <div class="profile-feed row">
                               
                                      <span>
                                    <?php
                                    if ($client_devis == null)
                                        echo '<B>Aucun devis trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px align:center">
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                ID
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Réference
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Commercial
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total HT
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TVA
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total Remises
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TTC
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Document
                                            </th>

                                    <?php
                                            foreach ($client_devis as $devis) {
                                                ?>
                                                <tr>   
                                                    <td style="text-align: left;">
                                                        <span><?php echo $devis['0']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $devis['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $devis['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <span><?php echo $devis['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['5']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['6']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['7']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;" >  
                                                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'devis') ?>" data="<?php echo MInit::crypt_tp('id', $devis['0']) ?>">
                                        <i class="ace-icon fa fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                                <?php
                                            }
                                        }
                                        ?>

                                    </table>
                                </span> 

                                 

                            </div><!-- /. feed row -->

                        </div><!-- /#feed -->


                        <div id="feed1" class="tab-pane">
                            <div class="profile-feed row">
                               
                                      <span>
                                    <?php
                                    if ($client_devis == null)
                                        echo '<B>Aucun devis trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px align:center">
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                ID
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Réference
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Commercial
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total HT
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TVA
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total Remises
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TTC
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Document
                                            </th>

                                    <?php
                                            foreach ($client_devis as $devis) {
                                                ?>
                                                <tr>   
                                                    <td style="text-align: left;">
                                                        <span><?php echo $devis['0']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $devis['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $devis['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <span><?php echo $devis['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['5']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['6']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['7']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;" >  
                                                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'devis') ?>" data="<?php echo MInit::crypt_tp('id', $devis['0']) ?>">
                                        <i class="ace-icon fa fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                                <?php
                                            }
                                        }
                                        ?>

                                    </table>
                                </span> 

                                 

                            </div><!-- /. feed row -->

                        </div><!-- /#feed 1 -->

                         <div id="feed2" class="tab-pane">
                            <div class="profile-feed row">
                               
                                      <span>
                                    <?php
                                    if ($client_devis == null)
                                        echo '<B>Aucun devis trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px align:center">
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                ID
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Réference
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Commercial
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total HT
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TVA
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total Remises
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TTC
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Document
                                            </th>

                                    <?php
                                            foreach ($client_devis as $devis) {
                                                ?>
                                                <tr>   
                                                    <td style="text-align: left;">
                                                        <span><?php echo $devis['0']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo $devis['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $devis['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <span><?php echo $devis['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['5']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['6']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $devis['7']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;" >  
                                                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'devis') ?>" data="<?php echo MInit::crypt_tp('id', $devis['0']) ?>">
                                        <i class="ace-icon fa fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                                <?php
                                            }
                                        }
                                        ?>

                                    </table>
                                </span> 

                                 

                            </div><!-- /. feed row -->

                        </div><!-- /#feed 2 -->

                        </div><!-- /#tab-content -->
                    </div><!-- /#tattable -->
                </div>
            </div>

        </div><!-- /#col x12 -->
</div><!-- /.row -->

<!-- page specific plugin scripts -->
<script type="text/javascript">
    var scripts = [null, null]
    $('.page-content-area').ace_ajax('loadScripts', scripts, function() {
      //inline scripts related to this page
    });
</script>