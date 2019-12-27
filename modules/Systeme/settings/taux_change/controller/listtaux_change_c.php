<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: taux_change
//Created : 11-12-2019
//Controller Liste
$array_column = array(
	array(
        'column' => 'sys_taux_change.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
    array(
        'column' => 'd.devise',
        'type'   => '',
        'alias'  => 'id_devise',
        'width'  => '15',
        'header' => 'Devise',
        'align'  => 'C'
                        ),
	array(
        'column' => 'sys_taux_change.conversion',
        'type'   => '',
        'alias'  => 'conversion',
        'width'  => '15',
        'header' => 'Conversion',
        'align'  => 'R'
                        )
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('sys_taux_change, ref_devise d');
//Set Jointure
$list_data_table->joint = ' sys_taux_change.id_devise = d.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'sys_taux_change';
//Set Task used for statut line
$list_data_table->task = 'taux_change';
//Set File name for export
$list_data_table->file_name = 'taux_change';
//Set Title of report
$list_data_table->title_report = 'Taux Change';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

