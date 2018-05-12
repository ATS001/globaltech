<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 09-05-2018
//View
//array colomn
$array_column = array(
	array(
        'column' => 'bl.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    //Complete Array fields here
    	array(
                            'column' => 'bl.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'reference',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'bl.client',
                            'type'   => '',
                            'alias'  => 'client',
                            'width'  => '15',
                            'header' => 'client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'bl.projet',
                            'type'   => '',
                            'alias'  => 'projet',
                            'width'  => '15',
                            'header' => 'designation projet',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'bl.ref_bc',
                            'type'   => '',
                            'alias'  => 'ref_bc',
                            'width'  => '15',
                            'header' => 'ref bon commande client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'bl.iddevis',
                            'type'   => '',
                            'alias'  => 'iddevis',
                            'width'  => '15',
                            'header' => 'Devis',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'bl.date_bl',
                            'type'   => '',
                            'alias'  => 'date_bl',
                            'width'  => '15',
                            'header' => 'date_bl',
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
$html_data_table->title_module = "generatebl_facture";
$html_data_table->task = 'generatebl_facture';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































