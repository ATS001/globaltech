<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
		<?php TableTools::btn_add('compte', 'Page de profil', Null, $exec = NULL, 'reply');   ?>			
	</div>
</div>
<div class="page-header">
	<h1>
		Liste des activités
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
	

		<div class="table-header">
			Liste "Activités" 
		</div>
		<div>
			<table id="activities_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
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
	
	var table = $('#activities_grid').DataTable({

		bProcessing: true,
		//notifcol : 3,
		serverSide: true,
		//Personnalisation des collonne et style d'ordre (asc or desc) for multiple columns order we should use [[3,'desc'],[colonne,'ordre']],
		
		order: [[3,'desc']],
		
		ajax_url:"activities",
	


                aoColumns: [
                    {"sClass": "left","sWidth":"5%"}, 
                    {"sClass": "left","sWidth":"40%"},
                    {"sClass": "left","sWidth":"30%"}, 
                    {"sClass": "left","sWidth":"20%"}, 
                    {"sClass": "left","sWidth":"5%"}, 
                    ],
                });
      

$('#activities_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('activities', table.cell($row, 0).data(), '.btn_action')
});

$('#id_search').on('keyup', function () {
		table.column(0).search( $(this).val() )
		.draw();
		
});


});

</script>