<ul class="dropdown-menu dropdown-menu-right">
<?php 
$uhf_vhf_clients = new Muhf_vhf_clients();//New instance for blr clients
$uhf_vhf_clients->id_uhf_vhf_clients = Mreq::tp('id'); //Set ID of blr_station POST AJAX
$uhf_vhf_clients->get_uhf_vhf_clients(); //Get All ino of blr clients

$action = new TableTools(); //New instance for table tools
$action->line_data = $uhf_vhf_clients->uhf_vhf_clients_info;//Set data for this line
$action->action_line_table('uhf_vhf_clients', 'uhf_vhf_clients', $uhf_vhf_clients->uhf_vhf_clients_info['creusr'], 'deleteuhf_vhf_clients');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="uhf_vhf_clients" data="id=%id%" rel="adduhf_vhf_clients" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Ajouter Client
		</a>
</li> -->
</ul>
