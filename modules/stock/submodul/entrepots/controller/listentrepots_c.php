<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'entrepots.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'entrepots.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'Référence',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'entrepots.libelle',
                            'type'   => '',
                            'alias'  => 'libelle',
                            'width'  => '15',
                            'header' => 'Entrepot',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'entrepots.emplacement',
                            'type'   => '',
                            'alias'  => 'emplacement',
                            'width'  => '15',
                            'header' => 'Emplacement Physique',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'entrepots.date_creation',
                            'type'   => 'date',
                            'alias'  => 'date_creation',
                            'width'  => '15',
                            'header' => 'Date de création',
                            'align'  => 'L'
                        ),

     
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'L'
    ),
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('entrepots');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'entrepots';
//Set Task used for statut line
$list_data_table->task = 'entrepots';
//Set File name for export
$list_data_table->file_name = 'entrepots';
//Set Title of report
$list_data_table->title_report = 'Liste Entrepots';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

