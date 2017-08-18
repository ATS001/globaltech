<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
		<?php TableTools::btn_add('compte', 'Page de profil', Null, $exec = NULL, 'reply');   ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Historique de connexion
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
	

		<div class="table-header">
			Historique "Connexion" 
		</div>
		<div>
			<table id="history_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>						
						<th>
							Id
						</th>
						<th>
							Opération
						</th>
						<th>
							Utilisateur 
						</th>
						<th>
							Date d'opération
						</th>
						<th>
							Durée
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
	
	var table = $('#history_grid').DataTable({
		bProcessing: true,
		//notifcol : 3,
		serverSide: true,
		//Personnalisation des collonne et style d'ordre (asc or desc) for multiple columns order we should use [[3,'desc'],[colonne,'ordre']],
		
		order: [[3,'desc']],
		
		ajax_url:"history",
		extra_data: "id=<?php echo Mreq::tp('id');?>",
	


                aoColumns: [
                    {"sClass": "left","sWidth":"5%"}, 
                    {"sClass": "left","sWidth":"30%"},
                    {"sClass": "left","sWidth":"30%"}, 
                    {"sClass": "left","sWidth":"15%"},
                    {"sClass": "left","sWidth":"15%"}, 
                    {"sClass": "left","sWidth":"5%"}, 
                    ],
                });
      

$('#history_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('history', table.cell($row, 0).data(), '.btn_action')
});

$('#id_search').on('keyup', function () {
		table.column(0).search( $(this).val() )
		.draw();
		
});


});

</script>