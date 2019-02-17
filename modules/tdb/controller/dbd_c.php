<!-- /.row -->
<?php 
//include 'addtest_c.php';
view::load_view('indicators');

//$chart = new MHighchart();
//$chart->call_chart('recette_per_month');


$chart = new MHighchart();
$chart->titre = 'Evolution des recettes par mois';
$chart->id_chart = 'recette_per_month';
$chart->items = 'Fcfa';
$chart->column_render('v_recet_per_month', 6);

//Best Product
$chart = new MHighchart();
$chart->titre = 'Partition recette par type produit';
$chart->items = 'Fcfa';
$chart->Pie_render('v_sum_best_type_product', 6, " intvl BETWEEN '2019-01-01' AND '2019-12-31'");

//Best Product
/*$chart = new MHighchart();
$chart->titre = 'Top Produits demandés';
$chart->items = 'Fcfa';
if($chart->table_rank_render('v_sum_best_product', 3, 'test'));*/

?>
