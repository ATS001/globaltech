<?php
if(Mreq::tp('id') != null)
{
    $info_facture = new Mfacture();
    $info_facture->id_facture = Mreq::tp('id');
    $info_facture->get_facture();
    $id_facture = Mreq::tp('id');
    $id_facture_c = MInit::crypt_tp('id', $id_facture);
}
    
    

    $array_column = array(
	array(
        'column' => 'encaissements.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'encaissements.reference',
        'type'   => '',
        'alias'  => 'reference',
        'width'  => '10',
        'header' => 'Référence',
        'align'  => 'L'
    ),
    array(
        'column' => 'encaissements.designation',
        'type'   => '',
        'alias'  => 'des',
        'width'  => '10',
        'header' => 'Désignation',
        'align'  => 'L'
    ),
  
    array(
        'column' => 'factures.reference',
        'type'   => '',
        'alias'  => 'freference',
        'width'  => '10',
        'header' => 'Facture',
        'align'  => 'L'
    ),
    array(
        'column' => 'encaissements.montant',
        'type'   => '',
        'alias'  => 'mt',
        'width'  => '15',
        'header' => 'Montant',
        'align'  => 'C'
    ),
     array(
        'column' => 'encaissements.date_encaissement',
        'type'   => 'date',
        'alias'  => 'date',
        'width'  => '15',
        'header' => 'Date',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    )
    
 );
    
    //Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "encaissements";
$html_data_table->task = 'encaissements';

//si t as besoin d'envoyer data ajoute key data Ã  Array ex: 'data' => 'id=$id'
$html_data_table->btn_return = array('task' => 'factures', 'title' => 'Retour liste factures');
$html_data_table->task = 'encaissements';
if(Mreq::tp('id') != null){
    $html_data_table->js_extra_data = "id=$id_facture";
    $html_data_table->btn_add_data = MInit::crypt_tp('id', $id_facture);
}


if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}

