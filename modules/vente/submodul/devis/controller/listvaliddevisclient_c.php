<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 09-10-2017
//Controller Liste
$array_column = array(
	array(
        'column' => 'devis.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'devis.tkn_frm',
                            'type'   => '',
                            'alias'  => 'tkn_frm',
                            'width'  => '15',
                            'header' => 'Token Form insert',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'reference',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.id_client',
                            'type'   => '',
                            'alias'  => 'id_client',
                            'width'  => '15',
                            'header' => 'id_client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.tva',
                            'type'   => '',
                            'alias'  => 'tva',
                            'width'  => '15',
                            'header' => 'Soumis à la TVA',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.id_commercial',
                            'type'   => '',
                            'alias'  => 'id_commercial',
                            'width'  => '15',
                            'header' => 'commercial chargé du suivi',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.date_devis',
                            'type'   => '',
                            'alias'  => 'date_devis',
                            'width'  => '15',
                            'header' => 'date_devis',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.type_remise',
                            'type'   => '',
                            'alias'  => 'type_remise',
                            'width'  => '15',
                            'header' => 'type de remise',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.valeur_remise',
                            'type'   => '',
                            'alias'  => 'valeur_remise',
                            'width'  => '15',
                            'header' => 'Valeur de la remise',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.totalht',
                            'type'   => '',
                            'alias'  => 'totalht',
                            'width'  => '15',
                            'header' => 'total ht des articles',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.totalttc',
                            'type'   => '',
                            'alias'  => 'totalttc',
                            'width'  => '15',
                            'header' => 'total ttc des articles',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.totaltva',
                            'type'   => '',
                            'alias'  => 'totaltva',
                            'width'  => '15',
                            'header' => 'total tva des articles',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.claus_comercial',
                            'type'   => '',
                            'alias'  => 'claus_comercial',
                            'width'  => '15',
                            'header' => 'clauses commercial devis',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'devis.devis_pdf',
                            'type'   => '',
                            'alias'  => 'devis_pdf',
                            'width'  => '15',
                            'header' => 'devis_pdf',
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
$list_data_table->tables = array('devis');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'devis';
//Set Task used for statut line
$list_data_table->task = 'validdevisclient';
//Set File name for export
$list_data_table->file_name = 'validdevisclient';
//Set Title of report
$list_data_table->title_report = 'Liste validdevisclient';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

