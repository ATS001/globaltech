<div class="page-header">
	<h1>
		Gestion types de produits
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
			
						<?php TableTools::btn_add('addtype_produit','Ajouter Type'); ?>
						<?php TableTools::btn_csv('types_produits','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('types_produits','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Types de produits" 
		</div>
		<div>
			<table id="types_produits_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
					    <th>
							Type
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
	
	var table = $('#types_produits_grid').DataTable({
		bProcessing: true,
		notifcol : 2,
		serverSide: true,
		
		ajax_url:"types_produits",
		
                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, // Identifiant 
                    {"sClass": "left","sWidth":"30%"}, // Type produit
                    {"sClass": "center","sWidth":"10%"}, // Statut
                    {"sClass": "center","sWidth":"5%"}, // Action
                    ],
                });

            
 
            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#types_produits_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	append_drop_menu('type_produits', table.cell($row, 0).data(), '.btn_action')
});

$('#id_search').on('keyup', function () {
            	table.column(0).search( $(this).val() )
                     .draw();
            
});



});

</script>