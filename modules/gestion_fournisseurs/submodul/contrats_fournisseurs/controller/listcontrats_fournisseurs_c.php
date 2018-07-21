<?php
//array colomn
$array_column = array(
	array(
        'column' => 'contrats_frn.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats_frn.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'fournisseurs.denomination',
        'type'   => '',
        'alias'  => 'fournisseur',
        'width'  => '15',
        'header' => 'Fournisseur',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'contrats_frn.date_effet',
        'type'   => 'date',
        'alias'  => 'date_effet',
        'width'  => '10',
        'header' => 'Date Effet',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats_frn.date_fin',
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
$list_data_table->tables = array('contrats_frn', 'fournisseurs');
//Set Jointure
$list_data_table->joint = 'contrats_frn.id_fournisseur = fournisseurs.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'contrats_frn';
//Set Task used for statut line
$list_data_table->task = 'contrats_fournisseurs';
//Set File name for export
$list_data_table->file_name = 'liste_contrats';
//Set Title of report
$list_data_table->title_report = 'Liste des contrats fournisseurs';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
