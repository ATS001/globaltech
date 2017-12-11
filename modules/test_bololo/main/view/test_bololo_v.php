<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: test_bololo
//Created : 29-10-2017
//View
//array colomn
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "test_bololo";
$html_data_table->task = 'test_bololo';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































