<?php
//Get all VSAT_Station info
 $info_anonyme= new Manonyme();
//Set ID of Module with POST id
 $info_anonyme->id_anonyme = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_anonyme->get_anonyme())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_anonyme->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 

 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('anonymes','Liste des anonymes', Null, NULL, 'reply');?>
					
	</div>
</div>

<div class="page-header">
	<h1>
		Modifier anonyme
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_anonyme->Shw('titre',1).' -'.$info_anonyme->id_anonyme.'-)';?>
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
$form = new Mform('editanonyme', 'editanonyme', $info_anonyme->Shw('id',1), 'anonymes','1');
$form->input_hidden('id', $info_anonyme->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations permissionaire anonyme ');

//Titre
$titre_array[]  = array('required', 'true', 'Insérer un titre' );
$titre_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Titre', 'titre', 'text' ,6 , $info_anonyme->Shw('titre',1), $titre_array);


//Longitude
$longitude_array[]  = array('required', 'true', 'Insérer la longitude' );
$form->input('Longitude', 'longi', 'text' ,6 , $info_anonyme->Shw('longi',1), $longitude_array);

//Latitude
$latitude_array[]  = array('required', 'true', 'Insérer la latitude' );
$form->input('Latitude', 'latit', 'text' ,6 , $info_anonyme->Shw('latit',1), $latitude_array);

//Technologie

//Technologie
$technologie_array  = array('VSAT' => 'VSAT', 'BLR' => 'BLR','UHF/VHF'=>'UHF/VHF' );
$form->select('Technologie', 'technologie', 3,$technologie_array,null, $info_anonyme->Shw('technologie',1), $multi = NULL );

//Date de la visite
$array_date[]= array('required', 'true', 'Insérer la date de visite');
$form->input_date('Date visite', 'date_visite', 4, $info_anonyme->Shw('date_visite',1), $array_date);

$form->step_end();
$form->step_start(2, 'Remarques et images');


$form->input_editor('Remarques', 'remarque', 8, $info_anonyme->Shw('remarque',1), $js_array = null);
//Zone gallery photos //Creat input photo_id[] and photo_titl[]
//$label[] = array('Photo Antenne - Photo Radio - Photo Modem - capture ecran test de débit (via le site www.speedtest.net)');
$form->gallery_bloc(null, null, $info_anonyme->Shw('pj_images',1));

//Button submit 
$form->button('Modifier Anonyme');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
