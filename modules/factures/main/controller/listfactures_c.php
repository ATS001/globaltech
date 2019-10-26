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
        'width'  => '12',
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
        'width'  => '8',
        'header' => 'Total TTC',
        'align'  => 'R'
    ),
    
    array(
        'column' => 'factures.total_paye',
        'type'   => 'int',
        'alias'  => 'tp',
        'width'  => '8',
        'header' => 'Total payé',
        'align'  => 'R'
    ),
  
    array(
        'column' => 'factures.reste',
        'type'   => 'int',
        'alias'  => 'rest',
        'width'  => '8',
        'header' => 'Reste',
        'align'  => 'R'
    ),
    array(
        'column' => 'CONCAT((SELECT c.reference FROM clients c WHERE c.denomination=factures.client), " - ",factures.client)',
        'type'   => '',
        'alias'  => 'con_clt',
        'width'  => '27',
        'header' => 'Client',
        'align'  => 'C'
    ),
    array(
        'column' => 'IF(base_fact="C",CONCAT(" DU ", DATE_FORMAT(factures.du,"%d-%m-%Y")," AU ",DATE_FORMAT(factures.au,"%d-%m-%Y"))," ")',
        'type'   => 'html',
        'html'   => 'IF(base_fact="C",CONCAT("<b>DU</b> ", DATE_FORMAT(factures.du,"%d-%m-%Y")," <b>AU</b> ",DATE_FORMAT(factures.au,"%d-%m-%Y"))," ")',
        'alias'  => 'periode',
        'width'  => '12',
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
//Show line for owner
$only_owner = null;
$id_service = session::get('service');
if($id_service == 7)
{
    $only_owner = ' AND devis.creusr = '.session::get('userid');
}
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('factures');
//Set Jointure
$list_data_table->joint = ' 1=1 '.$only_owner;
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

//Set Fliter setting

//Set Order Status
//{"attente_validation":"0", "attente_envoi_client":"1", "attente_paiement":"2","paye_partiellement":"3","facture_payee":"4","facture_archivee":"100"}

$order_status = array(Msetting::get_set('etat_facture', 'attente_validation'), Msetting::get_set('etat_facture', 'attente_envoi_client'), Msetting::get_set('etat_facture', 'attente_paiement'), Msetting::get_set('etat_facture', 'paye_partiellement'),Msetting::get_set('etat_facture', 'facture_payee'),Msetting::get_set('etat_facture', 'facture_archivee'));
$list_data_table->order_status = $order_status;

//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
