<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: client_temp
//Created : 07-01-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'clients_temp.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'clients_temp.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'Reference client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients_temp.r_social',
                            'type'   => '',
                            'alias'  => 'r_social',
                            'width'  => '15',
                            'header' => 'Raison social',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients_temp.adresse',
                            'type'   => '',
                            'alias'  => 'adresse',
                            'width'  => '15',
                            'header' => 'Adresse',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients_temp.email',
                            'type'   => '',
                            'alias'  => 'email',
                            'width'  => '15',
                            'header' => 'E-mail',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients_temp.id_devise',
                            'type'   => '',
                            'alias'  => 'id_devise',
                            'width'  => '15',
                            'header' => 'Devise de facturation du client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients_temp.tva',
                            'type'   => '',
                            'alias'  => 'tva',
                            'width'  => '15',
                            'header' => 'tva O ou N',
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
$list_data_table->tables = array('clients_temp');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'clients_temp';
//Set Task used for statut line
$list_data_table->task = 'clients_temp';
//Set File name for export
$list_data_table->file_name = 'clients_temp';
//Set Title of report
$list_data_table->title_report = 'Liste clients_temp';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

