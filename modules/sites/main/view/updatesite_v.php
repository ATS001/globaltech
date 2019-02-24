<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: sites
//Created : 24-02-2019
//View
//Get all sites info 
 $info_sites = new Msites();
//Set ID of Module with POST id
 $info_sites->id_sites = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_sites->get_sites())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('sites','Liste des sites', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le sites: <?php $info_sites->s('id')?>
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
$form = new Mform('updatesite', 'updatesite', '1', 'sites', '0', null);
$form->input_hidden('id', $info_sites->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Type Site

 $array_site = array('RADIO' => 'RADIO', 'VSAT' => 'VSAT');
 $form->select('Type Site', 'type_site', 2, $array_site, $indx = NULL, $info_sites->g("type_site"), $multi = NULL);

//Client
$client_array[] = array('required', 'true', 'Choisir un client');
$form->select_table('Client', 'id_client', 6, 'clients', 'id', 'denomination', 'denomination', $indx = '------', $info_sites->g("id_client"), $multi = NULL, $where = 'etat= 1', $client_array);

//Date mise en ligne
$date_mes_array[]= array('required', 'true', 'Insérez la date de mise en service');
$form->input_date('Date mise en service', 'date_mes', 2, $info_sites->g("date_mes"), $date_mes_array);

//basestation
$form->select_table('Station de base', 'basestation', 6, 'sites', 'id', 'reference', 'reference', $indx = '------', $info_sites->g("basestation"), $multi = NULL, $where = 'etat= 1  AND type_site="RADIO"', NULL);

//secteur
$form->input('Secteur', 'secteur', 'text', 6, $info_sites->g("secteur"),null, null);

//antenne
$form->input('Antenne', 'antenne', 'text', 6,$info_sites->g("antenne"), null, NULL);

//modem
$form->input('Modem', 'modem', 'text', 6,$info_sites->g("modem"), null, NULL);

//sn_modem
$form->input('SN Modem', 'sn_modem', 'text', 6,$info_sites->g("sn_modem"), null, null);

//bande
$form->input('Bande', 'bande', 'text', 6, $info_sites->g("bande"),null, null);

//satellite
$form->input('Satellite', 'satellite', 'text', 6,$info_sites->g("satellite"), null, NULL);


//lnb
$form->input('LNB', 'lnb', 'text', 6,$info_sites->g("lnb"), null, NULL);

//Buc
$form->input('BUC', 'buc', 'text', 6,$info_sites->g("buc"), null, NULL);



$form->button('Enregistrer le site');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
<!-- End Add devis bloc -->
		
    
  <script type="text/javascript">
$(document).ready(function() {
    
    
    
    if($("#type_site option:selected").text() == 'RADIO'){

            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false);         
            $('#basestation').attr('readonly', false);
            $('#secteur').attr('readonly', false);
            $('#antenne').attr('readonly', false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false);          
            
            $('#bande').attr('readonly', true);
            $('#buc').attr('readonly', true);
            $('#satellite').attr('readonly',true);
            $('#lnb').attr('readonly', true);
           
        }else{
            
            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false); 
            $('#satellite').attr('readonly',false);
            $('#bande').attr('readonly',false);
            $('#antenne').attr('readonly',false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false); 
            $('#buc').attr('readonly', false);            
            $('#lnb').attr('readonly', false);
            
             $('#basestation').attr('readonly', true);
             $('#secteur').attr('readonly', true);
        }
    
  
    $('#type_site').on('change',function() {
        if($("#type_site option:selected").text() == 'RADIO'){

            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false);         
            $('#basestation').attr('readonly', false);
            $('#secteur').attr('readonly', false);
            $('#antenne').attr('readonly', false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false);          
            
            $('#bande').attr('readonly', true).val('');
            $('#buc').attr('readonly', true).val('');
            $('#satellite').attr('readonly',true).val('');
            $('#lnb').attr('readonly', true).val('');
            
        }else{
            
            $('#reference').attr('readonly', false).val('');
            $('#id_client').attr('readonly', false).val('');
            $('#date_mes').attr('readonly', false).val(''); 
            $('#satellite').attr('readonly',false).val('');
            $('#bande').attr('readonly',false).val('');
            $('#antenne').attr('readonly',false).val('');
            $('#modem').attr('readonly', false).val('');
            $('#sn_modem').attr('readonly', false).val(''); 
            $('#buc').attr('readonly', false).val('');            
            $('#lnb').attr('readonly', false).val('');
            
             $('#basestation').attr('readonly', true).val('');
             $('#secteur').attr('readonly', true).val('');
        }

    });	
    
   });
    </script>	