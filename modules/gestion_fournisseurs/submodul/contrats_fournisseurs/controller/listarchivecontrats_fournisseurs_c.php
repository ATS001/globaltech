<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs
//Created : 01-08-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'contrats_frn.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'contrats_frn.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'Reference',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.id_fournisseur',
                            'type'   => '',
                            'alias'  => 'id_fournisseur',
                            'width'  => '15',
                            'header' => 'id_fournisseur',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.date_effet',
                            'type'   => '',
                            'alias'  => 'date_effet',
                            'width'  => '15',
                            'header' => 'date_effet',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.date_fin',
                            'type'   => '',
                            'alias'  => 'date_fin',
                            'width'  => '15',
                            'header' => 'date_fin',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.commentaire',
                            'type'   => '',
                            'alias'  => 'commentaire',
                            'width'  => '15',
                            'header' => 'commentaire',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.date_notif',
                            'type'   => '',
                            'alias'  => 'date_notif',
                            'width'  => '15',
                            'header' => 'date_notif',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.pj',
                            'type'   => '',
                            'alias'  => 'pj',
                            'width'  => '15',
                            'header' => 'pj',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'contrats_frn.pj_photo',
                            'type'   => '',
                            'alias'  => 'pj_photo',
                            'width'  => '15',
                            'header' => 'pj_photo',
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
$list_data_table->tables = array('contrats_frn');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'contrats_frn';
//Set Task used for statut line
$list_data_table->task = 'archivecontrats_fournisseurs';
//Set File name for export
$list_data_table->file_name = 'archivecontrats_fournisseurs';
//Set Title of report
$list_data_table->title_report = 'Liste archivecontrats_fournisseurs';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

