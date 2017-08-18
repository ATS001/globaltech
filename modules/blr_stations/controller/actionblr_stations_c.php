<ul class="dropdown-menu dropdown-menu-right">
<?php 
$blr_stations= new Mblr_stations();//New instance for blr stations
$blr_stations->id_blr_stations = Mreq::tp('id'); //Set ID of blr_station POST AJAX
$blr_stations->get_blr_stations(); //Get All ino of blr stations

$action = new TableTools(); //New instance for table tools
$action->line_data = $blr_stations->blr_stations_info;//Set data for this line
$action->action_line_table('blr_stations', 'blr_stations', $blr_stations->blr_stations_info['creusr'], 'deleteblr_stations');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="blr_stations" data="id=%id%" rel="addblr_clients" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Ajouter Client
		</a>
</li> -->
</ul>
