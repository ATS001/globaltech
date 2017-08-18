
<?php
		
global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	//SELECT `fnom`, `lnom`, `servic`,`active` FROM `users_sys`



	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'dscrip',
		2 =>'file',
		3 =>'type_view',
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " task, modul ";
    // define joint and rtable elation start always with 'WHERE'
	$joint .= " WHERE task.modul = modul.id ";
	// set sherched columns.(the final colm without comma)
	$colms .= " task.id, ";
	//$colms .= " CONCAT('<div class=\"user\"><img class=\"nav-user-photo\" alt=\"\" src=\"./upload/useres/',users_sys.id,'/',MD5(users_sys.photo),'48x48.png\"></div>') as photo, ";
	
	$colms .= " task.dscrip, ";
	$colms .= " CONCAT(task.rep,' / ',task.file) as file, ";
	$colms .= " task.type_view ";
	//default where";
	$where .= " AND modul.id = ".Mreq::tp('id');
	
	



    
    
	// check search value exist
	if( !empty($params['search']['value']) ) {

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where .= $joint == ""? " WHERE " : " AND ";


		$where .=" task.file LIKE '%".$serch_value."%' ";    
		$where .=" OR task.dscrip LIKE '%".$serch_value."%' ";
		$where .=" OR task.rep LIKE '%".$serch_value."%' ";
		$where .=" OR task.type_view LIKE '%".$serch_value."%' ";
        $where .=" OR task.id LIKE '%".$serch_value."%' ";
        

	}


	// getting total number records without any search
	
	$sql = "SELECT $colms  FROM  $tables $joint ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}


 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'task_list';
    	$title     = 'Liste Task  ';
    	if(Mreq::tp('format')=='csv')
    	{
    	/*	0 =>'id',
		1 =>'dscrip',
		2 =>'file',
		3 =>'type_view',*/
    		$header    = array('ID', 'Description', 'File', 'Type_view');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Description'=>40, 'File'=>35, 'Type_view'=>15);
    		Minit::Export_pdf($header, $file_name, $title);
    	}
    	  	

    }

	//
    if (!$db->Query($sqlRec)) $db->Kill($db->Error()." SQLREC $sqlRec");
	//
   
	//iterate on results row and create new index array of data
	 while (!$db->EndOfSeek()) {
      $row = $db->RowValue();
	  $data[] = $row;
	 }
	
	//exit($sqlRec);

	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo(json_encode($json_data));  // send data as json format

?>
	
