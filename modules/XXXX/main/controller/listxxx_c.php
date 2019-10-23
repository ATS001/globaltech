<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: XXXX
//Created : 19-10-2019
//Controller Liste
$array_column = array(
	array(
        'column' => 'visites.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'visites.commerciale',
                            'type'   => '',
                            'alias'  => 'commerciale',
                            'width'  => '15',
                            'header' => 'Commerciale',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'visites.raison_sociale',
                            'type'   => '',
                            'alias'  => 'raison_sociale',
                            'width'  => '15',
                            'header' => 'Raison sociale',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'visites.nature_visite',
                            'type'   => '',
                            'alias'  => 'nature_visite',
                            'width'  => '15',
                            'header' => 'Client / Prospect',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'visites.objet_visite',
                            'type'   => '',
                            'alias'  => 'objet_visite',
                            'width'  => '15',
                            'header' => 'Objet Visite',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'visites.date_visite',
                            'type'   => '',
                            'alias'  => 'date_visite',
                            'width'  => '15',
                            'header' => 'Date Visite',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'visites.interlocuteur',
                            'type'   => '',
                            'alias'  => 'interlocuteur',
                            'width'  => '15',
                            'header' => 'Interlocuteur',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'visites.fonction_interloc',
                            'type'   => 'int',
                            'alias'  => 'fonction_interloc',
                            'width'  => '15',
                            'header' => 'Fonction Interlocuteur',
                            'align'  => 'R'
                        ),
	array(
                            'column' => 'visites.coordonees_interloc',
                            'type'   => 'int',
                            'alias'  => 'coordonees_interloc',
                            'width'  => '15',
                            'header' => 'Coordonnées Interlocuteur',
                            'align'  => 'R'
                        ),
	array(
                            'column' => 'visites.commentaire',
                            'type'   => '',
                            'alias'  => 'commentaire',
                            'width'  => '15',
                            'header' => 'Commentaire',
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
$list_data_table->tables = array('visites');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'visites';
//Set Task used for statut line
$list_data_table->task = 'xxx';
//Set File name for export
$list_data_table->file_name = 'xxx';
//Set Title of report
$list_data_table->title_report = 'Liste xxx';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

