
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	//SELECT `fnom`, `lnom`, `servic`,`active` FROM `users_sys`



	//define index of column
	$columns = array( 
		0 => 'id',
		1 => 'prm', 
		2 => 'site',
		3 => 'ville',
        4 =>'type_stations',
		5 =>'porte_antenne',
		6 =>'etat',
		7 =>'notif',
		
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " uhf_vhf_stations,permissionnaires, ref_ville";
    // define joint and rtable elation
	$joint .= " WHERE   uhf_vhf_stations.prm  = permissionnaires.id AND uhf_vhf_stations.ville = ref_ville.id  ";
	// set sherched columns.(the final colm without comma)
	$colms .= " uhf_vhf_stations.id as id, ";
	$colms .= " permissionnaires.r_social as rs, ";
	$colms .= " uhf_vhf_stations.site as site ,";
	$colms .= " ref_ville.ville as ville ,";
        $colms .= " uhf_vhf_stations.type_station as type ,";
	$colms .= " uhf_vhf_stations.porte_antenne as porte ,";



	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('uhf_vhf_stations', 'uhf_vhf_stations');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('uhf_vhf_stations', 'uhf_vhf_stations');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE uhf_vhf_stations.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}else{

		$colms .= " CONCAT(CASE uhf_vhf_stations.etat 
                WHEN '0' THEN '<span class=\"label label-sm label-warning\">Non Valide</span>'
                WHEN '1' THEN '<span class=\"label label-sm label-success\">Valide</span>'
                ELSE ' ' END
                ,
                $notif_colms 
                ) as statut ";
	}
	
	    


    
    
	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
	{

		$serch_value = str_replace('+',' ',$params['search']['value']);
		$serch_value = MySQL::SQLValue("%".$serch_value."%");
        //Format where in case joint isset  
	    $where .= $joint == NULL ? " WHERE " : " AND ";


		$where .=" ( permissionnaires.r_social LIKE  $serch_value ";
		$where .=" OR uhf_vhf_stations.id LIKE $serch_value ";    
		$where .=" OR ref_ville.ville LIKE $serch_value ";
		$where .=" OR uhf_vhf_stations.site LIKE $serch_value ";
		$where .=" OR uhf_vhf_stations.type_station LIKE $serch_value ";
		$where .=" OR uhf_vhf_stations.porte_antenne LIKE $serch_value ";
		
        
        $where .=" OR CASE uhf_vhf_stations.etat 
                WHEN '0' THEN 'Non Valide'
                WHEN '1' THEN 'Valide'
                END LIKE $serch_value )";

		              
    }

	//var_dump($where);

	$where = $where == NULL ? NULL : $where;
	
	/**
	 * Check if Query have JOINT then format WHERE puting WHERE 1=1 before where_etat_line
	 * Check if Search active then and non JOINT format WHERE puting WHERE 1=1 before where_etat_line
	 */
	
	//$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where_etat_line =  $where == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where .= $where_etat_line;

	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables $joint $where ";
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
    	
    	$file_name = 'uhfvhf_list';
    	$title     = 'Liste des stations UHF/VHF ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID','Permissionnaire' ,'Site','Ville','Type','Portée','Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>5, 'Permissionnaire'=>25 ,'Site'=>20,'Ville'=>15,'Type'=>10,'Portée'=>10,'Statut'=>10);
    		Minit::Export_pdf($header, $file_name, $title);
    	}
    	  	

    }

	
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
	
