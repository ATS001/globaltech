<?php 

$posted_data = array(
   'date_debut'      => Mreq::tp('date_debut') ,
   'date_fin'      => Mreq::tp('date_fin')
       
        );


$checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['date_debut'] == NULL){

    $empty_list .= "<li>Date début</li>";
    $checker = 1;
  }
  
  if($posted_data['date_fin'] == NULL){

    $empty_list .= "<li>Date fin</li>";
    $checker = 1;
  }
  

  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  if (date('Y-m-d', strtotime($posted_data['date_debut'])) > date('Y-m-d', strtotime($posted_data['date_fin']))) {

        $control_date = "<ul>La date de début doit être inférieur à la date de fin </ul>";
        $checker = 2;
    }

    if ($checker == 2) {
        exit("0#$control_date");
    }
  
$clients = new Mclients();

$date_debut = Mreq::tp('date_debut');
$date_fin = Mreq::tp('date_fin');
$id_client = Mreq::tp('id');

$tab_mouvements=$clients->Gettable_detail_clients($date_debut, $date_fin,$id_client);

$array = array("tab" => $tab_mouvements);

echo json_encode($array);

