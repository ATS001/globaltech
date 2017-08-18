<ul class="dropdown-menu dropdown-menu-right">
<?php 


$region = new Mregion();
$region->id_region= Mreq::tp('id');
$region->get_region();



$action = new TableTools();
$action->line_data = $region->region_info;
$action->action_line_table('regions', 'ref_region');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="user" data="id=%id%" rel="edituser" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Action suplmentaire
		</a>
</li> -->
</ul>
