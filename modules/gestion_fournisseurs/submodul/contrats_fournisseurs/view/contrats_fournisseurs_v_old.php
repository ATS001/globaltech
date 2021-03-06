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
					
						<?php TableTools::btn_add('addcontrat_frn','Ajouter un contrat'); ?>
						<?php TableTools::btn_csv('contrats_fournisseurs','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('contrats_fournisseurs','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Contrats Fournisseurs" 
		</div>
		<div>
			<table id="contrats_frn_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>						
						<th>
							ID
						</th>
						
						<th>
							Réference
						</th>
						<th>
							Fournisseur
						</th>
                        
						<th>
							Date d'effet
						</th>
						<th>
							Date fin
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
	
	var table = $('#contrats_frn_grid').DataTable({
		bProcessing: true,
		notifcol : 5,
		serverSide: true,
		ajax_url:"contrats_fournisseurs",
		
                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, 
                    {"sClass": "left","sWidth":"20%"},//
                    {"sClass": "left","sWidth":"25%"},
                    {"sClass": "center","sWidth":"15%"},
                    {"sClass": "center","sWidth":"15%"},
                    {"sClass": "center","sWidth":"10%"},
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

$('#contrats_frn_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('contrats_fournisseurs', table.cell($row, 0).data(), '.btn_action')
});

$('#contrats_frn_grid tbody ').on('click', 'tr .this_del', function() {
	//alert($(this).attr("item"));
	stand_delet($(this),$(this).attr("table"),$(this).attr("item"))
});


});

</script>
