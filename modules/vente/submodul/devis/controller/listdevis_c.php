
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
        'width'  => '12',
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
$list_data_table->tables = array('devis', 'clients', 'ref_devise');
//Set Jointure
$list_data_table->joint = 'clients.id = devis.id_client AND clients.id_devise = ref_devise.id '.$only_owner;


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
//Set Fliter setting
$list_data_table->data_filter = array('id' => array('int','5'), 'client' => array('text','9'), 'date_devis' => array('date','5'), 'ref_produit' => array('text','5') );
//Set Order Status
//{"creat_devis":"0", "valid_devis":"1",  "send_devis":"2", "modif_client": "3", "valid_client":"4", "refus_client":"5", "devis_expir":"6", "devis_archive":"7", "valid_devis_dg":"8"}
$order_status = array(Msetting::get_set('etat_devis', 'creat_devis'), Msetting::get_set('etat_devis', 'valid_devis'), Msetting::get_set('etat_devis', 'valid_devis_dg'), Msetting::get_set('etat_devis', 'send_devis'), Msetting::get_set('etat_devis', 'modif_client'), Msetting::get_set('etat_devis', 'valid_client'), Msetting::get_set('etat_devis', 'refus_client'), Msetting::get_set('etat_devis', 'devis_expir'), Msetting::get_set('etat_devis', 'devis_archive'));
$list_data_table->order_status = $order_status;
//$list_data_table->debug = true;
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
