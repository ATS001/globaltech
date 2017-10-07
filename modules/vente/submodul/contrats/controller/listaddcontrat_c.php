<?php
$array_column = array(
  array(
        'column' => 'echeances_contrat.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '10',
    ),
  array(
        'column' => 'echeances_contrat.order',
        'type'   => '',
        'alias'  => 'item',
        'width'  => '10',
    ),
  array(
        'column' => 'echeances_contrat.date_echeance',
        'type'   => 'date',
        'alias'  => 'date_echeance',
        'width'  => '10',
    ),
  array(
        'column' => 'echeances_contrat.montant',
        'type'   => '',
        'alias'  => 'montant',
        'width'  => '10',
    ),
  array(
        'column' => 'echeances_contrat.commentaire',
        'type'   => 'int',
        'alias'  => 'commentaire',
        'width'  => '10',
    ),
 

);
$tkn_frm = Mreq::tp('tkn_frm');
   
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('echeances_contrat');
//Set Jointure
$list_data_table->joint = "echeances_contrat.tkn_frm = '$tkn_frm' ";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'echeances_contrat';
//Set Task used for statut line
$list_data_table->task = 'contrats';
//This query no need notif statut
$list_data_table->need_notif = false;
//$list_data_table->debug = true;
//Print JSON DATA
print($list_data_table->Query_maker());
?>
  
