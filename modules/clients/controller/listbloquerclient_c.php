<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: clients
//Created : 18-07-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'clients.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'clients.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'Code client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.type_client',
                            'type'   => '',
                            'alias'  => 'type_client',
                            'width'  => '15',
                            'header' => 'Type client D/T',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.denomination',
                            'type'   => '',
                            'alias'  => 'denomination',
                            'width'  => '15',
                            'header' => 'Denomination du client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.id_categorie',
                            'type'   => '',
                            'alias'  => 'id_categorie',
                            'width'  => '15',
                            'header' => 'Type client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.r_social',
                            'type'   => '',
                            'alias'  => 'r_social',
                            'width'  => '15',
                            'header' => 'Raison social',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.r_commerce',
                            'type'   => '',
                            'alias'  => 'r_commerce',
                            'width'  => '15',
                            'header' => 'Registre de commerce',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.nif',
                            'type'   => '',
                            'alias'  => 'nif',
                            'width'  => '15',
                            'header' => 'Id fiscale',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.nom',
                            'type'   => '',
                            'alias'  => 'nom',
                            'width'  => '15',
                            'header' => 'Nom',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.prenom',
                            'type'   => '',
                            'alias'  => 'prenom',
                            'width'  => '15',
                            'header' => 'Prénom',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.civilite',
                            'type'   => '',
                            'alias'  => 'civilite',
                            'width'  => '15',
                            'header' => 'Sexe',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.adresse',
                            'type'   => '',
                            'alias'  => 'adresse',
                            'width'  => '15',
                            'header' => 'Adresse',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.id_pays',
                            'type'   => '',
                            'alias'  => 'id_pays',
                            'width'  => '15',
                            'header' => 'Pays',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.id_ville',
                            'type'   => '',
                            'alias'  => 'id_ville',
                            'width'  => '15',
                            'header' => 'Ville',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.tel',
                            'type'   => '',
                            'alias'  => 'tel',
                            'width'  => '15',
                            'header' => 'Telephone',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.fax',
                            'type'   => '',
                            'alias'  => 'fax',
                            'width'  => '15',
                            'header' => 'Fax',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.bp',
                            'type'   => '',
                            'alias'  => 'bp',
                            'width'  => '15',
                            'header' => 'Boite postale',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.email',
                            'type'   => '',
                            'alias'  => 'email',
                            'width'  => '15',
                            'header' => 'E-mail',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.rib',
                            'type'   => '',
                            'alias'  => 'rib',
                            'width'  => '15',
                            'header' => 'compte bancaire du client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.id_devise',
                            'type'   => '',
                            'alias'  => 'id_devise',
                            'width'  => '15',
                            'header' => 'Devise de facturation du client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.tva',
                            'type'   => '',
                            'alias'  => 'tva',
                            'width'  => '15',
                            'header' => 'tva O ou N',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.pj',
                            'type'   => '',
                            'alias'  => 'pj',
                            'width'  => '15',
                            'header' => 'pj',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'clients.pj_photo',
                            'type'   => '',
                            'alias'  => 'pj_photo',
                            'width'  => '15',
                            'header' => 'photo du client',
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
$list_data_table->tables = array('clients');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'clients';
//Set Task used for statut line
$list_data_table->task = 'bloquerclient';
//Set File name for export
$list_data_table->file_name = 'bloquerclient';
//Set Title of report
$list_data_table->title_report = 'Liste bloquerclient';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

