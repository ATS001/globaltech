<?php 

$clients = new Mclients();

$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];

$tab_mouvements=$clients->Gettable_detail_clients($date_debut, $date_fin);

$array = array("tab" => $tab_mouvements);

echo json_encode($array);

