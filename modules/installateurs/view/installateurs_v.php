<div class="page-header">
	<h1>
		Gestion Installateurs
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
					
						<?php TableTools::btn_add('addinstallateur_ph','Ajouter Personne Physique'); ?>
						<?php TableTools::btn_add('addinstallateur_m','Ajouter Personne Morale'); ?>
						<?php TableTools::btn_csv('installateurs','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('installateurs','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Installateurs" 
		</div>
		<div>
			<table id="installateurs_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
					    <th>
							Dénomination
						</th>
							<th>
							Type d'istallateur
						</th>
						<th>
							Ville
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
	
	var table = $('#installateurs_grid').DataTable({
		bProcessing: true,
		notifcol : 4,
		serverSide: true,
		
		ajax_url:"installateurs",
		//extra_data:"extra_data=1",
		//ajax:{},
		// search_extra1 : [
		//    {id:'id_search',val:'ID: <input value="" id="id_search" class="form-control input-sm" type="search" placeholder="ID Permissionnaire" />'},
		//    /*{id:'an',val:'Ann: <select id="an"><option value="2015">2015</option><option value="2016">2016</option></select>'},*/
		// ],


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"}, // Identifiant 
                    {"sClass": "left","sWidth":"40%"}, // Dénomination
                    {"sClass": "left","sWidth":"20%"}, // Type installateur
                    {"sClass": "left","sWidth":"20%"}, // Ville
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

$('#installateurs_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('installateurs', table.cell($row, 0).data(), '.btn_action')
});

$('#id_search').on('keyup', function () {
            	table.column(0).search( $(this).val() )
                     .draw();
            
});



});

</script>