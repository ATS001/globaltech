<?php 
//SYS GLOBAL TECH
// Modul: factures => Controller Liste

global $db;
$params = $columns = $totalRecords = $data = array();

$params = $_REQUEST;

//define index of column
$columns = array(
    0 => 'id',
    1 => 'ref',
    2 => 'total_paye',
    3 => 'tva',
    4 => 'client',
    5 => 'statut'
);



$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
// define used table.
$tables .= "factures";
// set sherched columns.(the final colm without comma)
$colms .= " factures.id AS id, ";
$colms .= " factures.ref as ref, ";
$colms .= " factures.total_paye as tp, ";
$colms .= " factures.tva as tva, ";
$colms .= " factures.client as clt, ";


//difine if user have rule to show line depend of etat 
$where_etat_line = TableTools::where_etat_line('factures', 'factures');
//define notif culomn to concatate with any colms.
//this is change style of button action to red
$notif_colms = TableTools::line_notif_new('factures', 'factures');
$colms .= $notif_colms;


// check search value exist
if (!empty($params['search']['value']) or Mreq::tp('id_search') != NULL) {

    $serch_value = str_replace('+', ' ', $params['search']['value']);
    //Format where in case joint isset  
    $where_s .= $joint == NULL ? " WHERE " : " AND ";



    $where_s .= " AND ( factures.id LIKE '%" . $serch_value . "%' ";
    $where_s .= " OR (factures.ref LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (factures.total_paye LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (factures.tva LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (factures.client LIKE '%" . $serch_value . "%') ";
    $where_s .= TableTools::where_search_etat('factures', 'factures', $serch_value);
}




$where .= $where_etat_line;
$where .= $joint;
$where .= $where_s == NULL ? NULL : $where_s;
// getting total number records without any search
$sql = "SELECT $colms  FROM  $tables  ";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if (isset($where) && $where != NULL) {

    $sqlTot .= $where;
    $sqlRec .= $where;
}

//if we use notification we must ordring lines by nofication rule in first
//Change ('notif', status) with ('notif', column where notif code is concated)
//on case of order by other parametre this one is disabled (Check Export query)

$order_notif = TableTools::order_bloc($params['order'][0]['column']);

$sqlRec .= " ORDER BY $order_notif  " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";


if (!$db->Query($sqlTot))
    $db->Kill($db->Error() . " SQLTOT $sqlTot");
//
$totalRecords = $db->RowCount();

//Export data to CSV File
if (Mreq::tp('export') == 1) {

    $file_name = 'factures_list';
    $title = 'Liste des factures';
    if (Mreq::tp('format') == 'csv') {
        $header = array('ID', 'Référence', 'Totale', 'TVA','Client', 'Statut');
        Minit::Export_xls($header, $file_name, $title);
    } elseif (Mreq::tp('format') == 'pdf') {
        $header = array('ID' => 10, 'Référence' => 20, 'Totale' => 20,'TVA' => 15,'Client' => 15,'Statut' => 20);
        Minit::Export_pdf($header, $file_name, $title);
    } elseif (Mreq::tp('format') == 'dat') {
        Minit::send_big_param('fcts#' . $sqlTot);
    }
}


if (!$db->Query($sqlRec))
    $db->Kill($db->Error() . " SQLREC $sqlRec");
//
//iterate on results row and create new index array of data
while (!$db->EndOfSeek()) {
    $row = $db->RowValue();
    $data[] = $row;
}


$json_data = array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>