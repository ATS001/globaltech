
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'nom_station', 
		2 =>'r_social',
		3 =>'utilisation',
		4 =>'last_visite',
		5 =>'etat'
		
	);
		


	$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " vsat_stations, permissionnaires ";
    // define joint and rtable elation
	$joint .= " WHERE vsat_stations.id_perm = permissionnaires.id  ";
	// set sherched columns.(the final colm without comma)
	$colms .= " vsat_stations.id AS id, ";	
	$colms .= " vsat_stations.nom_station as nom_station, ";
	$colms .= " permissionnaires.r_social as r_social, ";
	$colms .= " vsat_stations.utilisation as utilisation, ";
	$colms .= " DATE_FORMAT(vsat_stations.last_visite,'%d-%m-%Y') as last_visite, ";


	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('vsat_stations', 'vsat');
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('vsat_stations', 'vsat');
    $colms .= $notif_colms;
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE vsat_stations.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}/*else{

		$colms .= " CONCAT(CASE vsat_stations.etat 
                WHEN '0' THEN '<span class=\"label label-sm label-warning\">Inactif</span>'
                WHEN '1' THEN '<span class=\"label label-sm label-success\">Actif</span>'
                ELSE ' ' END
                ,
                $notif_colms 
                ) as statut ";
                
	}*/

	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
	{

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where_s .= $joint == NULL? " WHERE " : " AND ";



		$where_s .=" ( vsat_stations.nom_station LIKE '%".$serch_value."%' ";   
		$where_s .=" OR (vsat_stations.id LIKE '%".$serch_value."%') ";
		$where_s .=" OR (vsat_stations.utilisation LIKE '%".$serch_value."%') ";
		$where_s .=" OR (vsat_stations.last_visite LIKE '%".$serch_value."%') ";
        $where_s .=" OR (permissionnaires.r_social LIKE '%".$serch_value."%' ) "; 
        $where_s .=" OR ( CASE vsat_stations.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'active'
                END LIKE '%".$serch_value."%' ))";
               
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
	//on case of order by other parametre this one is disabled 
    $order_notif = $params['order'][0]['column'] == 0 ? " CASE WHEN LOCATE('notif', statut) = 0  THEN 0 ELSE 1 END DESC ," : NULL;

 	$sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'vsat_list';
    	$title     = 'Liste Station VSAT ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Nom_station', ' Permissionnaire','Utilisation', 'Date visite' ,'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}elseif(Mreq::tp('format')=='pdf'){
    		$header    = array('ID'=>5, 'Nom_station'=>25, 'Permissionnaire'=>35,'Service'=>10, 'Date visite'=>15 ,'Statut'=>10);
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
