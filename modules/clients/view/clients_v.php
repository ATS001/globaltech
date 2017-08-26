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
							Code 
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
	        {"sClass": "center","sWidth":"10%"},
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

		$('.export_map').on('click', function() {
			$('body').fullScreen(true);
			var data  = table.ajax.params();

			var sUrl  = table.ajax.url()
			bootbox.process({
				message:'Working',
			});

			$.ajax({
				url: sUrl+'&lst=1&export=1&format=dat',
				type: 'POST',
				data: data,
				dataType: 'html',
				success: function(data) {


					var data_arry = data.split("#");
					if(data_arry[0] == 1) {
						bootbox.hideAll();
						var $data = data_arry[1];
                        $('body').fullScreen(true);
                        setTimeout(function() { $.colorbox({iframe:true, map:true, width:"100%", height:"100%",href:"./map/?mult="+$data }) },500)

					}else{
						ajax_loadmessage(data_arry[1],'nok',5000);
						bootbox.hideAll();
					}



				}
			});
		});

		$('#clients_grid').on('click', 'tr button', function() {
			var $row = $(this).closest('tr')
	        //alert(table.cell($row, 0).data());
	        append_drop_menu('clients', table.cell($row, 0).data(), '.btn_action')
	    });




});

</script>