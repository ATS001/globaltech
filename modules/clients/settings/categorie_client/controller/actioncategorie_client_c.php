<ul class="dropdown-menu dropdown-menu-right">
<?php 


$categorie_client = new Mcategorie_client();
$categorie_client->id_categorie_client= Mreq::tp('id');
$categorie_client->get_categorie_client();



$action = new TableTools();
$action->line_data = $categorie_client->categorie_client_info;
$action->action_line_table('categorie_client', 'categorie_client',$categorie_client->categorie_client_info['creusr'],'deletecategorie_client');
?>

</ul>