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
					
						<?php TableTools::btn_add('addproforma','Ajouter un proforma'); ?>
						<?php TableTools::btn_csv('proforma','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('proforma','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "proforma" 
		</div>
		<div>
			<table id="proforma_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>						
						<th>
							ID
						</th>
						<th>
							Date
						</th>
						<th>
							Réf
						</th>
						<th>
							Client
						</th>
						<th>
							Montant H.T
						</th>
						<th>
							Montant T.T.C
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
	
	var table = $('#proforma_grid').DataTable({
		bProcessing: true,
		notifcol : 6,
		serverSide: true,
		ajax_url:"proforma",
		//extra_data:"extra_data=1",
		//ajax:{},
		/*search_extra1 : [
		   {id:'service',val:'Etat: <input id="service" class="form-control input-sm" type="search" placeholder="Etat" />'},
		   {id:'etat',val:'Ann: <select id="etat"><option value="0">Inactif</option><option value="1">Active</option></select>'},
		],*/


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, 
                    {"sClass": "left","sWidth":"10%"},//
                    {"sClass": "left","sWidth":"10%"},
                    {"sClass": "left","sWidth":"35%"},
                    {"sClass": "alignRight","sWidth":"10%"},
                    {"sClass": "alignRight","sWidth":"10%"},
                    {"sClass": "left","sWidth":"15%"},
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

$('#proforma_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('proforma', table.cell($row, 0).data(), '.btn_action')
});

$('#proforma_grid tbody ').on('click', 'tr .this_del', function() {
	//alert($(this).attr("item"));
	stand_delet($(this),$(this).attr("table"),$(this).attr("item"))
});


});

</script>















































