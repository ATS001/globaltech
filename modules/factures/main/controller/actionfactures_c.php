<ul class="dropdown-menu dropdown-menu-right">
<?php 

$facture = new Mfacture();
$facture->id_facture= Mreq::tp('id');
$facture->get_facture();


$action = new TableTools();
$action->line_data = $facture->facture_info;
$etat_archive = Msetting::get_set('archive_factures');
$action->action_line_table('factures', 'factures',$facture->facture_info['creusr'],NULL,$etat_archive);

?>

</ul>