<div class="page-header">
	<h1>
		Gestion des stations BLR
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container">
				<div class="btn-group btn-overlap">
					
						<?php TableTools::btn_add('addblr_stations','Ajouter une station'); ?>
						<?php TableTools::btn_csv('blr_stations','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('blr','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste des stations 
		</div>
		<div>
			<table id="blr_stations_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Permissionnaire
						</th>
                        <th>
							Nom du Site
						</th>

						  <th>
							Ville
						</th>
 
   						<th>
							Type
						</th>
						<th>
							Nbr Clients
						</th>
						<th>
							Etat
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
	
	var table = $('#blr_stations_grid').DataTable({
		bProcessing: true,
		notifcol : 6,
		serverSide: true,
		
		ajax_url:"blr_stations",		


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"},   //ID
                    {"sClass": "left","sWidth":"15%"},	  //Permissionnaire
                    {"sClass": "left","sWidth":"20%"},	  //Site
                    {"sClass": "left","sWidth":"15%"},	  //Ville
                    {"sClass": "left","sWidth":"10%"},	 //Type
                    {"sClass": "left","sWidth":"10%"},   //NBR clients
                    {"sClass": "center","sWidth":"15%"},  //Etat
                    {"sClass": "center","sWidth":"5%"},   //#
                    ],
    });

            
 
              
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#blr_stations_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('blr_stations', table.cell($row, 0).data(), '.btn_action')
});


});

</script>