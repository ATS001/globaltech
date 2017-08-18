
<?php
//Get all secteur info
 $info_gsm_secteur= new Mgsm_secteur();
//Set ID of Module with POST id
 $info_gsm_secteur->id_gsm_secteur = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_secteur return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_gsm_secteur->get_gsm_secteur())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_gsm_secteur->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
  //Get id gsm station
 $id_technologie=$info_gsm_secteur->Shw('id_technologie',1);

?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('gsm_secteur', 'Liste des secteurs', MInit::crypt_tp('id',$id_technologie), $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_gsm_secteur->Shw('num_secteur',1).' -'.$info_gsm_secteur->id_gsm_secteur.'-)' ;?>
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


$form = new Mform('editgsm_secteur', 'editgsm_secteur',$info_gsm_secteur->Shw('id',1), 'gsm_secteur&'.MInit::crypt_tp('id',$id_technologie), '0');
$form->input_hidden('id', $info_gsm_secteur->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

// num secteur
//$sec_view  = array('1' => '1', '2' => '2' , '3' => '3' );
//$form->select('N° Secteur', 'num_secteur', 5, $sec_view, $indx = NULL ,$info_gsm_secteur->Shw('num_secteur',1));

//HBA
$hba_array[]  = array('required', 'true', 'Insérer HBA' );
$hba_array[]  = array('number', 'true', 'Entrez un HBA Valid' );
$form->input('H.B.A', 'hba', 'text' ,6 ,  $info_gsm_secteur->Shw('hba',1), $hba_array);

//Azimut
$azimut_array[]  = array('required', 'true', 'Insérer Azimut ' );
$azimut_array[]  = array('number', 'true', 'Entrez un Azimut Valid' );
$form->input('Azimut', 'azimuth', 'text' ,6 ,  $info_gsm_secteur->Shw('azimuth',1), $azimut_array);

//Model de l\'antenne
$mcc_array[]  = array('required', 'true', 'Insérer Tilt mécanique ' );
$mcc_array[]  = array('number', 'true', 'Entrez un Tilt mécanque Valid' );
$form->input('Tilt mécanique', 'tilt_mecanique', 'text' ,6 ,  $info_gsm_secteur->Shw('tilt_mecanique',1), $mcc_array);

//Tilt ELC
$elc_array[]  = array('required', 'true', 'Insérer Tilt ELC ' );
$elc_array[]  = array('number', 'true', 'Entrez un Tilt électrique  Valid' );
$form->input('Tilt électrique', 'tilt_electrique', 'text' ,6 , $info_gsm_secteur->Shw('tilt_electrique',1), $elc_array);


//Button submit 
$form->button('Modifier le secteur');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
