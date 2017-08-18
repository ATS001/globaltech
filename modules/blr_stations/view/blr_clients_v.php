<?php 
$info_blr_stations = new Mblr_stations();
$info_blr_stations->id_blr_stations = Mreq::tp('id');
$info_blr_stations->get_blr_stations();
$result=$info_blr_stations->check_nbr_clients();

if(!MInit::crypt_tp('id', null, 'D')  or !$info_blr_stations->get_blr_stations())
{ 	

	exit('0#'.$info_blr_stations->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
 
}

$id_blr_stations   = Mreq::tp('id');
$id_blr_stations_c = MInit::crypt_tp('id',$id_blr_stations); 
?> 
 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
	<?php TableTools::btn_add('blr_stations', 'Liste des stations', Null, $exec = NULL, 'reply'); ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		Gestion des clients BLR 
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo $info_blr_stations->Shw('site',1).' - '.$id_blr_stations.' - ' ; ?>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container">
				<div class="btn-group btn-overlap">
					
					  
                               		
                                <?php 
                                if($result) 
                                    { TableTools::btn_add('addblr_clients','Ajouter blr client',MInit::crypt_tp('id',Mreq::tp('id')));} 
                                ?>     
                                    	
				<?php TableTools::btn_csv('blr_clients','Exporter Liste'); ?>
				<?php TableTools::btn_pdf('blr_clients','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste des clients
		</div>
		<div>
			<table id="blr_clients_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Site
						</th>
						<th>
							Longitude
						</th>
                        <th>
							Latitude
						</th>

						  <th>
							Hauteur
						</th>
 
   						<th>
							Marque
						</th>
						<th>
							Modèle
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
	
	var table = $('#blr_clients_grid').DataTable({
		bProcessing: true,
		notifcol : 7,
		serverSide: true,
		
		ajax_url:"blr_clients",	
                extra_data : "id=<?php echo Mreq::tp('id');?>",
                


                aoColumns: [
                    {"sClass": "center","sWidth":"5%"},   
                    {"sClass": "left","sWidth":"15%"},  
                    {"sClass": "left","sWidth":"13%"},	
                    {"sClass": "left","sWidth":"13%"},	
                    {"sClass": "left","sWidth":"12%"},	
                    {"sClass": "left","sWidth":"15%"},	
                    {"sClass": "left","sWidth":"10%"},  
                    {"sClass": "center","sWidth":"10%"},   
                    {"sClass": "center","sWidth":"10%"},   
                    ],
    });

            
 
              
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#blr_clients_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('blr_clients', table.cell($row, 0).data(), '.btn_action')
});


});

</script>