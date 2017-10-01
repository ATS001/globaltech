	<?php 
			
		global $db;
		$params = $columns = $totalRecords = $data = array();

		$params = $_REQUEST;

		//define index of column
		$columns = array(
	    0 => 'id',
	    1 => 'ref',
            2 => 'type',        
	    3 => 'designation',
	    4 => 'stock_min',
	    5 => 'statut'
	);
			

	$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
	// define used table.
	$tables .= "produits,ref_types_produits";
	// define joint and rtable elation
	// set sherched columns.(the final colm without comma)
	$colms .= " produits.id AS id, ";
	$colms .= " produits.ref as ref, ";
        $colms .= " ref_types_produits.type_produit as type, ";
	$colms .= " produits.designation as designation, ";
	$colms .= " produits.stock_min as stock_min, ";
        $joint .="AND produits.idtype=ref_types_produits.id";

		
		
		//define notif culomn to concatate with any colms.
		//this is change style of button action to red
		$notif_colms = TableTools::line_notif_new('produits', 'produits');
	    $colms .= $notif_colms;
	    //difine if user have rule to show line depend of etat 
		$where_etat_line = TableTools::where_etat_line('produits', 'produits');

		// check search value exist
		if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
		{

			$serch_value = str_replace('+',' ',$params['search']['value']);
	        


	    $where_s .= " AND( produits.ref LIKE '%" . $serch_value . "%' ";
	    $where_s .= " OR (produits.id LIKE '%" . $serch_value . "%') ";
	    $where_s .= " OR (produits.designation LIKE '%" . $serch_value . "%') ";
            $where_s .= " OR (ref_types_produits.type_produit LIKE '%" . $serch_value . "%') ";
	    $where_s .= " OR (produits.stock_min LIKE '%" . $serch_value . "%') ";
	    $where_s .= TableTools::where_search_etat('produits', 'produits', $serch_value);
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

	    $file_name = 'produits_list';
	    $title = 'Liste des produits';
	    if (Mreq::tp('format') == 'csv') {
	        $header = array('ID', 'Référence', 'Désignation', 'Stock minimale', 'Statut');
	        Minit::Export_xls($header, $file_name, $title);
	    } elseif (Mreq::tp('format') == 'pdf') {
	        $header = array('ID' => 5, 'Référence' => 25, 'Désignation' => 35, 'Stock minimale' => 15, 'Statut' => 20);
	        Minit::Export_pdf($header, $file_name, $title);
	    } elseif (Mreq::tp('format') == 'dat') {
	        Minit::send_big_param('produits#' . $sqlTot);
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
