
<?php
	//Get connected user
	$user_id=Mreq::tp('id');

	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	
	//define index of column
	$columns = array( 
		0 => 'id',
		1 => 'operation',
		2 => 'nom',
		3 => 'date_operation',
		4 => 'duree',
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = $where_etat_line = NULL;
    // define used table.
	$tables .= " users_sys,session";
    // define joint and rtable elation
	$joint .= "WHERE users_sys.id=session.userid AND users_sys.ID=".$user_id ;
	// set sherched columns.(the final colm without comma)
	$colms .= " session.id_sys as id,";
	$colms .= " IF (session.expir IS NULL,'Session ouverte','Ouverture de session') as operation,";
	$colms .= " CONCAT(users_sys.fnom,' ',users_sys.lnom) as nom,";
	$colms .= " DATE_FORMAT(session.dat,'%d-%m-%Y %H:%i') as date_operation,";
	$colms .= " CONCAT( TIMESTAMPDIFF(MINUTE, session.dat, (IF (session.expir IS NULL,NOW(),session.expir))), ' minutes') as duree";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('users_sys', 'history');
	    
	// check search value exist
	if( !empty($params['search']['value']) ) {

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where .= $joint == NULL? " WHERE " : " AND ";

		$where .=" (session.id_sys LIKE '%".$serch_value."%'"; 
		$where .=" OR IF (session.expir IS NULL,'Session ouverte','Ouverture de session') LIKE '%".$serch_value."%'"; 
		$where .=" OR CONCAT(users_sys.fnom,' ',users_sys.lnom) LIKE '%".$serch_value."%'";  
		$where .=" OR DATE_FORMAT(session.dat,'%d-%m-%Y %H:%i') LIKE '%".$serch_value."%'";
		$where .=" OR CONCAT( TIMESTAMPDIFF(MINUTE, session.dat, (IF (session.expir IS NULL,NOW(),session.expir))), ' minutes') LIKE '%".$serch_value."%')";     

	}
	//var_dump($where);

	$where = $where == NULL ? NULL : $where;
	
	/**
	 * Check if Query have JOINT then format WHERE puting WHERE 1=1 before where_etat_line
	 * Check if Search active then and non JOINT format WHERE puting WHERE 1=1 before where_etat_line
	 */
	
	$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where_etat_line =  $where == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where .= $where_etat_line;


	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables $joint";
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
    
    //on a pas de notification
    //$order_notif = $params['order'][0]['column'] == 0 ? NULL : NULL;
    //var_dump($params['order']);

 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column'][0]]."   ".
 	$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

   
    
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
	
