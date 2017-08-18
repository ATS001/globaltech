<?php
//Get all VSAT_Station info
 $info_vsat= new Mvsat();
//Set ID of Module with POST id
 $info_vsat->id_vsat = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_vsat->get_vsat())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_vsat->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 

 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('vsat','Liste Stations VSAT', Null, NULL, 'reply');?>
					
	</div>
</div>

<div class="page-header">
	<h1>
		Modifier Station VSAT
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_vsat->Shw('nom_station',1).' -'.$info_vsat->id_vsat.'-)';?>
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
$form = new Mform('editvsat', 'editvsat', $info_vsat->Shw('id',1), 'vsat','1');
$form->input_hidden('id', $info_vsat->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
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
$form->input('Formulaire', 'pj', 'file', 6, 'Formulaire.pdf', null);
$form->file_js('pj', 500000, 'pdf', $info_vsat->Shw('pj',1), 1);
//Date laste visite
$array_date[]= array('required', 'true', 'Insérer la date de visite');
$form->input_date('Date visite', 'last_visite', 4, $info_vsat->Shw('last_visite',1), $array_date);
//ID Permissionnaire
$array_perm[]  = array('required', 'true', 'Choisir le permissionaire' );
$form->select_table('Permissionnaire', 'id_perm', 8, 'permissionnaires', 'id', 'r_social' , 'r_social', $indx = '------' ,$info_vsat->Shw('id_perm',1),$multi=NULL, 'etat = 1' , $array_perm,null);
//Nom de la Station
$nomstation_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom de la Station', 'nom_station', 'text', 6, $info_vsat->Shw('nom_station',1), null);

//Revendeur
//$array_revnd[]  = array('required', 'true', 'Choisir le Revendeur' );
$form->select_table('Revendeur', 'revendeur', 6, 'revendeurs', 'id', 'denomination' , 'denomination', $indx = '------' ,$info_vsat->Shw('revendeur',1),$multi=NULL, $where=NULL, null,null);
//Installateur
//$array_instal[]  = array('required', 'true', 'Choisir Installateur' );
$form->select_table('Installateur', 'installateur', 6, 'installateurs', 'id', 'denomination' , 'denomination', $indx = '------' ,$info_vsat->Shw('installateur',1),$multi=NULL, $where=NULL, null,null);

//Fin Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Informations HUB & Opérateurs');
//Choix de HUB
$array_hub[]  = array('required', 'true', 'Choisir le Hub' );
$form->select_table('Hub', 'hub', 6, 'vsat_hub', 'id', 'operateur' , 'operateur', $indx = '------' ,$info_vsat->Shw('hub',1),$multi=NULL, $where=NULL, $array_hub,null);
//Choix Satellite
$array_satel[]  = array('required', 'true', 'Choisir le Satellite' );
$form->select_table('Satellite', 'satellite', 6, 'vsat_satellite', 'id', 'satellite' , 'satellite', $indx = '------' ,$info_vsat->Shw('satellite',1),$multi=NULL, $where=NULL, $array_satel,null);
//End step 2
$form->step_end();
//Start step 3
$form->step_start(3, 'Caractéristiques Techniques (I)');
//Ville de station
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville de station', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$info_vsat->Shw('ville',1),$multi=NULL, $where=NULL, $ville_array,null);
//Longitude
$longuitude_array[]  = array('required', 'true', 'Insérer la longuitude' );
$longuitude_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Longitude', 'longi', 'text' ,6 , $info_vsat->Shw('longi',1), $longuitude_array);
//latitude
$altitude_array[]  = array('required', 'true', 'Insérer la Latitude' );
$altitude_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Latitude', 'latit', 'text', 6, $info_vsat->Shw('latit',1), $altitude_array);
//Architicture réseau
$arch_array  = array('E' => 'Etoile', 'M' => 'Maillé' );
$form->select('Architecture réseau', 'arch_reseau', 3, $arch_array, Null,$info_vsat->Shw('arch_reseau',1), $multi = NULL );
//Band Fréquence
$band_array  = array('C' => 'C-BAND','KU' => 'KU-BAND' , 'KA' => 'KA-BAND' );
$form->select('Bande Fréquence', 'bande_freq', 3, $band_array, Null ,$info_vsat->Shw('bande_freq',1), $multi = NULL );
//Utilisation
$use_array   = array('Internet' => 'Connexion Internet', 'backhaul' => 'Interconnexion Sites' );
$form->select('Type d\'utilisation', 'utilisation', 4, $use_array, Null ,$info_vsat->Shw('utilisation',1), $multi = NULL );
//Pays prevenance matériel
$pays_array[]  = array('required', 'true', 'Choisir le Pays de prevenance' );
$form->select_table('Provenance Matériel', 'pay_materiel', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '------' ,$info_vsat->Shw('pay_materiel',1),$multi=NULL, $where=NULL, $pays_array,null);
//Date d'entrée du matériel
$form->input_date('Date d\'entrée', 'dat_entr_materiel', 6, $info_vsat->Shw('dat_entr_materiel',1), null);
//End Step 3
$form->step_end();
//Start Step 4
$form->step_start(4, 'Configuration Antenne');
//Diametre antenne
$array_diam[]  = array('required', 'true', 'Diamètre Antenne' );
$form->input('Diamètre (m) ', 'diametre_antenne', 'text' ,2 , $info_vsat->Shw('diametre_antenne',1), $array_diam);
//Marque antenne
$array_marq_ant[]  = array('required', 'true', 'Marque Antenne' );
$form->input('Marque', 'marque_antenne', 'text', 6, $info_vsat->Shw('marque_antenne',1), $array_marq_ant);
//Azimuth
$array_azimut[]  = array('required', 'true', 'Azimuth' );
$form->input('Azimut', 'azimut', 'text' ,4 , $info_vsat->Shw('azimut',1), $array_azimut);
//Tilt
$array_tilt[]  = array('required', 'true', 'Inclinaison' );
$form->input('Inclinaison', 'tilt', 'text' ,4 , $info_vsat->Shw('tilt',1), $array_tilt);
//Polarisation
$array_pol[]  = array('required', 'true', 'Polarisation' );
$form->input('Polarisation', 'polarisation', 'text', 4, $info_vsat->Shw('polarisation',1), $array_pol);
//End Step 4
$form->step_end();
//Start Step 5
$form->step_start(5, 'Configuration Radio(BUC - LNB)');
//Marq Radio
$array_marq_rad[]  = array('required', 'true', 'Marque Radio' );
$form->input('Marque Radio', 'marque_radio', 'text' ,4 , $info_vsat->Shw('marque_radio',1), $array_marq_rad);
//NS Radio
$array_ns_rad[]  = array('required', 'true', 'N° de série Radio' );
$form->input('N° de série Radio', 'ns_radio', 'text' ,4 , $info_vsat->Shw('ns_radio',1), $array_ns_rad);
//Fréquence TX
$array_freq_tx[]  = array('required', 'true', 'Fréquence TX' );
$form->input('Fréquence TX', 'tx_freq', 'text', 4, $info_vsat->Shw('tx_freq',1), $array_freq_tx);
//Marque LNB
$array_marq_lnb[]  = array('required', 'true', 'Marque LNB' );
$form->input('Marque LNB', 'marque_lnb', 'text' ,4 , $info_vsat->Shw('marque_lnb',1), $array_marq_lnb);
//NS LNB
$array_ns_lnb[]  = array('required', 'true', 'N° de série LNB' );
$form->input('N° de série LNB', 'ns_lnb', 'text' ,4 , $info_vsat->Shw('ns_lnb',1), $array_ns_lnb);
//Fréquence RX
$array_freq_rx[]  = array('required', 'true', 'Fréquence RX' );
$form->input('Fréquence RX', 'rx_freq', 'text', 4, $info_vsat->Shw('rx_freq',1), $array_freq_rx);
//End Step 5
$form->step_end();
//Start Step 6
$form->step_start(6, 'Configuration Modem');
//Marque Modem
$array_marq_modem[]  = array('required', 'true', 'Marque Modem' );
$form->input('Marque Modem', 'marque_modem', 'text' ,4 , $info_vsat->Shw('marque_modem',1), $array_marq_modem);
//NS Modem
$array_ns_modem[]  = array('required', 'true', 'N° de série de Modem' );
$form->input('N° de série', 'ns_modem', 'text' ,4 , $info_vsat->Shw('ns_modem',1), $array_ns_modem);
//Adresse IP
$array_ip[]  = array('required', 'true', 'Adresse IP' );
$form->input('IP', 'ip', 'text', 4, $info_vsat->Shw('ip',1), null);
//Débit download
$array_deb_up[]  = array('required', 'true', 'Débit download' );
$form->input('Débit download', 'debit_download', 'text' ,5 , $info_vsat->Shw('debit_download',1), $array_deb_up);
//Débit Upload 
$array_deb_down[]  = array('required', 'true', 'Débit upload' );
$form->input('Débit upload', 'debit_upload', 'text', 5, $info_vsat->Shw('debit_upload',1), $array_deb_down);
$form->input('Coût mensuel', 'cout_mensuel', 'text', 5, $info_vsat->Shw('cout_mensuel',1), null);
//End Step 6
$form->step_end();
//Start Step 7
$form->step_start(7, 'Remarques & Fihiers à ajouter');
//Editeur de remarques
$form->input_editor('Remarques', 'remarq', 8, $info_vsat->Shw('remarq',1), $js_array = null);
//Zone gallery photos //Creat input photo_id[] and photo_titl[]
$label[] = array('Photo Antenne - Photo Radio - Photo Modem - capture ecran test de débit (via le site www.speedtest.net)');
$form->gallery_bloc(null, $label, $info_vsat->Shw('pj_images',1));
//End Step 7
$form->step_end();
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
