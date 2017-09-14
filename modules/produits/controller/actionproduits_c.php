	<ul class="dropdown-menu dropdown-menu-right">
	<?php 

	$produit = new Mproduit();
	$produit->id_produit= Mreq::tp('id');
	$produit->get_produit();


	$action = new TableTools();
	$action->line_data = $produit->produit_info;
	$action->action_line_table('produits', 'produits',$produit->produit_info['creusr'],'deleteproduit');


	?>

	</ul>
