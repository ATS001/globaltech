<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: test_bololo
//Created : 29-10-2017
//Controller Liste
$array_column = array(
	array(
        'column' => 'test_bololo.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'test_bololo.nom',
                            'type'   => '',
                            'alias'  => 'nom',
                            'width'  => '15',
                            'header' => 'Nom',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'test_bololo.prenom',
                            'type'   => '',
                            'alias'  => 'prenom',
                            'width'  => '15',
                            'header' => 'Prénom',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'test_bololo.dat_nais',
                            'type'   => '',
                            'alias'  => 'dat_nais',
                            'width'  => '15',
                            'header' => 'Date Naiss',
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
$list_data_table->tables = array('test_bololo');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'test_bololo';
//Set Task used for statut line
$list_data_table->task = 'test_bololo';
//Set File name for export
$list_data_table->file_name = 'test_bololo';
//Set Title of report
$list_data_table->title_report = 'Liste test_bololo';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

