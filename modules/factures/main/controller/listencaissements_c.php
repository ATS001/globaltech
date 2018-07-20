<?php
//array colomn
$array_column = array(
	array(
        'column' => 'encaissements.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    
    array(
        'column' => 'encaissements.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'factures.client',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '15',
        'header' => 'Client',
        'align'  => 'L'
    ),
    
    
    array(
        'column' => 'encaissements.designation',
        'type'   => '',
        'alias'  => 'des',
        'width'  => '15',
        'header' => 'Désignation',
        'align'  => 'L'
    ),
  
    
    array(
        'column' => 'encaissements.montant',
        'type'   => '',
        'alias'  => 'mt',
        'width'  => '10',
        'header' => 'Montant',
        'align'  => 'C'
    ),
     array(
        'column' => 'encaissements.date_encaissement',
        'type'   => 'date',
        'alias'  => 'date',
        'width'  => '10',
        'header' => 'Date',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    )
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('encaissements', 'factures');
//Set Jointure
//Where id facture is null then show all encaissement
$where = Mreq::tp('id') == null ? null : 'AND encaissements.idfacture = '.Mreq::tp('id');
$list_data_table->joint = 'encaissements.idfacture=factures.id '.$where;
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'encaissements';
//Set Task used for statut line
$list_data_table->task = 'encaissements';
//Set File name for export
$list_data_table->file_name = 'liste_encaissements';
//Set Title of report
$list_data_table->title_report = 'Liste encaissements';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
