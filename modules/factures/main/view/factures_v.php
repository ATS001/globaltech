<div class="page-header">
	<h1>
		<?php echo ACTIV_APP?>
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
					
						
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Factures" 
		</div>
		<div>
			<table id="fcts_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>						
						<th>
							ID
						</th>
						
						<th>
							Réference
						</th>
                                                <th>
							Totale
						</th>
                                                <th>
							Tva
						</th>
                                                <th>
							Client
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
	
	var table = $('#fcts_grid').DataTable({
		bProcessing: true,
		notifcol : 2,
		serverSide: true,
		ajax_url:"factures",
		
                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, 
                    {"sClass": "left","sWidth":"15%"},//
                    {"sClass": "left","sWidth":"15%"},//
                    {"sClass": "left","sWidth":"15%"},//
                    {"sClass": "left","sWidth":"15%"},//
                    {"sClass": "center","sWidth":"20%"},
                    {"sClass": "center","sWidth":"5%"},
                    ],
    });
     /*var column = table.column(0);
     column.visible( ! column.visible() );*/

    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#fcts_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('factures', table.cell($row, 0).data(), '.btn_action')
});

$('#fcts_grid tbody ').on('click', 'tr .this_del', function() {
	//alert($(this).attr("item"));
	stand_delet($(this),$(this).attr("table"),$(this).attr("item"))
});


});

</script>
