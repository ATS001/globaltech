<?php 
//var_dump($_COOKIE);
$chart = new MHighchart();
$chart->call_chart('recette_per_month');


$chart = new MHighchart();
$chart->titre = 'Evolution des recettes par mois';
$chart->id_chart = 'recette_per_month';
$chart->items = 'Fcfa';
$chart->column_render('v_recet_per_month', 6);

//Best Product
$chart = new MHighchart();
$chart->titre = 'Top Produits demandés';
$chart->items = 'Fcfa';
$chart->Pie_render('v_sum_best_product', 6);
?>
