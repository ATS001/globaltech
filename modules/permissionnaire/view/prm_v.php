<div class="page-header">
	<h1>
		Gestion Permissionnaires
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
					
						<?php TableTools::btn_add('addprm','Ajouter Permissionnaire'); ?>
						<?php TableTools::btn_csv('prm','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('prm','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Permissionnaires" 
		</div>
		<div>
			<table id="prm_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Raison Sociale
						</th>
						<th>
							Catégorie
						</th>
						<th>
							Secteur d'activité
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
	
	var table = $('#prm_grid').DataTable({
		bProcessing: true,
		notifcol : 4,
		serverSide: true,
		
		ajax_url:"prm",
		//extra_data:"extra_data=1",
		//ajax:{},
		
                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, // Réf
                    {"sClass": "left","sWidth":"30%"}, // Raison Social
                    {"sClass": "left","sWidth":"25%"}, // ville
                    {"sClass": "left","sWidth":"25%"}, // personne à contacte
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

    $('#prm_grid').on('click', 'tr button', function() {
    	var $row = $(this).closest('tr')
    	//alert(table.cell($row, 0).data());
    	append_drop_menu('prm', table.cell($row, 0).data(), '.btn_action')
    });

});

</script>















































