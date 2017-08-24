<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => Controller Liste
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'categorie_client', 
		2 =>'statut'		
	);
		


	$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " categorie_client ";
	// define joint and rtable elation
	$joint .=  " where 1=1  ";
	// set sherched columns.(the final colm without comma)
	$colms .= " categorie_client.id AS id, ";	
	$colms .= " categorie_client.categorie_client as categorie_client, ";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('categorie_client', 'categorie_client');
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('categorie_client', 'categorie_client');
    $colms .= $notif_colms;
	

	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
	{

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where_s .= $joint == NULL? " WHERE " : " AND ";



		$where_s .=" ( categorie_client.categorie_client LIKE '%".$serch_value."%' ";   
		$where_s .=" OR (categorie_client.id LIKE '%".$serch_value."%') ";

        $where_s .= TableTools::where_search_etat('categorie_client', 'categorie_client', $serch_value);
               
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
	$sql = "SELECT $colms  FROM  $tables $joint $where ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where_s) && $where_s != '') {

		$sqlTot .= $where_s;
		$sqlRec .= $where_s;
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
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'categorie_client_list';
    	$title     = 'Liste Catégorie Client ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Catégorie_Client','Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}elseif(Mreq::tp('format')=='pdf'){
    		$header    = array('ID'=>10, 'Catégorie_Client'=>45,'Statut'=>45);
    		Minit::Export_pdf($header, $file_name, $title);
    	}elseif(Mreq::tp('format')=='dat'){
    		Minit::send_big_param('vsat#'.$sqlTot);
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
