<ul class="dropdown-menu dropdown-menu-right">
<?php 


$service = new Mservice();
$service->id_service = Mreq::tp('id');
$service->get_service();


$action = new TableTools();
$action->line_data = $service->service_info;

$action->action_line_table('services', 'services', $service->service_info['creusr'], 'deleteservices')
?>

</ul>
