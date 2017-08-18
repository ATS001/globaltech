<div class="page-header">
	<h1>
		Gestion des services
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
					
						<?php TableTools::btn_add('addservice','Ajouter un service'); ?>
						<?php TableTools::btn_csv('listservices','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('listservices','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste Services 
		</div>
		<div>
			<table id="services_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Service
						</th>
						<th>
							Nbr membres
						</th>
						<th>
							Signature
						</th>
												
                        <th>
							etat
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
	
	var table = $('#services_grid').DataTable({
		bProcessing: true,
		notifcol : 2,
		serverSide: true,
		
		ajax_url:"services",
		


                aoColumns: [
                    {"sClass": "left","sWidth":"5%"}, 
                    {"sClass": "left","sWidth":"55%"},
                    {"sClass": "center","sWidth":"15%"},
                    {"sClass": "center","sWidth":"10%"},
                    {"sClass": "left","sWidth":"10%"},
                    {"sClass": "center","sWidth":"5%"},
                    ],
    });

            
 
            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#services_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('services', table.cell($row, 0).data(), '.btn_action')
});





});

</script>















































<?php 
//ARCEP PORTAIL CAPTIVE MANAGER
// Modul: services => View<?php 
//ARCEP PORTAIL CAPTIVE MANAGER
// Modul: services => View