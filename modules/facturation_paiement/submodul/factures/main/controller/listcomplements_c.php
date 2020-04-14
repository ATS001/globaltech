<?php

//array colomn
$array_column = array(
    array(
        'column' => 'complement_facture.id',
        'type' => '',
        'alias' => 'id',
        'width' => '5',
        'header' => 'ID',
        'align' => 'C'
    ),
    array(
        'column' => 'complement_facture.designation',
        'type' => '',
        'alias' => 'des',
        'width' => '10',
        'header' => 'Désignation',
        'align' => 'L'
    ),
    array(
        'column' => 'complement_facture.montant',
        'type' => '',
        'alias' => 'mt',
        'width' => '10',
        'header' => 'Montant',
        'align' => 'R'
    ),
    array(
        'column' => 'complement_facture.type',
        'type' => '',
        'alias' => 'type',
        'width' => '15',
        'header' => 'Type',
        'align' => 'C'
    ),
    array(
        'column' => 'complement_facture.date_complement',
        'type' => '',
        'alias' => 'date',
        'width' => '15',
        'header' => 'Date',
        'align' => 'C'
    ),
);
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('complement_facture', 'factures');
//Set Jointure
$list_data_table->joint = 'complement_facture.idfacture=factures.id AND complement_facture.idfacture = ' . Mreq::tp('id');
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'complement_facture';
//Set Task used for statut line
$list_data_table->task = 'complements';
//Set File name for export
$list_data_table->file_name = 'liste_complements';
//Set Title of report
$list_data_table->title_report = 'Liste complements';
//Print JSON DATA
if (!$data = $list_data_table->Query_maker()) {
    exit("0#" . $list_data_table->log);
} else {
    echo $data;
}
?>
	
