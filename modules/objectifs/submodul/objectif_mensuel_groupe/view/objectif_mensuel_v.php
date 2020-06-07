<?php
//First check target no Hack
if(!defined('_MEXEC'))die();


if (Mreq::tp('id') != null) {
    $info_objectif_annuel = new Mobjectif_mensuel_groupe();
    $info_objectif_annuel->id_objectif_mensuel_groupe = Mreq::tp('id');
    $info_objectif_annuel->get_objectif_mensuel_groupe();
    $id_commercial = $info_objectif_annuel->objectif_mensuel_groupe_info["id_commercial"];
    $id_commercial_c = MInit::crypt_tp('id_commercial', $id_commercial);
$annee = $info_objectif_annuel->objectif_mensuel_groupe_info["annee"];
$annee_c = MInit::crypt_tp('annee', $annee);
}else{

  $id_commercial = Mreq::tp('id_commercial');
  $id_commercial_c = MInit::crypt_tp('id_commercial', $id_commercial);
  $annee = Mreq::tp('annee');
  $annee_c = MInit::crypt_tp('annee', $annee);
}

$array_column = array(
    array(
        'column' => 'objectif_mensuel.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '3',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT(commerciaux.nom,\' \',commerciaux.prenom)',
        'type'   => '',
        'alias'  => 'commercial',
        'width'  => '10',
        'header' => 'Commercial',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_mensuel.description',
        'type'   => '',
        'alias'  => 'designation',
        'width'  => '15',
        'header' => 'Désignation',
        'align'  => 'L'
    ),
    array(
        'column' => 'objectif_mensuel.objectif',
        'type'   => '',
        'alias'  => 'objectif',
        'width'  => '5',
        'header' => 'Objectif',
        'align'  => 'R'
    ),
    array(
        'column' => 'objectif_mensuel.realise',
        'type'   => '',
        'alias'  => 'realise',
        'width'  => '5',
        'header' => 'Réalisation',
        'align'  => 'R'
    ),
    array(
        'column' => 'objectif_mensuel.seuil',
        'type'   => '',
        'alias'  => 'seuil',
        'width'  => '5',
        'header' => 'Seuil',
        'align'  => 'C'
    ),

    /*array(
        'column' => 'objectif_mensuel.date_s',
        'type'   => 'date',
        'alias'  => 'date_s',
        'width'  => '7',
        'header' => 'Date début',
        'align'  => 'C'
    ),
    array(
        'column' => 'objectif_mensuel.date_e',
        'type'   => 'date',
        'alias'  => 'date_e',
        'width'  => '7',
        'header' => 'Date fin',
        'align'  => 'C'
    ),*/


    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '12',
        'header' => 'Statut',
        'align'  => 'C'
    ),

);
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Objectifs par Commercial";
$html_data_table->task = 'objectif_mensuel';
$html_data_table->task_add = 'add_objectif_mensuel';
$html_data_table->btn_return = array('task' => 'objectif_mensuel_groupe', 'title' => 'Retour liste Objectifs');

if (Mreq::tp('id') != null) {
    $html_data_table->js_extra_data = "id_commercial=$id_commercial&annee=$annee";
    //$html_data_table->btn_add_data = MInit::crypt_tp('id_commercial', $id_commercial)&MInit::crypt_tp('annee', $annee);
    $html_data_table->btn_add_data = "id_commercial=$id_commercial&annee=$annee";
  }

  if (Mreq::tp('id_commercial') != null) {
    $html_data_table->js_extra_data = "id_commercial=$id_commercial&annee=$annee";
  }

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
