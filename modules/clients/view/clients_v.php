<div class="page-header">
	<h1>
		Gestion Clients
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
					
						<?php TableTools::btn_add('addclient','Ajouter Client'); ?>
						<?php TableTools::btn_csv('clients','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('clients','Exporter Liste'); ?>

			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Clients" 
		</div>
		<div>
			<table id="clients_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Référence 
						</th>
						<th>
							Dénomination
						</th>
						<th>
							Raison Sociale
						</th>
						<th>
							Catégorie Client
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

		var table = $('#clients_grid').DataTable({
			bProcessing: true,
			notifcol : 5,
			serverSide: true,

			ajax_url:"clients",
		//extra_data:"extra_data=1",
		//ajax:{},
		aoColumns: [
	        {"sClass": "center","sWidth":"5%"}, //
	        {"sClass": "left","sWidth":"10%"},
	        {"sClass": "left","sWidth":"20%"}, //
	        {"sClass": "left","sWidth":"20%"},
	        {"sClass": "center","sWidth":"20%"},
	        {"sClass": "center","sWidth":"20%"},
	        {"sClass": "center","sWidth":"5%"},
	        ],
	    });


		$('.export_csv').on('click', function() {
			csv_export(table, 'csv');
		});
		$('.export_pdf').on('click', function() {
			csv_export(table, 'pdf');
		});

		
		$('#clients_grid').on('click', 'tr button', function() {
			var $row = $(this).closest('tr')
	        //alert(table.cell($row, 0).data());
	        append_drop_menu('clients', table.cell($row, 0).data(), '.btn_action')
	    });




});

</script>