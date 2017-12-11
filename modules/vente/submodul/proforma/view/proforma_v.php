<?php
//array colomn
$array_column = array(
	array(
        'column' => '',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => 'date',
        'alias'  => 'date_proforma',
        'width'  => '10',
        'header' => 'Date proforma',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'ref_produit',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'client',
        'width'  => '25',
        'header' => 'Client',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'L'
    ),
    
 );
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Proforma";
$html_data_table->task = 'proforma';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>
