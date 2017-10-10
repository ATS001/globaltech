<ul class="dropdown-menu dropdown-menu-right">
<?php 


$proforma = new Mproforma();
$proforma->id_proforma = Mreq::tp('id');
$proforma->get_proforma();



$action = new TableTools();
$action->line_data = $proforma->proforma_info;
$action->action_line_table('proforma', 'proforma', $proforma->proforma_info['creusr'], 'deleteproforma');

?>
</ul>