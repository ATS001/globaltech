<div class="page-header">
	<h1>
		Gestion des types éhéance
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
					
						<?php TableTools::btn_add('addtype_echeance','Ajouter Type Echéance'); ?>
						<?php TableTools::btn_csv('type_echeance','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('type_echeance','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Type Echéance" 
		</div>
		<div>
				<table id="type_echeance_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Type Echéance
						</th>
						<th>
							Statut
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
	
	var table = $('#type_echeance_grid').DataTable({
		bProcessing: true,
		notifcol : 2,
		serverSide: true,
		
		ajax_url:"type_echeance",
		


                aoColumns: [
                    {"sClass": "left","sWidth":"5%"}, //ID
                    {"sClass": "left","sWidth":"70%"},//type_echeance
                    {"sClass": "center","sWidth":"20%"},//Statut
                    {"sClass": "center","sWidth":"5%"},//Action
                    ],
    });

      
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#type_echeance_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('type_echeance', table.cell($row, 0).data(), '.btn_action')
});


});

</script>