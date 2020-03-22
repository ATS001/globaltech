<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: sites
//Created : 17-02-2019
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('sites','Liste des sites', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un sites
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
 
$form = new Mform('addsites', 'addsites', '', 'sites', '0', null);

//photo
$form->input('Photo', 'photo', 'file', 6, null, null);
$form->file_js('photo', 500000, 'image');

//Type Site
 $array_site = array('RADIO' => 'RADIO', 'VSAT' => 'VSAT');
 $form->select('Type Site', 'type_site', 2, $array_site, $indx = NULL, $selected = 'RADIO', $multi = NULL);

//Client
$client_array[] = array('required', 'true', 'Choisir un client');
$form->select_table('Client', 'id_client', 6, 'clients', 'id', 'denomination', 'denomination', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat= 1', $client_array);

//Date mise en ligne
$date_mes_array[]= array('required', 'true', 'Insérez la date de mise en service');
$form->input_date('Date mise en service', 'date_mes', 2, date('d-m-Y'), $date_mes_array);


//Station de base
 $array_site = array('BTS CDG' => 'BTS CDG', 'BTS NDJARI' => 'BTS NDJARI');
 $form->select('Station de base', 'basestation', 2, $array_site, $indx = NULL, $selected = 'BTS CDG', $multi = NULL);

//secteur
$form->input('Secteur', 'secteur', 'text', 6, null, null);

//antenne
$ant_array[]  = array('required', 'true', 'Entrez une antenne' );
$form->input('Antenne', 'antenne', 'text', 6, null, $ant_array);



//modem
$modem_array[]  = array('required', 'true', 'Entrez un modem' );
$form->input('Modem', 'modem', 'text', 6, null, $modem_array);

//sn_modem
$sn_array[]  = array('required', 'true', 'Entrez un SN modem' );
$form->input('SN Modem', 'sn_modem', 'text', 6, null, $sn_array);

//bande
$form->input('Bande', 'bande', 'text', 6, null, null);

//satellite
$form->input('Satellite', 'satellite', 'text', 6, null, NULL);


//lnb
$form->input('LNB', 'lnb', 'text', 6, null, NULL);

//Buc
$form->input('BUC', 'buc', 'text', 6, null, NULL);

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
   
    //*******************************************
            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false);     
            $('#secteur').attr('readonly', false);
            $('#antenne').attr('readonly', false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false);          
            
                                   
              document.getElementById('bande').style.display = 'none';
              document.getElementById('label_bande').style.display = 'none';
              
              document.getElementById('buc').style.display = 'none';
              document.getElementById('label_buc').style.display = 'none';
              
              document.getElementById('satellite').style.display = 'none';
              document.getElementById('label_satellite').style.display = 'none';
              
              document.getElementById('lnb').style.display = 'none';
              document.getElementById('label_lnb').style.display = 'none';
             
    
    //********************************************

    $('#type_site').on('change',function() {
        if($("#type_site option:selected").text() == 'RADIO'){

            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false);         
            $('#secteur').attr('readonly', false);
            $('#antenne').attr('readonly', false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false);          
            
                                    
              document.getElementById('bande').style.display = 'none';
              document.getElementById('label_bande').style.display = 'none';
              
              document.getElementById('buc').style.display = 'none';
              document.getElementById('label_buc').style.display = 'none';
              
              document.getElementById('satellite').style.display = 'none';
              document.getElementById('label_satellite').style.display = 'none';
              
              document.getElementById('lnb').style.display = 'none';
              document.getElementById('label_lnb').style.display = 'none';
            
        }else{
               
              
              document.getElementById('bande').style.display = 'block';
              document.getElementById('label_bande').style.display = 'block';
              
              document.getElementById('buc').style.display = 'block';
              document.getElementById('label_buc').style.display = 'block';
              
              document.getElementById('satellite').style.display = 'block';
              document.getElementById('label_satellite').style.display = 'block';
              
              document.getElementById('lnb').style.display = 'block';
              document.getElementById('label_lnb').style.display = 'block';
              
              
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
            
              
              document.getElementById('secteur').style.display = 'none';
              document.getElementById('label_secteur').style.display = 'none';
              
            
        }

    });	
   });
    </script>