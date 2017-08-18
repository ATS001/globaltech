<?php
view::load('tdb', 'tdb');
view::load('tdb', 'indicators');
view::load('tdb', 'vsat_graph');
$chart = new MHighchart();
$chart->titre = 'Station VSAT par Catégorie Permissionnaires';
$chart->items = 'Stations';
$chart->Pie_render('v_count_s_vsat_per_cat_perm', 6);
$chart = new MHighchart();

?>

