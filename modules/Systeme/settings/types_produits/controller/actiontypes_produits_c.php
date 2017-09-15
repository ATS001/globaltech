<ul class="dropdown-menu dropdown-menu-right">
<?php 

$type_produit = new Mtype_produit();
$type_produit->id_type_produit= Mreq::tp('id');
$type_produit->get_type_produit();


$action = new TableTools();
$action->line_data = $type_produit->type_produit_info;
$action->action_line_table('Systeme/settings/types_produits', 'ref_types_produits',$type_produit->type_produit_info['creusr'],'deletetype_produit');


?>

</ul>
