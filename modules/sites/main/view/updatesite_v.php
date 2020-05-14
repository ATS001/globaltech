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

//photo
$form->input('Photo', 'photo', 'file', 6, 'Photo.png', null);
$form->file_js('photo', 100000, 'image', $info_sites->g('photo'), 1);

 $array_site = array('RADIO' => 'RADIO', 'VSAT' => 'VSAT');
 $form->select('Type Site', 'type_site', 2, $array_site, $indx = NULL, $info_sites->g("type_site"), $multi = NULL);

//Client
$client_array[] = array('required', 'true', 'Choisir un client');
$form->select_table('Client', 'id_client', 6, 'clients', 'id', 'denomination', 'denomination', $indx = '------', $info_sites->g("id_client"), $multi = NULL, $where = 'etat= 1', $client_array);

//Nom site
$form->input('Nom du site', 'site_name', 'text', 6,  $info_sites->g("site_name"), NULL);

//Date mise en ligne
$date_mes_array[]= array('required', 'true', 'Insérez la date de mise en service');
$form->input_date('Date mise en service', 'date_mes', 2, $info_sites->g("date_mes"), $date_mes_array);

//Station de base
 $array_site = array('BTS Bureau' => 'BTS Bureau', 'BTS DG' => 'BTS DG');
 $form->select('Station de base', 'basestation', 2, $array_site, $indx = NULL, $info_sites->g("basestation"), $multi = NULL);


 //satellite
 $form->input('Satellite', 'satellite', 'text', 6,$info_sites->g("satellite"), NULL);

 //antenne
 $form->input('Antenne', 'antenne', 'text', 6,$info_sites->g("antenne"), null);

 //secteur
 $form->input('Secteur', 'secteur', 'text', 6,$info_sites->g("secteur"), null);

 //radio
 $form->input('Radio', 'radio', 'text', 6, $info_sites->g("radio"), null);

 // Adresse Mac Routeur
 $form->input('Mac Adress Radio', 'addr_mac_radio', 'text', 6,$info_sites->g("addr_mac_radio"), null);

 //modem
 $form->input('Modem', 'modem', 'text', 6, $info_sites->g("modem"),null);

 //sn_modem
 $form->input('SN Modem', 'sn_modem', 'text', 6,$info_sites->g("sn_modem"), null);

 //routeur
 $form->input('Routeur', 'routeur', 'text', 6, $info_sites->g("routeur"),null);

 // Adresse Mac Routeur
 $form->input('Mac Adress Router', 'addr_mac_router', 'text', 6, $info_sites->g("addr_mac_router"), null);

 //lnb
 $form->input('LNB', 'lnb', 'text', 6,$info_sites->g("lnb"), NULL);

 //Buc
 $form->input('BUC', 'buc', 'text', 6, $info_sites->g("buc"), NULL);

 //Câble
 $form->input('Câble', 'cable', 'text', 6, $info_sites->g("cable"), NULL);

 //bande passante
 $form->input('Bande passante', 'bande', 'text', 6, $info_sites->g("bande"), null);

$form->button('Modifier le site');

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


/* ****************************************************************************** */
if($("#type_site option:selected").text() == 'RADIO'){

    $('#reference').attr('readonly', false);
    $('#id_client').attr('readonly', false);
    $('#date_mes').attr('readonly', false);
    $('#secteur').attr('readonly', false);
    $('#antenne').attr('readonly', false);
    $('#modem').attr('readonly', false);
    $('#sn_modem').attr('readonly', false);

      document.getElementById('xbasestationx').style.display = 'none';
      document.getElementById('xbucx').style.display = 'none';
      document.getElementById('xsatellitex').style.display = 'none';
      document.getElementById('xlnbx').style.display = 'none';
      document.getElementById('xmodemx').style.display = 'none';
      document.getElementById('xsn_modemx').style.display = 'none';


}else  {

     document.getElementById('xsecteurx').style.display = 'none';
     document.getElementById('xradiox').style.display = 'none';
     document.getElementById('xaddr_mac_radiox').style.display = 'none';
     document.getElementById('xbasestationx').style.display = 'none';
     document.getElementById('xmodemx').style.display = 'block';
     document.getElementById('xsn_modemx').style.display = 'block';
}


    $('#type_site').on('change',function() {
        if($("#type_site option:selected").text() == 'RADIO'){

            $('#reference').attr('readonly', false);
            $('#id_client').attr('readonly', false);
            $('#date_mes').attr('readonly', false);
            $('#secteur').attr('readonly', false);
            $('#antenne').attr('readonly', false);
            $('#modem').attr('readonly', false);
            $('#sn_modem').attr('readonly', false);

              document.getElementById('xbasestationx').style.display = 'none';
              document.getElementById('xbucx').style.display = 'none';
              document.getElementById('xsatellitex').style.display = 'none';
              document.getElementById('xlnbx').style.display = 'none';
              document.getElementById('xmodemx').style.display = 'none';
              document.getElementById('xsn_modemx').style.display = 'none';


              document.getElementById('xsecteurx').style.display = 'block';
              document.getElementById('xbasestationx').style.display = 'block';
              document.getElementById('xradiox').style.display = 'block';
              document.getElementById('xaddr_mac_radiox').style.display = 'block';
              document.getElementById('bande').style.display = 'block';
  document.getElementById('xbandex').style.display = 'bande';

              $('#reference').attr('readonly', false).val('');
            //  $('#id_client').attr('readonly', false).val('');
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
                      $('#routeur').attr('readonly', false).val('');
                              $('#cable').attr('readonly', false).val('');

        }else  if($("#type_site option:selected").text() == 'VSAT') {
               document.getElementById('xbasestationx').style.display = 'block';
              document.getElementById('xbandex').style.display = 'block';
              document.getElementById('xbucx').style.display = 'block';
              document.getElementById('xsatellitex').style.display = 'block';
              document.getElementById('xlnbx').style.display = 'block';
              document.getElementById('xmodemx').style.display = 'block';
              document.getElementById('xsn_modemx').style.display = 'block';

            $('#secteur').attr('readonly', false).val('');
            $('#reference').attr('readonly', false).val('');
      //      $('#id_client').attr('readonly', false).val('');
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
            $('#radio').attr('readonly', false).val('');
            $('#routeur').attr('readonly', false).val('');
            $('#cable').attr('readonly', false).val('');
            $('#site_name').attr('readonly', false).val('');

              document.getElementById('xsecteurx').style.display = 'none';
              document.getElementById('xbasestationx').style.display = 'none';
              document.getElementById('xradiox').style.display = 'none';
              document.getElementById('xaddr_mac_radiox').style.display = 'none';
        }else{

          document.getElementById('xbucx').style.display = 'block';
        	document.getElementById('xsatellitex').style.display = 'block';
        	document.getElementById('xlnbx').style.display = 'block';
        	document.getElementById('xbasestationx').style.display = 'block';
          document.getElementById('xsecteurx').style.display = 'block';



        	$('#secteur').attr('readonly', false).val('');
        	$('#reference').attr('readonly', false).val('');
    //    	$('#id_client').attr('readonly', false).val('');
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

        }

    });
   });
    </script>
