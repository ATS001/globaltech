<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 26-02-2018
//View
//array colomn

$contrat = new Mcontrat();
//Set ID of Module with POST id
$contrat->id_contrat = Mreq::tp('id');
$contrat->get_contrat();
$id = Mreq::tp('id');

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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "echeances";
$html_data_table->task = 'echeances';

//si t as besoin d'envoyer data ajoute key data Ã  Array ex: 'data' => 'id=$id'
$html_data_table->btn_return = array('task' => 'contrats', 'title' => 'Retour liste des abonnements');
$html_data_table->task = 'echeances';
$html_data_table->js_extra_data = "id_contrat=$id";
$html_data_table->btn_add_check=TRUE;
//$html_data_table->btn_add_data = MInit::crypt_tp('id_contrat', $id);

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































