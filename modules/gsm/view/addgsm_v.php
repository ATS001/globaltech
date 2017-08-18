<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('gsm','Liste Stations GSM', Null, NULL, 'reply');?>
					
	</div>
</div>

<div class="page-header">
	<h1>
		Ajouter une Station GSM
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
$form = new Mform('addgsm', 'addgsm', '', 'gsm' , '1');
//Etapes Wizard form
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$wizard_array[] = array(4,'Etape 4');
$wizard_array[] = array(5,'Etape 5');

//Set Form Wizard
$form->wizard_steps = $wizard_array;
//Info permissionaire et station
$form->step_start(1, 'Informations permissionaire et Site ');
//Formulaire
$form->input('Formulaire', 'pj', 'file', 6, null, null);
$form->file_js('pj', 500000, 'pdf');

$user_info = new Musers();
$user_info->id_user = session::get('userid');
$user_info->get_user();
$service=$user_info->Shw('service_user',1);

//Get all Permissionnaire info
//$info_prm= new Mprms();
//$info_prm->get_id_prm ($service);

//ID Permissionnaire
//$array_perm[]  = array('required', 'true', 'Choisir le permissionaire' );
//$form->select_table('Permissionnaire', 'id_perm', 8, 'permissionnaires', 'id', 'r_social' , 'r_social', $indx = '------' ,$selected=$prm, $multi=NULL, 'etat = 1'.$serv , $array_perm,null);

//Nom de la Station
$nomstation_array[]  = array('required', 'true', 'Insérer Nom du site' );
$form->input('Nom de la Station', 'nom_station', 'text', 6, null, null);
//Adresse de site
$adress_array[]  = array('required', 'true', 'Insérer Adresse du site' );
$form->input('Adresse de la Station', 'adresse', 'text', 8, null, null);
//Ville de station
$ville_array[]  = array('required', 'true', 'Choisir la Ville' );
$form->select_table('Ville de station', 'ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,43,$multi=NULL, $where=NULL, $ville_array,null);
//Longitude
$longuitude_array[]  = array('required', 'true', 'Insérer la longuitude' );
$longuitude_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$longuitude_array[]  = array('number', 'true', 'Entrez une longitude Valid' );
$form->input('Longitude', 'longi', 'text' ,6 , null, $longuitude_array);
//latitude
$altitude_array[]  = array('required', 'true', 'Insérer la Latitude' );
$altitude_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$altitude_array[]  = array('number', 'true', 'Entrez une latitude Valid' );
$form->input('Latitude', 'latit', 'text', 6, null, $altitude_array);
//Fin Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Informations Support et Energie');
//Type Support
$typ_sup_array  = array('Pylone' => 'Pylone', 'Rooftop' => 'Rooftop' );
$form->select('Type de Support', 'type_support', 3, $typ_sup_array, Null,'Pylone', $multi = NULL );
//Site Partagé
$bool_value_array  = array('0' => 'Non', '1' => 'Oui' );//Used for all select bool
$form->select('Site Partagé', 'shared_site', 3, $bool_value_array, Null,'0', $multi = NULL );
//Opérateur Partage Si est Site partagé
$form->input('Opérateur de partage', 'oper_share', 'text', 8, null, Null);
$form->bloc_title('Source d\'energie');
//Energie Group
$form->select('Groupe Electrogène', 'power_generator', 3, $bool_value_array, Null,'0', $multi = NULL );
//Réseau electrique
$form->select('Réseau Eléctrique', 'power_company', 3, $bool_value_array, Null,'0', $multi = NULL );
//Energie Solaire
$form->select('Energie Solaire', 'power_solar', 3, $bool_value_array, Null,'0', $multi = NULL );
//End step 2
$form->step_end();
//Start step 3
$form->step_start(3, 'Système d\'interconnexion');
//Interconnexion FH
$form->select('Interconnexion F.H', 'bh_fh', 3, $bool_value_array, Null,'0', $multi = NULL );
//Interconnexion VSAT
$form->select('Interconnexion VSAT', 'bh_vsat', 3, $bool_value_array, Null,'0', $multi = NULL );
//Interconnexion Fibre
$form->select('Interconnexion Fibre ', 'bh_fibre', 3, $bool_value_array, Null,'0', $multi = NULL );
//End Step 3
$form->step_end();
//Start Step 4
$form->step_start(4, 'Technolgies déployées');
//Technologie 2G
$form->select('Technologie 2G ', 'tech_2g', 3, $bool_value_array, Null,'1', $multi = NULL );
//Technologie 3G
$form->select('Technologie 3G ', 'tech_3g', 3, $bool_value_array, Null,'0', $multi = NULL );
//Technologie 4G
$form->select('Technologie 4G ', 'tech_4g', 3, $bool_value_array, Null,'0', $multi = NULL );
//Technologie CDMA
$form->select('Technologie CDMA ', 'tech_cdma', 3, $bool_value_array, Null,'0', $multi = NULL );
//End Step 4
$form->step_end();
//Start Step 7
$form->step_start(5, 'Fihiers à ajouter');
//Zone gallery photos //Creat input photo_id[] and photo_titl[]
$label[] = array('Photo Support - Photo BTS');
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
