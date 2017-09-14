<<<<<<< HEAD
<div class="page-header">
	<h1>
		Gestion des produits
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
=======
	<div class="page-header">
		<h1>
			Gestion des produits
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
			</small>
		</h1>
	</div><!-- /.page-header -->
>>>>>>> refs/remotes/origin/ayoub

	<div class="row">
		<div class="col-xs-12">
			<div class="clearfix">
				<div class="pull-right tableTools-container">
					<div class="btn-group btn-overlap">
				
							<?php TableTools::btn_add('addproduit','Ajouter Produit'); ?>
							<?php TableTools::btn_csv('produits','Exporter Liste'); ?>
							<?php TableTools::btn_pdf('produits','Exporter Liste'); ?>
						
				    </div>
				</div>
			</div>

<<<<<<< HEAD
		<div class="table-header">
			Liste "produits" 
		</div>
		<div>
			<table id="produits_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
                                                <th>
							Référence
						</th>
                                                <th>
							Désignation
						</th>                                              
                                               
                                                <th>
							Stock minimale
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
=======
			<div class="table-header">
				Liste "produits" 
			</div>
			<div>
				<table id="produits_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
					<thead>
						<tr>
							
							<th>
								ID
							</th>
	                                                <th>
								Référence
							</th>
	                                                <th>
								Désignation
							</th>                                              
	                                               
	                                                <th>
								Stock minimale
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
>>>>>>> refs/remotes/origin/ayoub
		</div>
	</div>
	<script type="text/javascript">


<<<<<<< HEAD
$(document).ready(function() {
	
	var table = $('#produits_grid').DataTable({
		bProcessing: true,
		notifcol : 4,
		serverSide: true,
		
		ajax_url:"produits",
		
                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, // Identifiant 
                    {"sClass": "left","sWidth":"20%"}, // Catégorie
                    {"sClass": "left","sWidth":"25%"},   
                    {"sClass": "left","sWidth":"10%"},
                    {"sClass": "center","sWidth":"10%"}, // Statut
                    {"sClass": "center","sWidth":"10%"}, // Action
                    ],
                });
=======
	$(document).ready(function() {
		
		var table = $('#produits_grid').DataTable({
			bProcessing: true,
			notifcol : 4,
			serverSide: true,
			
			ajax_url:"produits",
			
	                aoColumns: [
	                    {"sClass": "center","sWidth":"5%"}, // Identifiant 
	                    {"sClass": "left","sWidth":"20%"}, // Catégorie
	                    {"sClass": "left","sWidth":"25%"},   
	                    {"sClass": "left","sWidth":"10%"},
	                    {"sClass": "center","sWidth":"10%"}, // Statut
	                    {"sClass": "center","sWidth":"10%"}, // Action
	                    ],
	                });
>>>>>>> refs/remotes/origin/ayoub

	            
	 
	            
	           
	        
	    
	$('.export_csv').on('click', function() {
		csv_export(table, 'csv');
	});
	$('.export_pdf').on('click', function() {
		csv_export(table, 'pdf');
	});

	$('#produits_grid').on('click', 'tr button', function() {
		var $row = $(this).closest('tr')
		append_drop_menu('produits', table.cell($row, 0).data(), '.btn_action')
	});

	$('#id_search').on('keyup', function () {
	            	table.column(0).search( $(this).val() )
	                     .draw();
	            
	});



	});

	</script>