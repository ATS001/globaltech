<?php
//Get all gsm_Station info
 $info_gsm= new Mgsm();
//Set ID of Module with POST id
 $info_gsm->id_gsm = Mreq::tp('id');

 //Get all gsm_technologie info
 $gsm_technologie_info= new Mgsm_technologie();
//Get the result of the function check_technologie (true or false)
 $result=$gsm_technologie_info->check_technologie($info_gsm->id_gsm);

//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_gsm->get_gsm())
 { 	
 	//returne message error red to client 
 	exit('3#'.$info_gsm->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 
?> 

<div class="page-header">
	<h1>
		Gestion Technologie GSM
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<div class="pull-right tableTools-container">
			<?php TableTools::btn_add('gsm','Liste Stations GSM', Null, NULL, 'reply');?>
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
						TableTools::btn_add('addgsm_technologie','Ajouter Technologie GSM',MInit::crypt_tp('id',Mreq::tp('id')));
						} 
						?>
						<?php TableTools::btn_csv('gsm_technologie','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('gsm_technologie','Exporter Liste'); ?>
					
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Technologie GSM" 
			<?php echo ' ('.$info_gsm->Shw('nom_station',1).' -'.$info_gsm->id_gsm.'-)';?>
		</div>
		<div>
			<table id="gsm_technologie_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Technologie
						</th>
						<th>
							Marque BTS
						</th>
						<th>
							Modele antenne
						</th>
						<th>
							Nombre radios
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
	
	var table = $('#gsm_technologie_grid').DataTable({
		bProcessing: true,
		//notifcol : 5,
		serverSide: true,
		
		ajax_url:"gsm_technologie",
		extra_data : "id=<?php echo Mreq::tp('id');?>",
		//ajax:{},
		// search_extra1 : [
		//    {id:'id_search',val:'ID: <input value="" id="id_search" class="form-control input-sm" type="search" placeholder="ID Permissionnaire" />'},
		//    /*{id:'an',val:'Ann: <select id="an"><option value="2015">2015</option><option value="2016">2016</option></select>'},*/
		// ],


		aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, // Identifiant 
                    {"sClass": "left","sWidth":"22,5%"}, // Technologie
                    {"sClass": "left","sWidth":"22,5%"}, // Marque BTS
                    {"sClass": "left","sWidth":"22,5%"}, // Modele antenne     
                    {"sClass": "left","sWidth":"22,5%"}, // Nombre radios
                    {"sClass": "center","sWidth":"5%"}, // Action
                    ],
                });

	
	
	
	
	
	
	$('.export_csv').on('click', function() {
		csv_export(table, 'csv');
	});
	$('.export_pdf').on('click', function() {
		csv_export(table, 'pdf');
	});

	$('#gsm_technologie_grid').on('click', 'tr button', function() {
		var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('gsm_technologie', table.cell($row, 0).data(), '.btn_action')
});

	$('#id_search').on('keyup', function () {
		table.column(0).search( $(this).val() )
		.draw();
		
	});



});

</script>