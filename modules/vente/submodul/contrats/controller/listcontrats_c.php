<?php
//array colomn
$array_column = array(
    array(
        'column' => 'contrats.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '13',
        'header' => 'Référence',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.reference',
        'type'   => '',
        'alias'  => 'devis',
        'width'  => '13',
        'header' => 'Devis',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.date_contrat',
        'type'   => 'date',
        'alias'  => 'date_contrat',
        'width'  => '8',
        'header' => 'Date Contrat',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT(clients.reference, " - ",clients.denomination)',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '30',
        'header' => 'Client',
        'align'  => 'L'
    ),
    array(
        'column' => 'contrats.date_effet',
        'type'   => 'date',
        'alias'  => 'date_effet',
        'width'  => '8',
        'header' => 'Date Début',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.date_fin',
        'type'   => 'date',
        'alias'  => 'date_fin',
        'width'  => '8',
        'header' => 'Date Fin',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '10',
        'header' => 'Statut',
        'align'  => 'C'
    ),
 );
//Show line for owner
$only_owner = null;
$id_service = session::get('service');
if($id_service == 7)
{
    $only_owner = ' AND contrats.creusr  = '.session::get('userid');
}
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('contrats', 'devis','clients');
//Set Jointure
$list_data_table->joint = 'contrats.iddevis = devis.id and clients.id = devis.id_client'.$only_owner;
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'contrats';
//Set Task used for statut line
$list_data_table->task = 'contrats';
//Set File name for export
$list_data_table->file_name = 'liste_contrats';
//Set Title of report
$list_data_table->title_report = 'Liste des abonnements';

//Set Fliter setting
$list_data_table->data_filter = array('id' => array('int','5'), 'client' => array('text','9'), 'date_contrat' => array('date','5'), 'date_effet' => array('date','5'), 'date_fin' => array('date','5') );

//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

?>