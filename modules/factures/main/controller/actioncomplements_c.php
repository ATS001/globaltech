<ul class="dropdown-menu dropdown-menu-right">
<?php 

$complement = new Mfacture();
$complement->id_complement= Mreq::tp('id');
$facture->get_complement();



$action = new TableTools();
$action->line_data = $complement->complement_info;

//$action->action_line_table('factures', 'factures',$facture->facture_info['creusr'],'deletefacture');
?>

</ul>