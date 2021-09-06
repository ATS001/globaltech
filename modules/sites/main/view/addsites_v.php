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

//Nom site
$form->input('Nom site', 'site_name', 'text', 6, null, NULL);

//Date mise en ligne
$date_mes_array[]= array('required', 'true', 'Insérez la date de mise en service');
$form->input_date('Date mise en service', 'date_mes', 2, date('d-m-Y'), $date_mes_array);
//Station de base
 $array_site = array('BTS Bureau' => 'BTS Bureau', 'BTS DG' => 'BTS DG');
 $form->select('Station de base', 'basestation', 2, $array_site, $indx = NULL, $selected = 'BTS Bureau', $multi = NULL);

 //satellite
 $form->input('Satellite', 'satellite', 'text', 6, null, NULL);

 //antenne
 $form->input('Antenne', 'antenne', 'text', 6, null, null);

//secteur
$form->input('Secteur', 'secteur', 'text', 6, null, null);

//radio
$form->input('Radio', 'radio', 'text', 6, null, null);

// Adresse Mac Routeur
$form->input('Mac Adress Radio', 'addr_mac_radio', 'text', 6, null,null);

//modem
$form->input('Modem', 'modem', 'text', 6, null,null);

//sn_modem
$form->input('SN Modem', 'sn_modem', 'text', 6, null, null);

//routeur
$form->input('Routeur', 'routeur', 'text', 6, null, null);

// Adresse Mac Routeur
$form->input('Mac Adress Router', 'addr_mac_router', 'text', 6, null, null);

//lnb
$form->input('LNB', 'lnb', 'text', 6, null, NULL);

//Buc
$form->input('BUC', 'buc', 'text', 6, null, NULL);

$form->button('Enregistrer le site');

//Câble
$form->input('Câble', 'cable', 'text', 6, null, NULL);

//bande passante
$form->input('Bande passante', 'bande', 'text', 6, null, null);

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

    //******************************************* basestation
            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false);
            $('#secteur').attr('readonly', false);
            $('#antenne').attr('readonly', false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false);

  						document.getElementById('xmodemx').style.display = 'none';
              document.getElementById('xsn_modemx').style.display = 'none';
              document.getElementById('xbucx').style.display = 'none';
              document.getElementById('xsatellitex').style.display = 'none';
              document.getElementById('xlnbx').style.display = 'none';




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


              document.getElementById('xbucx').style.display = 'none';
              document.getElementById('xsatellitex').style.display = 'none';
              document.getElementById('xlnbx').style.display = 'none';
							  document.getElementById('xmodemx').style.display = 'none';
  						document.getElementById('xsn_modemx').style.display = 'none';

								document.getElementById('xsecteurx').style.display = 'block';
							  document.getElementById('xbasestationx').style.display = 'block';
								document.getElementById('xradiox').style.display = 'block';
				   			document.getElementById('xaddr_mac_radiox').style.display = 'block';




        }else if($("#type_site option:selected").text() == 'VSAT'){

            	document.getElementById('xbucx').style.display = 'block';
              document.getElementById('xsatellitex').style.display = 'block';
              document.getElementById('xlnbx').style.display = 'block';
        			document.getElementById('xsn_modemx').style.display = 'block';
  						document.getElementById('xmodemx').style.display = 'block';

						$('#secteur').attr('readonly', false).val('');
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
						$('#addr_mac_radio').attr('readonly', false).val('');
						$('#addr_mac_router').attr('readonly', false).val('');


              document.getElementById('xsecteurx').style.display = 'none';
							document.getElementById('xradiox').style.display = 'none';
							document.getElementById('xaddr_mac_radiox').style.display = 'none';
							document.getElementById('xbasestationx').style.display = 'none';
}

    });
   });
    </script>
