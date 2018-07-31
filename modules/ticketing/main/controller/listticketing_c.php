<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticketing
//Created : 18-07-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'tickets.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'tickets.id_client',
                            'type'   => '',
                            'alias'  => 'id_client',
                            'width'  => '15',
                            'header' => 'Client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.projet',
                            'type'   => '',
                            'alias'  => 'projet',
                            'width'  => '15',
                            'header' => 'Projet',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.message',
                            'type'   => '',
                            'alias'  => 'message',
                            'width'  => '15',
                            'header' => 'Message',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.date_previs',
                            'type'   => '',
                            'alias'  => 'date_previs',
                            'width'  => '15',
                            'header' => 'Date prévisionnelle',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.date_realis',
                            'type'   => '',
                            'alias'  => 'date_realis',
                            'width'  => '15',
                            'header' => 'Date de réalisation',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.type_produit',
                            'type'   => '',
                            'alias'  => 'type_produit',
                            'width'  => '15',
                            'header' => 'Type produit',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.categorie_produit',
                            'type'   => '',
                            'alias'  => 'categorie_produit',
                            'width'  => '15',
                            'header' => 'Catégorie produit',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.id_produit',
                            'type'   => '',
                            'alias'  => 'id_produit',
                            'width'  => '15',
                            'header' => 'Produit',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.id_technicien',
                            'type'   => '',
                            'alias'  => 'id_technicien',
                            'width'  => '15',
                            'header' => 'Technicien',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.date_affectation',
                            'type'   => '',
                            'alias'  => 'date_affectation',
                            'width'  => '15',
                            'header' => 'Date affectation',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.code_cloture',
                            'type'   => '',
                            'alias'  => 'code_cloture',
                            'width'  => '15',
                            'header' => 'Code cloture',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.observation',
                            'type'   => '',
                            'alias'  => 'observation',
                            'width'  => '15',
                            'header' => 'observation',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'tickets.serial_number',
                            'type'   => '',
                            'alias'  => 'serial_number',
                            'width'  => '15',
                            'header' => 'serial_number',
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
$list_data_table->tables = array('tickets');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'tickets';
//Set Task used for statut line
$list_data_table->task = 'ticketing';
//Set File name for export
$list_data_table->file_name = 'ticketing';
//Set Title of report
$list_data_table->title_report = 'Liste ticketing';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

