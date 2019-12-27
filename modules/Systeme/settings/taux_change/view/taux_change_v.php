<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: taux_change
//Created : 11-12-2019
//View
//array colomn
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Taux Change";
$html_data_table->task = 'taux_change';
$html_data_table->btn_add_check=TRUE;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































