<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('vsat','Liste Stations VSAT', Null, NULL, 'reply');?>
					
	</div>
</div>

<div class="page-header">
	<h1>
		Ajouter une Station VSAT
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
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
//__construct($id_form, $app_exec, $is_edit, $app_redirect, $is_wizard)
$form = new Mform('addvsat', 'addvsat', '', 'vsat' , '1');
//Etapes Wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$wizard_array[] = array(4,'Etape 4');
$wizard_array[] = array(5,'Etape 5');
$wizard_array[] = array(6,'Etape 6');
$wizard_array[] = array(7,'Etape 7');
//Set Form Wizard
$form->wizard_steps = $wizard_array;
//Info permissionaire et station
$form->step_start(1, 'Informations permissionaire et Site ');
//Formulaire
$form->input('Formulaire', 'pj', 'file', 6, null, null);
$form->file_js('pj', 500000, 'pdf');
//Date laste visite
$array_date[]= array('required', 'true', 'Insérer la date de visite');
$form->input_date('Date visite', 'last_visite', 4, date('d-m-Y'), $array_date);
//ID Permissionnaire
$array_perm[]  = array('required', 'true', 'Choisir le permissionaire' );
$form->select_table('Permissionnaire', 'id_perm', 8, 'permissionnaires', 'id', 'r_social' , 'r_social', $indx = '------' ,$selected=NULL,$multi=NULL, 'etat = 1' , $array_perm,null);
//Nom de la Station
$nomstation_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom de la Station', 'nom_station', 'text', 6, null, null);
//Revendeur
//$array_revnd[]  = array('required', 'true', 'Choisir le Revendeur' );
$form->select_table('Revendeur', 'revendeur', 6, 'revendeurs', 'id', 'denomination' , 'denomination', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, null,null);
//Installateur
//$array_instal[]  = array('required', 'true', 'Choisir Installateur' );
$form->select_table('Installateur', 'installateur', 6, 'installateurs', 'id', 'denomination' , 'denomination', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, null,null);
//Fin Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Informations HUB & Opérateurs');
//Choix de HUB
$array_hub[]  = array('required', 'true', 'Choisir le Hub' );
$form->select_table('Hub', 'hub', 6, 'vsat_hub', 'id', 'operateur' , 'operateur', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $array_hub,null);
//Choix Satellite
$array_satel[]  = array('required', 'true', 'Choisir le Satellite' );
$form->select_table('Satellite', 'satellite', 6, 'vsat_satellite', 'id', 'satellite' , 'satellite', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $array_satel,null);
//End step 2
$form->step_end();
//Start step 3
$form->step_start(3, 'Caractéristiques Techniques (I)');
//Ville de station
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville de station', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,43,$multi=NULL, $where=NULL, $ville_array,null);
//Longitude
$longuitude_array[]  = array('required', 'true', 'Insérer la longuitude' );
$longuitude_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Longitude', 'longi', 'text' ,6 , null, $longuitude_array);
//latitude
$altitude_array[]  = array('required', 'true', 'Insérer la Latitude' );
$altitude_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Latitude', 'latit', 'text', 6, null, $altitude_array);
//Architicture réseau
$arch_array  = array('E' => 'Etoile', 'M' => 'Maillé' );
$form->select('Architecture réseau', 'arch_reseau', 3, $arch_array, Null,'E', $multi = NULL );
//Band Fréquence
$band_array  = array('C' => 'C-BAND','KU' => 'KU-BAND' , 'KA' => 'KA-BAND' );
$form->select('Bande Fréquence', 'bande_freq', 3, $band_array, Null ,'C', $multi = NULL );
//Utilisation
$use_array   = array('Internet' => 'Connexion Internet', 'backhaul' => 'Interconnexion Sites' );
$form->select('Type d\'utilisation', 'utilisation', 4, $use_array, Null ,'Internet', $multi = NULL );
//Pays prevenance matériel
$pays_array[]  = array('required', 'true', 'Choisir le Pays de prevenance' );
$form->select_table('Provenance Matériel', 'pay_materiel', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $pays_array,null);
//Date d'entrée du matériel
$form->input_date('Date d\'entrée', 'dat_entr_materiel', 6, date('d-m-Y'), null);
//End Step 3
$form->step_end();
//Start Step 4
$form->step_start(4, 'Configuration Antenne');
//Diametre antenne
$array_diam[]  = array('required', 'true', 'Diamètre Antenne' );
$form->input('Diamètre (m) ', 'diametre_antenne', 'text' ,2 , null, $array_diam);
//Marque antenne
$array_marq_ant[]  = array('required', 'true', 'Marque Antenne' );
$form->input('Marque', 'marque_antenne', 'text', 6, null, $array_marq_ant);
//Azimuth
$array_azimut[]  = array('required', 'true', 'Azimuth' );
$form->input('Azimut', 'azimut', 'text' ,4 , null, $array_azimut);
//Tilt
$array_tilt[]  = array('required', 'true', 'Inclinaison' );
$form->input('Inclinaison', 'tilt', 'text' ,4 , null, $array_tilt);
//Polarisation
$array_pol[]  = array('required', 'true', 'Polarisation' );
$form->input('Polarisation', 'polarisation', 'text', 4, null, $array_pol);
//End Step 4
$form->step_end();
//Start Step 5
$form->step_start(5, 'Configuration Radio(BUC - LNB)');
//Marq Radio
$array_marq_rad[]  = array('required', 'true', 'Marque Radio' );
$form->input('Marque Radio', 'marque_radio', 'text' ,4 , null, $array_marq_rad);
//NS Radio
$array_ns_rad[]  = array('required', 'true', 'N° de série Radio' );
$form->input('N° de série Radio', 'ns_radio', 'text' ,4 , null, $array_ns_rad);
//Fréquence TX
$array_freq_tx[]  = array('required', 'true', 'Fréquence TX' );
$form->input('Fréquence TX', 'tx_freq', 'text', 4, null, $array_freq_tx);
//Marque LNB
$array_marq_lnb[]  = array('required', 'true', 'Marque LNB' );
$form->input('Marque LNB', 'marque_lnb', 'text' ,4 , null, $array_marq_lnb);
//NS LNB
$array_ns_lnb[]  = array('required', 'true', 'N° de série LNB' );
$form->input('N° de série LNB', 'ns_lnb', 'text' ,4 , null, $array_ns_lnb);
//Fréquence RX
$array_freq_rx[]  = array('required', 'true', 'Fréquence RX' );
$form->input('Fréquence RX', 'rx_freq', 'text', 4, null, $array_freq_rx);
//End Step 5
$form->step_end();
//Start Step 6
$form->step_start(6, 'Configuration Modem');
//Marque Modem
$array_marq_modem[]  = array('required', 'true', 'Marque Modem' );
$form->input('Marque Modem', 'marque_modem', 'text' ,4 , null, $array_marq_modem);
//NS Modem
$array_ns_modem[]  = array('required', 'true', '*' );
$form->input('N° de série', 'ns_modem', 'text' ,4 , null, $array_ns_modem);
//Adresse IP
$array_ip[]  = array('required', 'true', 'Adresse IP' );
$form->input('IP', 'ip', 'text', 4, null, null);
//Débit download
$array_deb_up[]  = array('required', 'true', 'Débit download' );
$form->input('Débit download', 'debit_download', 'text' ,5 , null, $array_deb_up);
//Débit Upload 
$array_deb_down[]  = array('required', 'true', 'Débit upload' );
$form->input('Débit upload', 'debit_upload', 'text', 5, null, $array_deb_down);
$form->input('Coût mensuel', 'cout_mensuel', 'text', 5, null, null);
//End Step 6
$form->step_end();
//Start Step 7
$form->step_start(7, 'Remarques & Fihiers à ajouter');
//Editeur de remarques
$form->input_editor('Remarques', 'remarq', 8, $input_value = null, $js_array = null);
//Zone gallery photos //Creat input photo_id[] and photo_titl[]
$label[] = array('Photo Antenne - Photo Radio - Photo Modem - capture ecran test de débit (via le site www.speedtest.net)');
$form->gallery_bloc(null, $label);
//End Step 7
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
