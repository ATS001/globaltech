<div class="page-header">
	<h1>
		Gestion Fournisseurs
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
					
						<?php TableTools::btn_add('addfournisseur','Ajouter Fournisseur'); ?>
						<?php TableTools::btn_csv('fournisseurs','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('fournisseurs','Exporter Liste'); ?>

			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Fournisseurs" 
		</div>
		<div>
			<table id="fournisseurs_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
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
							Pays
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

		var table = $('#fournisseurs_grid').DataTable({
			bProcessing: true,
			notifcol : 5,
			serverSide: true,

			ajax_url:"fournisseurs",
		//extra_data:"extra_data=1",
		//ajax:{},
		aoColumns: [
	        {"sClass": "center","sWidth":"5%"}, //
	        {"sClass": "left","sWidth":"10%"},
	        {"sClass": "left","sWidth":"20%"}, //
	        {"sClass": "left","sWidth":"20%"},
	        {"sClass": "left","sWidth":"20%"},
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

		
		$('#fournisseurs_grid').on('click', 'tr button', function() {
			var $row = $(this).closest('tr')
	        //alert(table.cell($row, 0).data());
	        append_drop_menu('fournisseurs', table.cell($row, 0).data(), '.btn_action')
	    });




});

</script>