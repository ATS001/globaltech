<?php
$blr_info = new Mblr_stations();
$prm_info=new Mprms();


$blr_info->id_blr_stations = Mreq::tp('id');
$blr_info->get_blr_stations();

$prm_info->id_prm=$blr_info->Shw('prm',1);
$prm_info->get_prm();
?>
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	//SELECT `fnom`, `lnom`, `servic`,`active` FROM `users_sys`



	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'site',
		2 =>'longi', 
		3 =>'latit',
		4 =>'hauteur',
		5 =>'marque',
		6 =>'modele',
		7 =>'etat',
		8 =>'notif',
		
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " blr_stations, blr_clients";
    // define joint and rtable elation
	$joint .= " WHERE   blr_stations.id  = blr_clients.station_base ";
	$joint .= " AND blr_clients.station_base = ".Mreq::tp('id');
		// set sherched columns.(the final colm without comma)
	$colms .= " blr_clients.id as id, ";
	$colms .= " blr_clients.site as site, ";	
	$colms .= " blr_clients.longi as longitude, ";
	$colms .= " blr_clients.latit as latitude ,";
	$colms .= " blr_clients.hauteur as hauteur ,";
	$colms .= " blr_clients.marque as marque ,";
	$colms .= " blr_clients.modele as modele ,";



	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('blr_clients', 'blr_clients');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('blr_clients', 'blr_clients');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE blr_clients.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}else{

		$colms .= " CONCAT(CASE blr_clients.etat 
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


		$where .=" ( blr_clients.longi LIKE  $serch_value ";
		$where .=" OR blr_clients.latit LIKE $serch_value "; 
                $where .=" OR blr_stations.site LIKE $serch_value ";
		$where .=" OR blr_clients.hauteur LIKE $serch_value ";
		$where .=" OR blr_clients.marque LIKE $serch_value ";
		$where .=" OR blr_clients.modele LIKE $serch_value ";
				
        
        $where .=" OR CASE blr_stations.etat 
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
    	
    	$file_name = 'blr_list';
    	$title     = 'Liste des clients de '.$prm_info->Shw('r_social',1).' - Station de base '.$blr_info->Shw('site',1);
        
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID','Site', 'Longitude','Latitude' ,'Hauteur','Marque','Modèle','Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>5,'Site'=>20, 'Longitude'=>15, 'Latitude'=>15 ,'Hauteur'=>15,'Marque'=>10,'Modèle'=>10,'Statut'=>10);
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
	