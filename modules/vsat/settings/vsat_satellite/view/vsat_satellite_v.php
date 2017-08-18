<div class="page-header">
	<h1>
		Gestion des satellites
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
					
						<?php TableTools::btn_add('addvsat_satellite','Ajouter un satellite'); ?>
						<?php TableTools::btn_csv('vsat_satellite','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('vsat_satellite','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste des satellites
		</div>
		<div>
			<table id="vsat_satellite_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Satellite
						</th>
                        <th>
							Position Orbitale
						</th>

						  <th>
							Pays de l'opérateur
						</th>
 
   						<th>
							Fournisseur
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
	
	var table = $('#vsat_satellite_grid').DataTable({
		bProcessing: true,
		notifcol : 5,
		serverSide: true,
		
		ajax_url:"vsat_satellite",		


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"},   //ID
                    {"sClass": "left","sWidth":"20%"},	  //Satellite
                    {"sClass": "left","sWidth":"15%"},	  //Position
                    {"sClass": "left","sWidth":"15%"},	  //Pays
                    {"sClass": "left","sWidth":"25%"},	  //Fournisseur
                    {"sClass": "center","sWidth":"10%"},  //Etat
                    {"sClass": "center","sWidth":"5%"},   //#
                    ],
    });

            
 
              
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#vsat_satellite_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('vsat_satellite', table.cell($row, 0).data(), '.btn_action')
});


});

</script>