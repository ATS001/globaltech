
<?php
//array colomn
$array_column = array(
	array(
        'column' => 'devis.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.date_devis',
        'type'   => 'date',
        'alias'  => 'date_devis',
        'width'  => '10',
        'header' => 'Date devis',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.reference',
        'type'   => '',
        'alias'  => 'ref_produit',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'C'
    ),
    array(
        'column' => 'clients.denomination',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '40',
        'header' => 'Client',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.totalht',
        'type'   => 'int',
        'alias'  => 'montantht',
        'width'  => '10',
        'header' => 'Montant HT',
        'align'  => 'C'
    ),
    array(
        'column' => 'devis.totalttc',
        'type'   => 'int',
        'alias'  => 'montantttc',
        'width'  => '25',
        'header' => 'Montant TTC et le test text',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '25',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('devis', 'clients');
//Set Jointure
$list_data_table->joint = 'clients.id = devis.id_client';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'devis';
//Set Task used for statut line
$list_data_table->task = 'devis';
//Set File name for export
$list_data_table->file_name = 'liste_devis';
//Set Title of report
$list_data_table->title_report = 'Liste Devis';
//Print JSON DATA
print($list_data_table->Query_maker());



?>
	
