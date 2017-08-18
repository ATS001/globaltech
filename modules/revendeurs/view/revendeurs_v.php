<div class="page-header">
	<h1>
		Gestion Revendeurs
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
					
						<?php TableTools::btn_add('addrevendeur','Ajouter Revendeur'); ?>
						<?php TableTools::btn_csv('revendeurs','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('revendeurs','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Revendeurs" 
		</div>
		<div>
			<table id="revendeurs_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
					    <th>
							Raison Sociale
						</th>
							<th>
							Secteur d'activité
						</th>
						<th>
							Ville
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
	
	var table = $('#revendeurs_grid').DataTable({
		bProcessing: true,
		notifcol : 4,
		serverSide: true,
		
		ajax_url:"revendeurs",
		


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, // Identifiant 
                    {"sClass": "left","sWidth":"40%"}, // Dénomination
                    {"sClass": "left","sWidth":"20%"}, // Secteur d'activité
                    {"sClass": "left","sWidth":"20%"}, // Ville
                    {"sClass": "center","sWidth":"10%"}, // Statut
                    {"sClass": "center","sWidth":"5%"}, // Action
                    ],
                });

            
 
            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#revendeurs_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('revendeurs', table.cell($row, 0).data(), '.btn_action')
});

$('#id_search').on('keyup', function () {
            	table.column(0).search( $(this).val() )
                     .draw();
            
});



});

</script>