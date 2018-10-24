
<?php
//array colomn
$array_column = array(
	array(
        'column' => 'proforma.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'proforma.date_proforma',
        'type'   => 'date',
        'alias'  => 'date_proforma',
        'width'  => '13',
        'header' => 'Date proforma',
        'align'  => 'C'
    ),
    array(
        'column' => 'proforma.reference',
        'type'   => '',
        'alias'  => 'ref_produit',
        'width'  => '15',
        'header' => 'Référence',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT(clients.denomination,\' - \',ref_devise.abreviation)',
        'type'   => 'link',
        'link'   => array('CONCAT(clients.denomination,\' - \',ref_devise.abreviation)', 'detailsclient', 'clients.id'),
        'alias'  => 'client',
        'width'  => '23',
        'header' => 'Client',
        'align'  => 'L',
        'export' => 'clients.denomination',
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
$list_data_table->tables = array('proforma', 'clients', 'ref_devise');
//Set Jointure
$list_data_table->joint = 'clients.id = proforma.id_client  AND clients.id_devise = ref_devise.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'proforma';
//Set Task used for statut line
$list_data_table->task = 'proforma';
//Set File name for export
$list_data_table->file_name = 'liste_proformas';
//Set Title of report
$list_data_table->title_report = 'Liste Proformas';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
