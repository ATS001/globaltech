<ul class="dropdown-menu dropdown-menu-right">
<?php 
$blr_clients = new Mblr_clients();//New instance for blr clients
$blr_clients->id_blr_clients = Mreq::tp('id'); //Set ID of blr_station POST AJAX
$blr_clients->get_blr_clients(); //Get All ino of blr clients

$action = new TableTools(); //New instance for table tools
$action->line_data = $blr_clients->blr_clients_info;//Set data for this line
$action->action_line_table('blr_clients', 'blr_clients', $blr_clients->blr_clients_info['creusr'], 'deleteblr_clients');

?>
<!-- <li>
	    <a href="#" class="this_url" redi="blr_clients" data="id=%id%" rel="addblr_clients" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Ajouter Client
		</a>
</li> -->
</ul>
