<ul class="dropdown-menu dropdown-menu-right">
<?php 


$vsat_satellite = new Mvsat_satellite();
$vsat_satellite->id_vsat_satellite = Mreq::tp('id');
$vsat_satellite->get_vsat_satellite();


$action = new TableTools();
$action->line_data = $vsat_satellite->vsat_satellite_info;
$action->action_line_table('vsat_satellite', 'vsat_satellite', 'deletevsat_satellite');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="user" data="id=%id%" rel="edituser" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Action suplmentaire
		</a>
</li> -->
</ul>
