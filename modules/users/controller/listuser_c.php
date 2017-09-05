
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	
	//define index of column
	$columns = array( 
		0 =>'id_user',
		//1 =>'photo',
		1 =>'nom', 
		2 =>'service',
		3 =>'statut',
		4 =>'notif',
		
	);

	//Format all variables
    $colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = NULL;
	
    // define used table.
	$tables .= " users_sys, services ";
    // define joint and rtable elation
	$joint .= " WHERE services.id = users_sys.service ";
	// set sherched columns.(the final colm without comma)
	$colms .= " users_sys.id AS id_user, ";

	//$colms .= " CONCAT('<div class=\"user\"><img class=\"nav-user-photo\" alt=\"\" src=\"./upload/useres/',users_sys.id,'/',MD5(users_sys.photo),'48x48.png\"></div>') as photo, ";
	
	$colms .= " CONCAT(users_sys.lnom,'  ',users_sys.fnom) as nom, ";
	$colms .= " services.service as service, ";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('users_sys', 'user');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('users_sys', 'user');
	$colms .= $notif_colms;
	
	    
	// check search value exist
	if( !empty($params['search']['value']) ) {

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where_s .= $joint == NULL? " WHERE " : " AND ";


		$where_s .=" ( CONCAT(users_sys.lnom,' ',users_sys.fnom) LIKE '%".$serch_value."%' ";    
		$where_s .=" OR services.service LIKE '%".$serch_value."%' ";
        $where_s .=" OR users_sys.id LIKE '%".$serch_value."%' ";
        $where_s .= TableTools::where_search_etat('users_sys', 'user', $serch_value);



	}
	//var_dump($where);

	$where = $where == NULL ? NULL : $where;
	
	/**
	 * Check if Query have JOINT then format WHERE puting WHERE 1=1 before where_etat_line
	 * Check if Search active then and non JOINT format WHERE puting WHERE 1=1 before where_etat_line
	 */
	
	$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where_etat_line =  $where_s == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	
	$where .= $where_etat_line;


	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables $joint ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

	//if we use notification we must ordring lines by nofication rule in first
	//Change ('notif', status) with ('notif', column where notif code is concated)
	//on case of order by other parametre this one is disabled (Check Export query)
	
    $order_notif = TableTools::order_bloc($params['order'][0]['column']);

    

 	$sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'user_list';
    	$title     = 'Liste utilisateur ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Nom & Prénom', 'Service', 'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}elseif(Mreq::tp('format')=='pdf'){
    		$header    = array('ID'=>10, 'Nom & Prénom'=>50, 'Service'=>20, 'Statut'=>20);
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
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);  // send data as json format
		

?>
	
