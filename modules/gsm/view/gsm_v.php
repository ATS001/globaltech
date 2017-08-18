<div class="page-header">
	<h1>
		Gestion G.S.M
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
					
						<?php TableTools::btn_add('addgsm','Ajouter Station GSM'); ?>
						<?php TableTools::btn_csv('gsm','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('gsm','Exporter Liste'); ?>
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Station GSM" 
		</div>
		<div>
			<table id="gsm_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Opérateur
						</th>
						<th>
							Nom Station	
						</th>
						<th>
							Technologie
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

		var table = $('#gsm_grid').DataTable({
			bProcessing: true,
			notifcol : 4,
			serverSide: true,

			ajax_url:"gsm",
		//extra_data:"extra_data=1",
		//ajax:{},
		aoColumns: [
	        {"sClass": "center","sWidth":"3%"}, //
	        {"sClass": "left","sWidth":"15%"},
	        {"sClass": "left","sWidth":"25%"},
	        {"sClass": "left","sWidth":"20%"},
	        {"sClass": "left","sWidth":"17%"},
	        {"sClass": "center","sWidth":"15%"},
	        {"sClass": "center","sWidth":"5%"},
	        ],
	    });






		$('.export_csv').on('click', function() {
			csv_export(table, 'csv');
		});
		$('.export_pdf').on('click', function() {
			csv_export(table, 'pdf');
		});

		$('#gsm_grid').on('click', 'tr button', function() {
			var $row = $(this).closest('tr')
	        //alert(table.cell($row, 0).data());
	        append_drop_menu('gsm', table.cell($row, 0).data(), '.btn_action')
        });




});

</script>















































