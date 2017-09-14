<ul class="dropdown-menu dropdown-menu-right">
<?php 


$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');
$devis->get_devis();



$action = new TableTools();
$action->line_data = $devis->devis_info;
$action->action_line_table('devis', 'devis', $devis->devis_info['creusr'], 'deletedevis');

?>
</ul>