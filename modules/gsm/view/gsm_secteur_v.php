<?php
//Get all technologie info
 $gsm_technologie_info= new Mgsm_technologie();
//Set ID of Module with POST id
 $gsm_technologie_info->id_technologie = Mreq::tp('id');
 $gsm_technologie_info->get_technologie();
//Get the result of the function check_nbr_secteur (true or false)
 $result=$gsm_technologie_info->check_nbr_secteur();

//Check if Post ID <==> Post idc or get_secteur return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$gsm_technologie_info->get_technologie())
 { 	
 	//returne message error red to client 
 	exit('3#'.$gsm_technologie_info->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
  //Get id gsm station
 $id_technologie=$gsm_technologie_info->Shw('id_site_gsm',1);
?>

<div class="page-header">
	<h1>
		Gestion des secteurs
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<div class="pull-right tableTools-container">
			<?php TableTools::btn_add('gsm_technologie','Liste Technologies GSM', MInit::crypt_tp('id',$id_technologie), NULL, 'reply');?>
		</div>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container">
				<div class="btn-group btn-overlap">
					
						<?php 
						if($result) 
						{
						TableTools::btn_add('addgsm_secteur','Ajouter un secteur',MInit::crypt_tp('id',Mreq::tp('id'))); 
						} 
						?>
						<?php TableTools::btn_csv('gsm_secteur','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('secteurs','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste des secteurs		
			<?php echo ' ('.$gsm_technologie_info->Shw('libelle',1).' -'.$gsm_technologie_info->id_technologie.'-)';?>
		</div>
		<div>
			<table id="gsm_secteur_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							N° Secteur
						</th>
                        <th>
							HBA
						</th>

						  <th>
							Azimuth
						</th>
 
   						<th>
							Tilt mécanique
						</th>
                        <th>
							Tilt électrique
						</th>						
						<th>
							#
						</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">


$(document).ready(function() {
	
	var table = $('#gsm_secteur_grid').DataTable({
		bProcessing: true,
		notifcol : 6,
		serverSide: true,
		
		ajax_url:"gsm_secteur",
		extra_data : "id=<?php echo Mreq::tp('id');?>",


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"},   //ID
                    {"sClass": "left","sWidth":"10%"},	  //N Secteur
                    {"sClass": "left","sWidth":"15%"},	  //HBA
                    {"sClass": "left","sWidth":"15%"},	  //Azimuth
                    {"sClass": "left","sWidth":"15%"},	  //Tilt mꤡnique
                    {"sClass": "center","sWidth":"15%"},  //Tilt ꭥctrique
                    {"sClass": "center","sWidth":"5%"},   //#
                    ],
    });

            
 
              
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#gsm_secteur_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('gsm_secteur', table.cell($row, 0).data(), '.btn_action')
});


});

</script>