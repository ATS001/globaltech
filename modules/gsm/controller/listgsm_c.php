
<?php

	$user_info = new Musers();
	$user_info->id_user = session::get('userid');
	$user_info->get_user();
	$service=$user_info->Shw('service_user',1);


	if ($service =='AIRTEL' OR $service =='TIGO' ) {
		$serv=" AND gsm_stations.operateur='".$service."'";
		}else
	{
		$serv=" ";
	}


	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'r_social',
		2 =>'nom_station', 
		3 =>'technologie',
		4 =>'ville',
		5 =>'etat'
		
	);
		
   //var_dump($service);

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " gsm_stations, ref_ville ";
    // define joint and rtable elation
	$joint .= " WHERE  gsm_stations.ville = ref_ville.id".$serv; 
	// set sherched columns.(the final colm without comma)
	$colms .= " gsm_stations.id AS id, ";
	$colms .= " gsm_stations.operateur as operateur, ";	
	$colms .= " gsm_stations.nom_station as nom_station, ";
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(
	        CASE gsm_stations.tech_2g WHEN 1 THEN '[2G]' ELSE '' END,
            CASE gsm_stations.tech_3g WHEN 1 THEN '[3G]' ELSE '' END ,
            CASE gsm_stations.tech_4g WHEN 1 THEN '[4G]' ELSE '' END,
            CASE gsm_stations.tech_cdma WHEN 1 THEN '[CDMA]' ELSE '' END ) as technologie, ";
            
        
	}else{
		$colms .= " CONCAT(
	        CASE gsm_stations.tech_2g WHEN 1 THEN '<span class=\"badge badge-pink\">2G</span>' ELSE '' END,
            CASE gsm_stations.tech_3g WHEN 1 THEN '<span class=\"badge badge-info\">3G</span>' ELSE '' END ,
            CASE gsm_stations.tech_4g WHEN 1 THEN '<span class=\"badge badge-success\">4G</span>' ELSE '' END,
            CASE gsm_stations.tech_cdma WHEN 1 THEN '<span class=\"badge badge-danger\">CDMA</span>' ELSE '' END ) as technologie, "; 
               
	}
	$colms .= " ref_ville.ville as ville, ";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('gsm_stations', 'gsm');
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('gsm_stations', 'gsm');

	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{

		$colms .= " CONCAT(CASE gsm_stations.etat 
                WHEN '0' THEN 'Invalide'
                WHEN '1' THEN 'Valide'
                ELSE ' ' END
                ,
                ' ') as statut ";
        
	}else{
	
		$colms .= " CONCAT(CASE gsm_stations.etat 
                WHEN '0' THEN '<span class=\"label label-sm label-warning\">Invalide</span>'
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
        //Format where in case joint isset  
	    $where .= $joint == NULL? " WHERE " : " AND ";



		$where .=" ( gsm_stations.nom_station LIKE '%".$serch_value."%' ";   
		$where .=" OR (gsm_stations.id LIKE '%".$serch_value."%') ";
		$where .=" OR (CONCAT(
	        CASE gsm_stations.tech_2g WHEN 1 THEN '[2G]' ELSE '' END,
            CASE gsm_stations.tech_3g WHEN 1 THEN '[3G]' ELSE '' END ,
            CASE gsm_stations.tech_4g WHEN 1 THEN '[4G]' ELSE '' END,
            CASE gsm_stations.tech_cdma WHEN 1 THEN '[CDMA]' ELSE '' END ) LIKE '%".$serch_value."%') ";
		$where .=" OR (ref_ville.ville LIKE '%".$serch_value."%') ";
        $where .=" OR (permissionnaires.r_social LIKE '%".$serch_value."%' ) "; 
        $where .=" OR ( CASE gsm_stations.etat 
                WHEN '0' THEN 'Invalide'
                WHEN '1' THEN 'Valide'
                END LIKE '%".$serch_value."%' ))";
               
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
    	
    	$file_name = 'gsm_list';
    	$title     = 'Liste Station GSM ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', ' Opérateur', 'Nom_station', 'Technologie', 'Ville' ,'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>5, 'Opérateur'=>15, 'Nom_station'=>30, 'Technologie'=>20, 'Ville'=>20, 'Statut'=>10);
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
