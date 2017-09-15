<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => Controller Liste
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	
	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'type_echeance', 
		2 =>'statut',
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $where_s=$sqlTot = $sqlRec = "";

    // define used table.
	$tables .= " ref_type_echeance ";
    // define joint and rtable elation
	$joint .= " ";
	// set sherched columns.(the final colm without comma)
	$colms .= " ref_type_echeance.id AS id_type_echeance, ";	
	$colms .= " ref_type_echeance.type_echeance as type_echeance, ";
	

	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('ref_type_echeance', 'type_echeance');
	$colms .= $notif_colms;

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('ref_type_echeance', 'type_echeance');
	
    
	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL)  {

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where_s .= $joint == NULL? " WHERE " : " AND ";


		$where_s .=" (ref_type_echeance.type_echeance LIKE '%".$serch_value."%' ";  
		$where_s .=" OR ref_type_echeance.id LIKE '%".$serch_value."%' )";

		$where_s .= TableTools::where_search_etat('ref_type_echeance', 'type_echeance', $serch_value);

	}

	$where .= $where_etat_line;
	$where .= $joint;
	$where .= $where_s == NULL ? NULL : $where_s;
	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

	//if we use notification we must ordring lines by nofication rule in first
	//Change ('notif', status) with ('notif', column where notif code is concated)
	//on case of order by other parametre this one is disabled 
    $order_notif = $params['order'][0]['column'] == 0 ? " CASE WHEN LOCATE('notif', statut) = 0  THEN 0 ELSE 1 END DESC ," : NULL;

    

 	$sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'type_echeance_list';
    	$title     = 'Liste des Types Echeance ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Type Echéance','Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Type Echéance'=>70, 'Statut'=>20);
    		Minit::Export_pdf($header, $file_name, $title);
    	}
    	  	

    }
    
	//exit($sqlRec);
    if (!$db->Query($sqlRec)) $db->Kill($db->Error()." SQLREC $sqlRec");
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
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval( $totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);  // send data as json format
		

?>
	
