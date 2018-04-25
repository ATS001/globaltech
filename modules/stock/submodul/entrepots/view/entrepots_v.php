<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//View
//array colomn
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "entrepots";
$html_data_table->task = 'entrepots';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































