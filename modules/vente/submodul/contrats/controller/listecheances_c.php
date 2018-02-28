<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 26-02-2018
//Controller Liste

$array_column = array(
	array(
        'column' => 'echeances_contrat.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    
    array(
                            'column' => 'contrats.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'Reference',
                            'align'  => 'L'
                        ),
    
    //Complete Array fields here
    	array(
                            'column' => 'echeances_contrat.date_echeance',
                            'type'   => 'date',
                            'alias'  => 'date_echeance',
                            'width'  => '10',
                            'header' => 'Date échéance',
                            'align'  => 'C'
                        ),
	
	array(
                            'column' => 'echeances_contrat.date_debut',
                            'type'   => 'date',
                            'alias'  => 'dd',
                            'width'  => '15',
                            'header' => 'Date debut',
                            'align'  => 'C'
                        ),
	array(
                            'column' => 'echeances_contrat.date_fin',
                            'type'   => 'date',
                            'alias'  => 'df',
                            'width'  => '15',
                            'header' => 'Date fin',
                            'align'  => 'C'
                        ),
	
	
/*
    
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'L'
    ),
    */
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('contrats','echeances_contrat');
//Set Jointure
$list_data_table->joint = 'contrats.id=echeances_contrat.idcontrat AND echeances_contrat.idcontrat='.Mreq::tp('id_contrat');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'echeances_contrat';
//Set Task used for statut line
$list_data_table->task = 'echeances';
//Set File name for export
$list_data_table->file_name = 'echeances';
//Set Title of report
$list_data_table->title_report = 'Liste des échéances';

//var_dump($list_data_table->debug=true);

//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

