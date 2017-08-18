<ul class="dropdown-menu dropdown-menu-right">
<?php 


$vsat_hub = new Mvsat_hub();
$vsat_hub->id_vsat_hub = Mreq::tp('id');
$vsat_hub->get_vsat_hub();


$action = new TableTools();
$action->line_data = $vsat_hub->vsat_hub_info;
$action->action_line_table('vsat_hub', 'vsat_hub', $vsat_hub->vsat_hub_info['creusr'], 'deletevsat_hub');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="user" data="id=%id%" rel="edituser" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Action suplmentaire
		</a>
</li> -->
</ul>
