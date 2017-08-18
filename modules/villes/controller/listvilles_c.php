
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	
	//define index of column
	$columns = array( 
		0 =>'id_ville',
		1 =>'libelle',
		2 =>'region',
		3 =>'latitude', 
		4 =>'longitude',
		5 =>'statut',
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = $where_etat_line = "";
    // define used table.
	$tables .= " ref_ville, ref_region ";
    // define joint and table relation
	$joint .= " WHERE ref_region.id = ref_ville.id_region ";
	// set sherched columns.(the final colm without comma)
	$colms .= " ref_ville.id AS id_ville, ";	
	$colms .= " ref_ville.ville as libelle, ";
	$colms .= " ref_region.region as region, ";
	$colms .= " ref_ville.latitude as latitude, ";
	$colms .= " ref_ville.longitude as longitude, ";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('ref_ville', 'villes');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('ref_ville', 'villes');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " (CASE ref_ville.etat 
                WHEN '0' THEN 'Inactive'
                WHEN '1' THEN 'Active'
                ELSE ref_ville.etat 
                END) as statut ";
	}else{

		$colms .= " CONCAT(CASE ref_ville.etat 
                WHEN '0' THEN '<span class=\"label label-sm label-warning\">Inactive</span>'
                WHEN '1' THEN '<span class=\"label label-sm label-success\">Active</span>'
                ELSE ' ' END
                ,
                $notif_colms 
                ) as statut ";
	}
	
	      

    
	// check search value exist
	if( !empty($params['search']['value']) ) {

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where .= $joint == NULL? " WHERE " : " AND ";

		$where .=" ( ref_ville.ville LIKE '%".$serch_value."%' ";
		$where .=" OR ref_region.region LIKE '%".$serch_value."%' ";    
		$where .=" OR ref_ville.latitude LIKE '%".$serch_value."%' ";
		$where .=" OR ref_ville.longitude LIKE '%".$serch_value."%' ";
		
        
        $where .=" OR CASE ref_ville.etat 
                WHEN '0' THEN 'Inactive'
                WHEN '1' THEN 'Active'
                END LIKE '%".$serch_value."%' )";

	}

	$where = $where == ""?"":$where;
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
	//on case of order by other parametre this one is disabled 
    $order_notif = $params['order'][0]['column'] == 0 ? " CASE WHEN LOCATE('notif', statut) = 0  THEN 0 ELSE 1 END DESC ," : NULL;

    

 	$sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'villes_list';
    	$title     = 'Liste des villes ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Ville', 'Region','Latitude','Longitude', 'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Ville'=>30, 'Region'=>20,'Latitude'=>15,'Longitude'=>15, 'Statut'=>10);
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
	
