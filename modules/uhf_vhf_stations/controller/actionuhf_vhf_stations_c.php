<ul class="dropdown-menu dropdown-menu-right">
<?php 
$uhf_vhf_stations= new Muhf_vhf_stations();//New instance for blr stations
$uhf_vhf_stations->id_uhf_vhf_stations = Mreq::tp('id'); //Set ID of blr_station POST AJAX
$uhf_vhf_stations->get_uhf_vhf_stations(); //Get All ino of blr stations

$action = new TableTools(); //New instance for table tools
$action->line_data = $uhf_vhf_stations->uhf_vhf_stations_info;//Set data for this line
$action->action_line_table('uhf_vhf_stations', 'uhf_vhf_stations', $uhf_vhf_stations->uhf_vhf_stations_info['creusr'], 'deleteuhf_vhf_stations');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="uhf_vhf_stations" data="id=%id%" rel="addblr_clients" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Ajouter Client
		</a>
</li> -->
</ul>
