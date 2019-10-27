<?php
//array colomn
$array_column = array(
	array(
        'column' => 'clients.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'clients.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '13',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'clients.denomination',
        'type'   => '',
        'alias'  => 'denomination',
        'width'  => '30',
        'header' => 'Dénomination',
        'align'  => 'L'
    ),
    array(
        'column' => 'categorie_client.categorie_client',
        'type'   => '',
        'alias'  => 'categorie_client',
        'width'  => '15',
        'header' => 'Catégorie ',
        'align'  => 'C'
    ),
    array(
        'column' => "clients.credat",
        'type'   => 'date',
        'alias'  => 'date_client',
        'width'  => '8',
        'header' => 'Date Création',
        'align'  => 'C'
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
//Show line for owner
$only_owner = null;
$id_service = session::get('service');
if($id_service == 7)
{
    $only_owner = ' AND clients.creusr = '.session::get('userid');
}
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('clients', 'categorie_client');
//Set Jointure
$list_data_table->joint = 'clients.id_categorie = categorie_client.id'.$only_owner;
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'clients';
//Set Task used for statut line
$list_data_table->task = 'clients';
//Set File name for export
$list_data_table->file_name = 'liste_clients';
//Set Title of report
$list_data_table->title_report = 'Liste Clients';

//Set Fliter setting
$list_data_table->data_filter = array('id' => array('int','5'), 'categorie_client' => array('text','9'), 'denomination' => array('text','9'), 'date_client' => array('date','5'));

//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
