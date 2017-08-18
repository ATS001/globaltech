<div class="page-header">
	<h1>
		Gestion VSAT
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
					
						<?php TableTools::btn_add('addvsat','Ajouter Station VSAT'); ?>
						<?php TableTools::btn_csv('vsat','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('vsat','Exporter Liste'); ?>
						<?php TableTools::btn_map('vsat','Exporter MAP'); ?>
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Station VSAT" 
		</div>
		<div>
			<table id="vsat_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Nom Station	
						</th>
						<th>
							Permissionnaire
						</th>
						<th>
							Utilisation
						</th>
						<th>
							Dernière visite
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

		var table = $('#vsat_grid').DataTable({
			bProcessing: true,
			notifcol : 5,
			serverSide: true,

			ajax_url:"vsat",
		//extra_data:"extra_data=1",
		//ajax:{},
		aoColumns: [
	        {"sClass": "center","sWidth":"3%"}, //
	        {"sClass": "left","sWidth":"25%"},
	        {"sClass": "left","sWidth":"30%"},
	        {"sClass": "left","sWidth":"15%"},
	        {"sClass": "center","sWidth":"12%"},
	        {"sClass": "center","sWidth":"10%"},
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

		$('#vsat_grid').on('click', 'tr button', function() {
			var $row = $(this).closest('tr')
	        //alert(table.cell($row, 0).data());
	        append_drop_menu('vsat', table.cell($row, 0).data(), '.btn_action')
	    });




});

</script>















































