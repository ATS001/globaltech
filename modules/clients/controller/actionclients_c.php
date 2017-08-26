<ul class="dropdown-menu dropdown-menu-right">
<?php 


$client = new Mclients();
$client->id_client= Mreq::tp('id');
$client->get_client();



$action = new TableTools();
$action->line_data = $client->client_info;
$action->action_line_table('clients', 'clients',$client->client_info['creusr'],'deleteclient');
?>

</ul>