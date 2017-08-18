<div class="page-header">
	<h1>
		Gestion des hubs
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
					
						<?php TableTools::btn_add('addvsat_hub','Ajouter un Hub'); ?>
						<?php TableTools::btn_csv('vsat_hub','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('vsat_hub','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste des Hubs
		</div>
		<div>
			<table id="vsat_hub_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Opérateur
						</th>
                        <th>
							Pays
						</th>

						  <th>
							Ville
						</th>
 
   						<th>
							E-mail
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
	
	var table = $('#vsat_hub_grid').DataTable({
		bProcessing: true,
		notifcol : 5,
		serverSide: true,
		
		ajax_url:"vsat_hub",		


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"},   //ID
                    {"sClass": "left","sWidth":"20%"},	  //Satellite
                    {"sClass": "left","sWidth":"15%"},	  //Position
                    {"sClass": "left","sWidth":"15%"},	  //Pays
                    {"sClass": "left","sWidth":"25%"},	  //Fournisseur
                    {"sClass": "center","sWidth":"10%"},  //Etat
                    {"sClass": "center","sWidth":"5%"},   //#
                    ],
    });

            
 
              
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#vsat_hub_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('vsat_hub', table.cell($row, 0).data(), '.btn_action')
});


});

</script>