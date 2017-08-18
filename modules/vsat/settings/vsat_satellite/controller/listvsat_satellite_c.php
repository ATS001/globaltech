
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	
	//define index of column
	$columns = array( 
		0 =>'id_satellite',	
		1 =>'satellite',
		2 =>'position_orbitale',                  
   		3 => 'pay_operator',                      
   		4 => 'contractor',                      
		5 =>'etat',
				
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = $where_etat_line = NULL;
    // define used table.
	$tables  .= " vsat_satellite";
	$colms   .= " vsat_satellite.id AS id_satellite, ";
	$colms   .= " vsat_satellite.satellite as satellite, ";
	$colms   .= " vsat_satellite.position_orbitale as porbitale, ";
	$colms   .= " vsat_satellite.pay_operator as poperator, ";
	$colms   .= " vsat_satellite.contractor as contractor, ";
	//$colms .= " vsat_satellite.etat as etat, ";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('vsat_satellite', 'vsat_satellite');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('vsat_satellite', 'vsat_satellite');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE vsat_satellite.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}else{

		$colms .= " CONCAT(CASE vsat_satellite.etat 
                WHEN '0' THEN '<span class=\"label label-sm label-warning\">Inactif</span>'
                WHEN '1' THEN '<span class=\"label label-sm label-success\">Actif</span>'
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




		//$where .=" ( CONCAT(radcheck.nom,' ',radcheck.prenom) LIKE '%".$serch_value."%' ";  
            $where .=" (vsat_satellite.satellite LIKE '%".$serch_value."%' ";
			$where .=" OR vsat_satellite.position_orbitale LIKE '%".$serch_value."%' ";
			$where .=" OR vsat_satellite.pay_operator LIKE '%".$serch_value."%' ";
			$where .=" OR vsat_satellite.contractor LIKE '%".$serch_value."%' ";
            $where .=" OR vsat_satellite.id LIKE '%".$serch_value."%' ";
            $where .=" OR CASE vsat_satellite.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Active'
                END LIKE '%".$serch_value."%' )";

	}


	//$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where_etat_line =  $where == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
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
    	
    	$file_name = 'satellite_list';
    	$title     = 'Liste satellite ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Satellite', 'position orbitale','Pays','Fournisseur');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Satellite'=>50, 'position orbital'=>20,'Pays'=>40,'Fournisseur'=>50);
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
