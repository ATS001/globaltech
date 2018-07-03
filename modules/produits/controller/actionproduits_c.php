	<ul class="dropdown-menu dropdown-menu-right">
	<?php 

	$produit = new Mproduit();
	$produit->id_produit= Mreq::tp('id');
	$produit->get_produit();


	$action = new TableTools();
	$action->line_data = $produit->produit_info;
	$action->action_line_table('produits', 'produits',$produit->produit_info['creusr'],'deleteproduit');
/*
if($produit->produit_info["idtype"] == "3")
{
    //var_dump($action->app_action);
    $tab=$action->app_action;
    //var_dump($tab);
    $tab.strtr('<li><a href="#" class="this_url" data="%id%" rel="buyproduct"  ><i class="ace-icon fa fa-euro bigger-100"></i> Achat  produit</a></li>'," ");
var_dump($tab);
    
}
 * 
 */
	?>

	</ul>
