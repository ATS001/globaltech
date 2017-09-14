<<<<<<< HEAD
<?php 
//SYS GLOBAL TECH
// Modul: clients => Controller Liste
		
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	//define index of column
$columns = array(
    0 => 'id',
    1 => 'ref',
    2 => 'idproduit',
    3 => 'qte',
    4 => 'statut'
);
		


$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
// define used table.
$tables .= "stock , produits";
// define joint and rtable elation
// set sherched columns.(the final colm without comma)
$colms .= " stock.id AS id, ";
$colms .= " produits.ref as refp, ";
$colms .= " stock.qte as qte, ";
$joint .= " AND stock.idproduit=produits.id AND stock.idproduit = ".Mreq::tp('id');


	
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('produits', 'buyproducts');
    $colms .= $notif_colms;
    //difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('produits', 'buyproducts');

	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
	{

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    /*$where_s .= $joint == NULL? " WHERE " : " AND ";*/


    $where_s .=" AND ( stock.id LIKE '%".$serch_value."%' ";     
    $where_s .= " OR (produits.ref LIKE '%" . $serch_value . "%') ";
    $where_s .= " OR (stock.qte LIKE '%" . $serch_value . "%') ";
    $where_s .= TableTools::where_search_etat('produits', 'buyproducts', $serch_value);
}
  
    
	//var_dump($where);

	//$where = $where == NULL ? NULL : $where;
	
	/**
	 * Check if Query have JOINT then format WHERE puting WHERE 1=1 before where_etat_line
	 * Check if Search active then and non JOINT format WHERE puting WHERE 1=1 before where_etat_line
	 */
	
	/*$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where_etat_line =  $where_s == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;

	$where .= $where_etat_line;*/
    $where .= $where_etat_line;
	$where .= $joint;
	$where .= $where_s == NULL ? NULL : $where_s;
	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables  ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != NULL) {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

	//if we use notification we must ordring lines by nofication rule in first
	//Change ('notif', status) with ('notif', column where notif code is concated)
	//on case of order by other parametre this one is disabled (Check Export query)
	
    $order_notif = TableTools::order_bloc($params['order'][0]['column']);

 	$sqlRec .=  " ORDER BY $order_notif  ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
if (Mreq::tp('export') == 1) {

    $file_name = 'achat_produit_list';
    $title = 'Liste des achats';
    if (Mreq::tp('format') == 'csv') {
        $header = array('ID', 'Produit', 'Quantité','Statut');
        Minit::Export_xls($header, $file_name, $title);
    } elseif (Mreq::tp('format') == 'pdf') {
        $header = array('ID' => 5, 'Produit' => 25,'Quantité' => 15,'Statut' => 20);
        Minit::Export_pdf($header, $file_name, $title);
    } elseif (Mreq::tp('format') == 'dat') {
        Minit::send_big_param('buyproduct#' . $sqlTot);
    }
}



//exit($sqlRec);
if (!$db->Query($sqlRec))
    $db->Kill($db->Error() . " SQLREC $sqlRec");
//
//iterate on results row and create new index array of data
while (!$db->EndOfSeek()) {
    $row = $db->RowValue();
    $data[] = $row;
}
//while( $row = mysqli_fetch_row($queryRecords) ) { 
//$data[] = $row;
//}	

$json_data = array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
=======
	<?php 
			
		global $db;
		$params = $columns = $totalRecords = $data = array();

		$params = $_REQUEST;

		//define index of column
		//define index of column
	$columns = array(
	    0 => 'id',
	    1 => 'ref',
	    2 => 'idproduit',
	    3 => 'qte',
	    4 => 'prix_achat',
	    5 => 'prix_vente',
	    6 => 'statut'
	);
			


	$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
	// define used table.
	$tables .= "stock , produits";
	// define joint and rtable elation
	// set sherched columns.(the final colm without comma)
	$colms .= " stock.id AS id, ";
	$colms .= " produits.ref as refp, ";
	$colms .= " stock.qte as qte, ";
	$colms .= " stock.prix_achat as pa, ";
	$colms .= " stock.prix_vente as pv, ";
	$joint .= " AND stock.idproduit=produits.id AND stock.idproduit = ".Mreq::tp('id');


		
		
		//define notif culomn to concatate with any colms.
		//this is change style of button action to red
		$notif_colms = TableTools::line_notif_new('stock', 'buyproducts');
	    $colms .= $notif_colms;
	    //difine if user have rule to show line depend of etat 
		$where_etat_line = TableTools::where_etat_line('stock', 'buyproducts');

		// check search value exist
		if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
		{

			$serch_value = str_replace('+',' ',$params['search']['value']);
	        //Format where in case joint isset  
		    /*$where_s .= $joint == NULL? " WHERE " : " AND ";*/


	    $where_s .=" AND ( stock.id LIKE '%".$serch_value."%' ";     
	    $where_s .= " OR (produits.ref LIKE '%" . $serch_value . "%') ";
	    $where_s .= " OR (stock.qte LIKE '%" . $serch_value . "%') ";
	    $where_s .= TableTools::where_search_etat('produits', 'buyproducts', $serch_value);
	}
	  
	    
		
	    $where .= $where_etat_line;
		$where .= $joint;
		$where .= $where_s == NULL ? NULL : $where_s;
		// getting total number records without any search
		$sql = "SELECT $colms  FROM  $tables  ";
		$sqlTot .= $sql;
		$sqlRec .= $sql;
		//concatenate search sql if value exist
		if(isset($where) && $where != NULL) {

			$sqlTot .= $where;
			$sqlRec .= $where;
		}

		//if we use notification we must ordring lines by nofication rule in first
		//Change ('notif', status) with ('notif', column where notif code is concated)
		//on case of order by other parametre this one is disabled (Check Export query)
		
	    $order_notif = TableTools::order_bloc($params['order'][0]['column']);

	 	$sqlRec .=  " ORDER BY $order_notif  ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


	    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
		//
	    $totalRecords = $db->RowCount();

	    //Export data to CSV File
	if (Mreq::tp('export') == 1) {

	    $file_name = 'achat_produit_list';
	    $title = 'Liste des achats';
	    if (Mreq::tp('format') == 'csv') {
	        $header = array('ID', 'Produit', 'Quantité','Prix d\'achat','Prix de vente','Statut');
	        Minit::Export_xls($header, $file_name, $title);
	    } elseif (Mreq::tp('format') == 'pdf') {
	        $header = array('ID' => 5, 'Produit' => 25,'Quantité' => 15,'Prix d\'achat' =>15,'Prix de vente'=> 15,'Statut' => 20);
	        Minit::Export_pdf($header, $file_name, $title);
	    } elseif (Mreq::tp('format') == 'dat') {
	        Minit::send_big_param('buyproduct#' . $sqlTot);
	    }
	}



	//exit($sqlRec);
	if (!$db->Query($sqlRec))
	    $db->Kill($db->Error() . " SQLREC $sqlRec");
	//
	//iterate on results row and create new index array of data
	while (!$db->EndOfSeek()) {
	    $row = $db->RowValue();
	    $data[] = $row;
	}
	//while( $row = mysqli_fetch_row($queryRecords) ) { 
	//$data[] = $row;
	//}	

	$json_data = array(
	    "draw" => intval($params['draw']),
	    "recordsTotal" => intval($totalRecords),
	    "recordsFiltered" => intval($totalRecords),
	    "data" => $data   // total data array
	);

	echo json_encode($json_data);  // send data as json format
	?>
>>>>>>> refs/remotes/origin/ayoub
