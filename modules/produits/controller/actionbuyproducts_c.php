	<ul class="dropdown-menu dropdown-menu-right">
	<?php 

	$achat = new Machat();
	$achat->id_achat= Mreq::tp('id');
	$achat->get_achat_produit();


	$action = new TableTools();
	$action->line_data = $achat->achat_info;
	$action->action_line_table('buyproduct', 'stock',$achat->achat_info['creusr'],'deletebuyproduct');


	?>

	</ul>
