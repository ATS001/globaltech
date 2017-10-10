<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller Liste

global $db;
$params = $columns = $totalRecords = $data = array();

$params = $_REQUEST;

//define index of column
$columns = array(
    0 => 'id',
    1 => 'reference',
    2 => 'fournisseur',
    3 =>  'date_effet',
    4 => 'date_fin',
    5 => 'statut'
);



$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
// define used table.
$tables .= "contrats_frn contrats_frn, fournisseurs f";
// define joint and rtable elation
$joint .= " AND contrats_frn.id_fournisseur = f.id  ";
// set sherched columns.(the final colm without comma)
$colms .= " contrats_frn.id AS id, ";
$colms .= " contrats_frn.reference as reference, ";
$colms .= " f.denomination as fournisseur, ";
$colms .= " contrats_frn.date_effet as date_effet, ";
$colms .= " contrats_frn.date_fin as date_fin, ";


//difine if user have rule to show line depend of etat 
$where_etat_line = TableTools::where_etat_line('contrats_frn', 'contrats_fournisseurs');
//define notif culomn to concatate with any colms.
//this is change style of button action to red
$notif_colms = TableTools::line_notif_new('contrats_frn', 'contrats_fournisseurs');
$colms .= $notif_colms;


// check search value exist
if (!empty($params['search']['value']) or Mreq::tp('id_search') != NULL) {

    $serch_value = str_replace('+', ' ', $params['search']['value']);
    //Format where in case joint isset  
    $where_s .= $joint == NULL ? " WHERE " : " AND ";



    $where_s .= " ( contrats_frn.id LIKE '%" . $serch_value . "%' ";
    $where_s .= " OR (contrats_frn.reference LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (f.denomination LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (contrats_frn.date_effet LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (contrats_frn.date_fin LIKE '%" . $serch_value . "%' ) ";
    $where_s .= TableTools::where_search_etat('contrats_frn', 'contrats_fournisseurs', $serch_value);
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

    $file_name = 'contrats_list';
    $title = 'Liste des contrats fournisseurs';
    if (Mreq::tp('format') == 'csv') {
        $header = array('ID', 'Référence', 'Fournisseur', 'Date effet','Date fin', 'Statut');
        Minit::Export_xls($header, $file_name, $title);
    } elseif (Mreq::tp('format') == 'pdf') {
        $header = array('ID' => 10, 'Référence' => 20, 'Fournisseur' => 20,'Date effet' => 15,'Date fin' => 15,'Statut' => 20);
        Minit::Export_pdf($header, $file_name, $title);
    } elseif (Mreq::tp('format') == 'dat') {
        Minit::send_big_param('contrats#' . $sqlTot);
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
