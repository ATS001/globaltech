<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: client_temp
//Created : 07-01-2018
//View
//array colomn
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "clients_temp";
$html_data_table->task = 'clients_temp';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































