<ul class="dropdown-menu dropdown-menu-right">
<?php 

$categorie_produit = new Mcategorie_produit();
$categorie_produit->id_categorie_produit= Mreq::tp('id');
$categorie_produit->get_categorie_produit();


$action = new TableTools();
$action->line_data = $categorie_produit->categorie_produit_info;
$action->action_line_table('categories_produits', 'ref_categories_produits',$categorie_produit->categorie_produit_info['creusr'],'deletecategorie_produit');


?>

</ul>
