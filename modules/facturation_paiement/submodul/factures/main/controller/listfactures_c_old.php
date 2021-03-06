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
        'column' => 'CONCAT(c.reference, " - ",factures.client)',
        'type'   => '',
        'alias'  => 'con_clt',
        'width'  => '17',
        'header' => 'Client',
        'align'  => 'C'
    ),
    array(
        'column' => 'IF(base_fact="C",CONCAT("<b>DU</b> ", DATE_FORMAT(factures.du,"%d-%m-%Y")," <b>AU</b> ",DATE_FORMAT(factures.au,"%d-%m-%Y"))," ")',
        'type'   => '',
        'alias'  => 'clt',
        'width'  => '18',
        'header' => 'Période facturée',
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
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('factures,clients c,devis d');
//Set Jointure
$list_data_table->joint = 'IF(factures.`base_fact`="C",
( factures.idcontrat=(SELECT ctr.id FROM contrats ctr WHERE factures.idcontrat=ctr.id AND ctr.iddevis=d.id AND d.id_client=c.id ) ), 
(factures.iddevis=d.id )) AND d.id_client=c.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'factures';
//Set Task used for statut line
$list_data_table->task = 'factures';
//Set File name for export
$list_data_table->file_name = 'liste_factures';
//Set Title of report
$list_data_table->title_report = 'Liste factures';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
