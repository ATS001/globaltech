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
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'devis.reference',
        'type'   => '',
        'alias'  => 'devis',
        'width'  => '10',
        'header' => 'Devis',
        'align'  => 'L'
    ),
    array(
        'column' => 'contrats.date_contrat',
        'type'   => 'date',
        'alias'  => 'date_contrat',
        'width'  => '10',
        'header' => 'Date Contrat',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.projet',
        'type'   => '',
        'alias'  => 'projet',
        'width'  => '10',
        'header' => 'Projet',
        'align'  => 'L'
    ),
    array(
        'column' => 'contrats.date_effet',
        'type'   => 'date',
        'alias'  => 'date_effet',
        'width'  => '10',
        'header' => 'Date Début',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats.date_fin',
        'type'   => 'date',
        'alias'  => 'date_fin',
        'width'  => '10',
        'header' => 'Date Fin',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('contrats', 'devis');
//Set Jointure
$list_data_table->joint = 'contrats.iddevis = devis.id ';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'contrats';
//Set Task used for statut line
$list_data_table->task = 'contrats';
//Set File name for export
$list_data_table->file_name = 'liste_contrats';
//Set Title of report
$list_data_table->title_report = 'Liste des contrats';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
