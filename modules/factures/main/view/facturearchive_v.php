<?php
//array colomn
$array_column = array(
	array(
        'column' => 'factures.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'factures.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'factures.date_facture',
        'type'   => 'date',
        'alias'  => 'df',
        'width'  => '8',
        'header' => 'Date',
        'align'  => 'C'
    ),
    array(
        'column' => 'factures.total_ttc',
        'type'   => 'int',
        'alias'  => 'tttc',
        'width'  => '6',
        'header' => 'Total TTC',
        'align'  => 'R'
    ),
    
    array(
        'column' => 'factures.total_paye',
        'type'   => 'int',
        'alias'  => 'tp',
        'width'  => '7',
        'header' => 'Total payé',
        'align'  => 'R'
    ),
  
    array(
        'column' => 'factures.reste',
        'type'   => 'int',
        'alias'  => 'rest',
        'width'  => '6',
        'header' => 'Reste',
        'align'  => 'R'
    ),
    array(
        'column' => 'CONCAT(c.code, " - ",factures.client)',
        'type'   => '',
        'alias'  => 'con_clt',
        'width'  => '15',
        'header' => 'Client',
        'align'  => 'C'
    ),
    array(
        'column' => 'IF(base_fact="C",CONCAT("<b>DU</b> ", DATE_FORMAT(factures.du,"%d-%m-%Y")," <b>AU</b> ",DATE_FORMAT(factures.au,"%d-%m-%Y"))," ")',
        'type'   => '',
        'alias'  => 'clt',
        'width'  => '15',
        'header' => 'Période facturée',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );

//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Factures Exprirées";
$html_data_table->task = 'facturearchive';

$html_data_table->btn_return = array('task' => 'factures', 'title' => 'Liste Facture');
$html_data_table->btn_add_check=TRUE;

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>

