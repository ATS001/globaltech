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
        'width'  => '25',
        'header' => 'Client',
        'align'  => 'L'
    ),
    array(
        'column' => 'devis.totalht',
        'type'   => 'int',
        'alias'  => 'montantht',
        'width'  => '10',
        'header' => 'Montant HT',
        'align'  => 'R'
    ),
    array(
        'column' => 'devis.totalttc',
        'type'   => 'int',
        'alias'  => 'montantttc',
        'width'  => '15',
        'header' => 'Montant TTC',
        'align'  => 'R'
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Devis";
$html_data_table->task = 'devis';
$html_data_table->btn_return = array('task' =>'tdb');

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>

















































