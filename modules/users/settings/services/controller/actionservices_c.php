<ul class="dropdown-menu dropdown-menu-right">
<?php 


$service = new Mservice();
$service->id_service = Mreq::tp('id');
$service->get_service();


$action = new TableTools();
$action->line_data = $service->service_info;
$action->action_line_table('services', 'services');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="user" data="id=%id%" rel="edituser" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Action suplmentaire
		</a>
</li> -->
</ul>
<?php 
//ARCEP PORTAIL CAPTIVE MANAGER
// Modul: services => Controller Action