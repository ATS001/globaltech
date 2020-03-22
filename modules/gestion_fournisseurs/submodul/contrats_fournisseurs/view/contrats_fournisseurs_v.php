<?php
//array colomn
$array_column = array(
	array(
        'column' => 'contrats_frn.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats_frn.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '20',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'fournisseurs.denomination',
        'type'   => '',
        'alias'  => 'fournisseur',
        'width'  => '25',
        'header' => 'Fournisseur',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'contrats_frn.date_effet',
        'type'   => 'date',
        'alias'  => 'date_effet',
        'width'  => '15',
        'header' => 'Date Effet',
        'align'  => 'C'
    ),
    array(
        'column' => 'contrats_frn.date_fin',
        'type'   => 'date',
        'alias'  => 'date_fin',
        'width'  => '15',
        'header' => 'Date Fin',
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

//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Contrats Fournisseurs";
$html_data_table->task = 'contrats_fournisseurs';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>
	
