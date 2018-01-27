<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//View
//array colomn
$array_column = array(
	array(
        'column' => 'commerciaux.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    //Complete Array fields here
    	array(
                            'column' => 'commerciaux.nom',
                            'type'   => '',
                            'alias'  => 'nom',
                            'width'  => '10',
                            'header' => 'Nom',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'commerciaux.prenom',
                            'type'   => '',
                            'alias'  => 'prenom',
                            'width'  => '10',
                            'header' => 'Prénom',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'commerciaux.is_glbt',
                            'type'   => '',
                            'alias'  => 'is_glbt',
                            'width'  => '10',
                            'header' => 'Interne',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'commerciaux.cin',
                            'type'   => '',
                            'alias'  => 'cin',
                            'width'  => '10',
                            'header' => 'CIN',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'commerciaux.rib',
                            'type'   => '',
                            'alias'  => 'rib',
                            'width'  => '10',
                            'header' => 'RIB',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'commerciaux.tel',
                            'type'   => '',
                            'alias'  => 'tel',
                            'width'  => '10',
                            'header' => 'Téléphone',
                            'align'  => 'L'
                        ),
    array(
                            'column' => 'commerciaux.email',
                            'type'   => '',
                            'alias'  => 'email',
                            'width'  => '10',
                            'header' => 'E-mail',
                            'align'  => 'L'
                        ),

    
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '10',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "commerciale";
$html_data_table->task = 'commerciale';



if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































