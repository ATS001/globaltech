<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: gestion_fournisseurs
//Created : 22-07-2018
//Controller Liste
$array_column = array(
	array(
        'column' => 'fournisseurs.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
     //Complete Array fields here
     	array(
                            'column' => 'fournisseurs.reference',
                            'type'   => '',
                            'alias'  => 'reference',
                            'width'  => '15',
                            'header' => 'Code fournisseur',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.denomination',
                            'type'   => '',
                            'alias'  => 'denomination',
                            'width'  => '15',
                            'header' => 'Denomination du client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.r_social',
                            'type'   => '',
                            'alias'  => 'r_social',
                            'width'  => '15',
                            'header' => 'Raison social',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.r_commerce',
                            'type'   => '',
                            'alias'  => 'r_commerce',
                            'width'  => '15',
                            'header' => 'Registre de commerce',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.nif',
                            'type'   => '',
                            'alias'  => 'nif',
                            'width'  => '15',
                            'header' => 'Id fiscale',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.nom',
                            'type'   => '',
                            'alias'  => 'nom',
                            'width'  => '15',
                            'header' => 'Nom',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.prenom',
                            'type'   => '',
                            'alias'  => 'prenom',
                            'width'  => '15',
                            'header' => 'Prénom',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.civilite',
                            'type'   => '',
                            'alias'  => 'civilite',
                            'width'  => '15',
                            'header' => 'Sexe',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.adresse',
                            'type'   => '',
                            'alias'  => 'adresse',
                            'width'  => '15',
                            'header' => 'Adresse',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.id_pays',
                            'type'   => '',
                            'alias'  => 'id_pays',
                            'width'  => '15',
                            'header' => 'Pays',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.id_ville',
                            'type'   => '',
                            'alias'  => 'id_ville',
                            'width'  => '15',
                            'header' => 'Ville',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.tel',
                            'type'   => '',
                            'alias'  => 'tel',
                            'width'  => '15',
                            'header' => 'Telephone',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.fax',
                            'type'   => '',
                            'alias'  => 'fax',
                            'width'  => '15',
                            'header' => 'Fax',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.bp',
                            'type'   => '',
                            'alias'  => 'bp',
                            'width'  => '15',
                            'header' => 'Boite postale',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.email',
                            'type'   => '',
                            'alias'  => 'email',
                            'width'  => '15',
                            'header' => 'E-mail',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.rib',
                            'type'   => '',
                            'alias'  => 'rib',
                            'width'  => '15',
                            'header' => 'compte bancaire du fournisseur',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.id_devise',
                            'type'   => '',
                            'alias'  => 'id_devise',
                            'width'  => '15',
                            'header' => 'Devise de facturation du client',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.id_motif_blocage',
                            'type'   => '',
                            'alias'  => 'id_motif_blocage',
                            'width'  => '15',
                            'header' => 'Motif Blocage',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.date_blocage',
                            'type'   => '',
                            'alias'  => 'date_blocage',
                            'width'  => '15',
                            'header' => 'Date Blocage',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.commentaire',
                            'type'   => '',
                            'alias'  => 'commentaire',
                            'width'  => '15',
                            'header' => 'commentaire',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.pj',
                            'type'   => '',
                            'alias'  => 'pj',
                            'width'  => '15',
                            'header' => 'pj',
                            'align'  => 'L'
                        ),
	array(
                            'column' => 'fournisseurs.pj_photo',
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
$list_data_table->tables = array('fournisseurs');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'fournisseurs';
//Set Task used for statut line
$list_data_table->task = 'gestion_fournisseurs';
//Set File name for export
$list_data_table->file_name = 'gestion_fournisseurs';
//Set Title of report
$list_data_table->title_report = 'Liste gestion_fournisseurs';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

