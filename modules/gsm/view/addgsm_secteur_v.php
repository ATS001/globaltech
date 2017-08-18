 <?php 
defined('_MEXEC') or die; 
//Get all Installateur info
 $info_gsm_technologie= new Mgsm_technologie();
//Set ID of Module with POST id
 $info_gsm_technologie->id_technologie = Mreq::tp('id');

$info_gsm_technologie->get_technologie();

//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_gsm_technologie->get_technologie())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_gsm_technologie->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 //Get id technologie station
 $id_technologie=Mreq::tp('id');
 $id_site=$info_gsm_technologie->Shw('id_site_gsm',1);
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
		<?php echo $id_technologie ?>
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
//
$form = new Mform('addgsm_secteur', 'addgsm_secteur','', 'gsm_secteur&'.MInit::crypt_tp('id',$id_technologie), '0');
$form->input_hidden('id_technologie',  $id_technologie);
$form->input_hidden('id_site',  $id_site);

$array_gsm_secteur = new Mgsm_secteur();
$arr_secteur = $array_gsm_secteur->get_secteur($id_technologie);
//$singl_arr = call_user_func_array('array_merge', $arr_secteur);
//var_dump($singl_arr);
//exit();

// num secteur
$secteur_array[]  = array('required', 'true', 'Séléctionner le secteur' );
/*$form->select_table('Secteur', 'num_secteur', 9, 'secteurs', 'id', 'libelle' , 'libelle', $indx = '*****' ,
	$selected=NULL,$multi=NULL, $where='etat=1 and (secteurs.id NOT IN(SELECT
                         `g`.`num_secteur`
                       FROM `gsm_secteur` `g`
                       WHERE (`g`.`id_technologie` ='.$id_technologie.')))', $secteur_array);*/

$form->select('Secteur', 'num_secteur', 6, $arr_secteur, $indx = '*****' ,$selected = NULL, $multi = NULL );

//HBA
$hba_array[]  = array('required', 'true', 'Insérer HBA' );
$hba_array[]  = array('number', 'true', 'Entrez un HBA Valid' );
$form->input('H.B.A', 'hba', 'text' ,6 , null, $hba_array);

//Azimut
$azimut_array[]  = array('required', 'true', 'Insérer Azimut ' );
$azimut_array[]  = array('number', 'true', 'Entrez un Azimut Valid' );
$form->input('Azimuth', 'azimuth', 'text' ,6 , null, $azimut_array);

//Model de l\'antenne
$mcc_array[]  = array('required', 'true', 'Insérer Tilt mécanique ' );
$mcc_array[]  = array('number', 'true', 'Entrez un Tilt mécanque Valid' );
$form->input('Tilt mécanique', 'tilt_mecanique', 'text' ,6 , null, $mcc_array);

//Tilt ELC
$elc_array[]  = array('required', 'true', 'Insérer Tilt ELC ' );
$elc_array[]  = array('number', 'true', 'Entrez un Tilt électrique  Valid' );
$form->input('Tilt électrique', 'tilt_electrique', 'text' ,6 , null, $elc_array);


//Button submit 
$form->button('Enregistrer secteur');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
