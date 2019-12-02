<?php 
//SYS GLOBAL TECH
// Modul: details client => View

//Get all clients info 
 $client  = new Mclients();

//Set ID of Module with POST id
 $client->id_client = Mreq::tp('id');
 $client->get_client();
 $pj    = $client->client_info['pj'];
 $photo = Minit::get_file_archive($client->client_info['pj_photo']);

 $client->get_list_devis();
 $client_devis = $client->devis_info;
 $client->get_total_devis();
 $total_devis= $client->tot_devis_info;

 $client->get_list_abn();
 $client_abn = $client->abn_info;

 $client->get_list_factures();
 $client_fact = $client->factures_info;
 $client->get_total_fact();
 $total_fact= $client->tot_factures_info;

 $client->get_list_encaissements();
 $client_enc= $client->enc_info;

 $client-> get_total_enc();
 $total_enc= $client->tot_enc_info;

 $client-> get_list_bls();
 $client_bl= $client->bl_info;
 
 $client->get_list_tickets();
 $client_tickets=$client->tickets_info;
 
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$client->get_client())
{   
    // returne message error red to client 
    exit('3#'.$client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
$tab_devis        = view::tab_render('devis', 'Devis', $add_set=NULL, 'money' , false, 'feed');
$tab_abn          = view::tab_render('contrats', 'Contrats', $add_set=NULL, 'book' , false,'feed1');
$tab_bl           = view::tab_render('bl', 'Bons de Livraison', $add_set=NULL, 'money' , false, 'feedbl');
$tab_factures     = view::tab_render('factures', 'Factures', $add_set=NULL, 'money' , false, 'feed2');
$tab_encaissement = view::tab_render('encaissements', 'Encaissements', $add_set=NULL, 'money' , false, 'feedenc');
$tab_mvmts_compte = view::tab_render('clients', 'Etat de compte', $add_set=NULL, 'money' , false, 'etat_compte');
$tab_ticket       = view::tab_render('tickets', 'Tickets', $add_set=NULL, 'money' , false, 'feedtickets');

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
<?php Mmodul::get_statut_etat_line('clients', $client->g('etat'));
//echo $actions;
?>
        <!-- PAGE CONTENT BEGINS -->
        
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

                        <?php
                        if($tab_devis['tb_rl'])
                        { ?>
                        <li>
                            <a data-toggle="tab" href="#feed">
                                <i class="dodger blue ace-icon fa fa-send bigger-120"></i>
                                Devis
                            </a>
                        </li>
                        <?php
                        }
                        
                        if($tab_abn['tb_rl'])
                        { ?>
                        <li>
                            <a data-toggle="tab" href="#feed1">
                                <i class="orange ace-icon fa fa-inbox bigger-120"></i>
                                Abonnements
                            </a>
                        </li>
                        <?php
                        }
                
                        if($tab_bl['tb_rl'])
                        { ?>
                        <li>
                            <a data-toggle="tab" href="#feedbl">
                                <i class="purple ace-icon fa fa-bookmark-o bigger-120"></i>
                                BLS
                            </a>
                        </li>
                        <?php
                        }

                        if($tab_factures['tb_rl'])
                        { ?>
                        

                         <li>
                            <a data-toggle="tab" href="#feed2">
                                <i class="pink ace-icon fa fa-file bigger-120"></i>
                                Factures
                            </a>
                        </li>
                        <?php
                        }

                        if($tab_encaissement['tb_rl'])
                        { ?>
                        <li>
                            <a data-toggle="tab" href="#feedenc">
                                <i class="red ace-icon fa fa-money bigger-120"></i>
                                Encaissements
                            </a>
                        </li>
                        <?php
                        }
                        if($tab_encaissement['tb_rl'])
                        { ?>
                        <li>
                            <a data-toggle="tab" href="#etat_compte">
                                <i class="orange ace-icon fa fa-bank bigger-120"></i>
                                Etat de compte
                            </a>
                        </li>
                        <?php
                        }
                        if($tab_encaissement['tb_rl'])
                        {
                        ?>
                        <li>
                            <a data-toggle="tab" href="#feedtickets">
                                <i class="red ace-icon fa fa-ticket bigger-120"></i>
                                Tickets
                            </a>
                        </li>
                        <?php
                        }?>
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
                         <?php
                                    if ($client->g('etat') == Msetting::get_set('etat_client', 'client_bloque') ) {
                                        ?>
                                       <div>
                                            <b class="red pull-right margin-left: 30px"> <?php echo $client->s('date_blocage')?>&nbsp;&nbsp;&nbsp;</b> 
                                            <b class="grey pull-right"> &nbsp;&nbsp;&nbsp;Date :&nbsp;</b>

                                            <b class="red pull-right margin-left: 30px"> <?php echo $client->s('commentaire')?></b> 
                                            <b class="grey pull-right">&nbsp;&nbsp;&nbsp; Commentaire :&nbsp;</b>
                                           
                                            <b class="red pull-right margin-left: 30px"> <?php echo $client->s('motif')?></b> 
                                            <b class="grey pull-right"> Motif de blocage:&nbsp;</b>   
                                        </div>
                        <?php 
                            }
                        ?>

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
                    </div><!-- #widget header -->

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
                                </div><!-- /.col sm 6-->

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
                                </div><!-- /.col sm 6-->

                            </div><!-- /.row -->
                        </div><!--widget main-->
                    </div><!--widget body-->
                </div><!--widget-box transparent-->

                        </div><!-- /.col sm 10 -->

                        </div><!-- /#home -->

                        <div id="feed" class="tab-pane">
                            <div class="profile-feed row">
                                   
                                <span>
                                   
<b class="blue pull-right margin-left: 30px"><?php TableTools::btn_add('adddevis', 'Ajouter Devis',MInit::crypt_tp('id_clnt', Mreq::tp('id')) . MInit::crypt_tp('&tsk_aft', 'detailsclient'), $exec = NULL);  ?> </b></br></br>
       
                                    <?php
                                    if ($client_devis == null)
                                        echo '<B>Aucun devis trouvé</B> ';
                                    else {
                                                     ?>
                                        <div>
                                            

                                            <b class="blue pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b> 
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $total_devis['totalht']?>&nbsp;</b> 
                                            <b class="grey pull-right"> Total HT:&nbsp;&nbsp;&nbsp;</b>   
                                            </br>
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b> 
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $total_devis['totalttc']?>&nbsp;</b> 
                                            <b class="grey pull-right"> Total TTC:&nbsp;&nbsp;&nbsp;</b>   
                                            </br></b> </br></b> 
                                        </div>

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
                                                Etat
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Devis
                                            </th>

                                    <?php
                                            foreach ($client_devis as $devis) {?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $devis['0']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
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
                                                     <td style="text-align: center;">
                                                        <span><?php Mmodul::get_etat_line('devis', $devis['8']); ?>
                                                        </span>
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
<div>
    <b class="blue pull-right margin-left: 30px"><?php TableTools::btn_add('addcontrats', 'Ajouter Abonnement',MInit::crypt_tp('id_clnt', Mreq::tp('id')) . MInit::crypt_tp('&tsk_aft', 'detailsclient'), $exec = NULL);  ?> </b></br></br>
</div>                                    
                                    <?php
                                    if ($client_abn == null)
                                        echo '<B>Aucun abonnement trouvé</B> ';
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
                                                Echéance
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date Effet
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date Fin
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Etat
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Contrats
                                            </th>

                                    <?php
                                            foreach ($client_abn as $abn) {
                                                ?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $abn['0']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $abn['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $abn['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $abn['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $abn['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $abn['5']; ?></span>
                                                    </td>
                                                     <td style="text-align: center;">
                                                        <span><?php Mmodul::get_etat_line('contrats', $abn['7']); ?>
                                                        </span>
                                                    </td>

                                                    <td style="text-align: center;" >  
                                                        <?php if( $abn['6'] != null){ ?>
                                                           <a href="#" class="iframe_pdf" rel=<?php echo $abn[6]; ?>>
                                                                <i class="ace-icon fa fa-print"></i>
                                                            </a>    
                                                            <?php } ?>
                                                    </td>
                                                </tr>
                                    <?php } } ?>

                                    </table>
                                </span> 

                                 

                            </div><!-- /. feed row -->

                        </div><!-- /#feed 1 -->

                         <div id="feed2" class="tab-pane">
                            <div class="profile-feed row">
                                
                                <span>
                                    <?php
                                    if ($client_fact == null)
                                        echo '<B>Aucune facture trouvée</B> ';
                                    else {
                                        ?>
                                        <div>
                                            <b class="red pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b>
                                            <b class="red pull-right margin-left: 30px"> <?php echo $total_fact['reste']?>&nbsp;</b> 
                                            <b class="grey pull-right">&nbsp;&nbsp;&nbsp;Reste à Payer:&nbsp;&nbsp;&nbsp;</b>  

                                            <b class="green pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b>
                                            <b class="green pull-right margin-left: 30px"> <?php echo $total_fact['paye']?>&nbsp;</b> 
                                            <b class="grey pull-right">&nbsp;&nbsp;&nbsp;Total Payé:&nbsp;&nbsp;&nbsp;</b>

                                            <b class="blue pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b>
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $total_fact['totalttc']?>&nbsp;</b> 
                                            <b class="grey pull-right">&nbsp;&nbsp;&nbsp; Total TTC:&nbsp;&nbsp;&nbsp;</b>

                                            <b class="blue pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b>
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $total_fact['totalht']?>&nbsp;</b> 
                                            <b class="grey pull-right">&nbsp;&nbsp;&nbsp; Total HT:&nbsp;&nbsp;&nbsp;</b> 
                                            
                                            </br></b> </br></b> 
                                        </div>

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
                                                Base Facturation
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total HT
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TVA
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total TTC
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Total Payé
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Reste
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Etat
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Factures
                                            </th>
                                    <?php
                                            foreach ($client_fact as $fact) {
                                                ?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $fact['0']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $fact['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $fact['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $fact['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $fact['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $fact['5']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $fact['6']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $fact['7']; ?></span>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $fact['8']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php Mmodul::get_etat_line('factures', $fact['9']); ?>
                                                        </span>
                                                    </td>
                                                    <td style="text-align: center;" >  
                                                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'facture') ?>" data="<?php echo MInit::crypt_tp('id', $fact['0']) ?>">
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

                         <div id="feedenc" class="tab-pane">
                            <div class="profile-feed row">
                                   
                                <span>
                                    <?php
                                    if ($client_enc == null)
                                        echo '<B>Aucun encaissement trouvé</B> ';
                                    else {
                                        ?>
                                        <div>
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $client->s('dev')?></b> 
                                            <b class="blue pull-right margin-left: 30px"> <?php echo $total_enc['total_enc']?>&nbsp;</b> 
                                            <b class="grey pull-right"> Total Encaissé:&nbsp;&nbsp;&nbsp;</b>   
                                            </br></b> </br></b> 
                                        </div>

                                        <table class="table table-striped table-bordered table-hover" style="width: 800px align:center">
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                ID
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Réference
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Désignation
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Dépositaire
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Référence de paiement
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Mode de paiement
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Montant payé
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Etat
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Encaissements
                                            </th>
                                    <?php
                                            foreach ($client_enc as $enc) {?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $enc['0']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $enc['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <span><?php echo $enc['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <span><?php echo $enc['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $enc['4']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $enc['5']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $enc['6']; ?></span>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <span><?php echo $enc['7']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php Mmodul::get_etat_line('encaissements', $enc['9']); ?>
                                                        </span>
                                                    </td>
                                                    <td style="text-align: center;" >  
                                                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'recepisse') ?>" data="<?php echo MInit::crypt_tp('id', $enc['0']) ?>">
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

                            </div><!-- /. feed enc row -->
                      
                        </div><!-- /#feed enc -->

                        <div id="feedbl" class="tab-pane">
                            <div class="profile-feed row">
                                   
                                <span>
                                    <?php
                                    if ($client_bl == null)
                                        echo '<B>Aucun BL trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px align:center;">
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
                                                Projet
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Etat
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                BLs
                                            </th>

                                    <?php
                                            foreach ($client_bl as $bl) {?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $bl['0']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $bl['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $bl['2']; ?></span>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <span><?php echo $bl['3']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php Mmodul::get_etat_line('bl', $bl['4']); ?>
                                                        </span>
                                                    </td>
                                                    
                                                    <td style="text-align: center;" >  
                                                        <a href="#" class="report_tplt" rel="<?php echo MInit::crypt_tp('tplt', 'bl') ?>" data="<?php echo MInit::crypt_tp('id', $bl['0']) ?>">
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

                            </div><!-- /. feed row bl -->
                      
                        </div><!-- /#feed bl-->
                        
                         <div id="etat_compte" class="tab-pane">
                            <div class="profile-feed row">
                                   
                                <span>

                                     <?php
                     if($tab_mvmts_compte['tb_rl'])
                     {
                        echo $tab_mvmts_compte['tcs'];
                        //Content (includ file - simple string - function return string)
                        include 'etat_compte_v.php';
                        echo $tab_mvmts_compte['tce'];
                     }
                                    ?>  

                                    
                                </span>        

                            </div><!-- /. feed row etat compte -->
                      
                        </div><!-- /#feed etat compte-->
                        
                        
                         <div id="feedtickets" class="tab-pane">
                            <div class="profile-feed row">
                                   
                                <span>
                                    <div>
    <b class="blue pull-right margin-left: 30px"><?php TableTools::btn_add('addtickets', 'Ajouter Ticket',MInit::crypt_tp('id_clnt', Mreq::tp('id')) . MInit::crypt_tp('&tsk_aft', 'detailsclient'), $exec = NULL);  ?> </b></br></br>
</div> 
                                    <?php
                                    if ($client_tickets == null)
                                        echo '<B>Aucun ticket trouvé</B> ';
                                    else {
                                        ?>
                                        <table class="table table-striped table-bordered table-hover" style="width: 800px align:center">
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                ID
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Client
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Site
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Date création
                                            </th>
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Technicien
                                            </th>                                            
                                            <th style="text-align: center;"><font color="#5C9EDB">
                                                Etat
                                            </th>
                                            
                                    <?php
                                            foreach ($client_tickets as $ticket) {?>
                                                <tr>   
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ticket['1']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ticket['22']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ticket['25']; ?></span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span><?php echo $ticket['26']; ?></span>
                                                    </td>
                                                     <td style="text-align: center;">
                                                        <span><?php echo $ticket['21']; ?></span>
                                                    </td>
                                                     <td style="text-align: center;">
                                                        <span><?php Mmodul::get_etat_line('tickets', $ticket['16']); ?>
                                                        </span>
                                                    </td>
                                                                                                                                                                                                               
                                                </tr>


                                                <?php
                                            }
                                        }
                                        ?>

                                        </table>
                                </span>        

                            </div><!-- /. feed enc row -->
                      
                        </div><!-- /#feed tickets -->
                                   

                    </div><!-- /#tab-content -->
                    
                    
                
                                
                    </div><!-- /#tattable -->
                </div>
            </div><!-- /# user profile -->
        </div>

</div><!-- /.row -->

<!-- page specific plugin scripts -->
<script type="text/javascript">
    var scripts = [null, null]
    $('.page-content-area').ace_ajax('loadScripts', scripts, function() {
      //inline scripts related to this page
    });
</script>