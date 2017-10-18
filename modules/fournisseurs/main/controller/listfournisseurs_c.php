<?php
//array colomn
$array_column = array(
	array(
        'column' => 'fournisseurs.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'fournisseurs.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'fournisseurs.denomination',
        'type'   => '',
        'alias'  => 'denomination',
        'width'  => '10',
        'header' => 'Dénomination',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'fournisseurs.r_social',
        'type'   => '',
        'alias'  => 'r_social',
        'width'  => '10',
        'header' => 'Raison Sociale',
        'align'  => 'L'
    ),
    array(
        'column' => 'ref_pays.pays',
        'type'   => '',
        'alias'  => 'pays',
        'width'  => '15',
        'header' => 'Pays',
        'align'  => 'L'
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
$list_data_table->tables = array('fournisseurs', 'ref_pays');
//Set Jointure
$list_data_table->joint = 'fournisseurs.id_pays = ref_pays.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'fournisseurs';
//Set Task used for statut line
$list_data_table->task = 'fournisseurs';
//Set File name for export
$list_data_table->file_name = 'liste_fournisseurs';
//Set Title of report
$list_data_table->title_report = 'Liste Fournisseurs';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
