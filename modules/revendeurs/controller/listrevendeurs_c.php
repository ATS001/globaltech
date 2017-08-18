<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	


	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'denomination',
		2 =>'qualification', 
		3 =>'ville',
		4 =>'etat',
		5 =>'notif',
				
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " revendeurs,ref_ville";
    // define joint and table relation
	$joint .= " WHERE   revendeurs.ville  = ref_ville.id and ref_ville.etat=1";
	// set sherched columns.(the final colm without comma)
	$colms .= " revendeurs.id as id, ";
	$colms .= " revendeurs.denomination as denomination ,";
	$colms .= " revendeurs.qualification as secteur_activite , ";
	$colms .= " ref_ville.ville as ville ,";

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('revendeurs', 'revendeurs');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('revendeurs', 'revendeurs');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE revendeurs.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}else{

		$colms .= " CONCAT(CASE revendeurs.etat 
                WHEN '0' THEN '<span class=\"label label-sm label-warning\">Inactif</span>'
                WHEN '1' THEN '<span class=\"label label-sm label-success\">Actif</span>'
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



		$where .=" ( revendeurs.denomination LIKE '%".$serch_value."%' ";   
		$where .=" OR (revendeurs.id LIKE '%".$serch_value."%' )"; 
		//$where .=" OR (revendeurs.type_instal LIKE '%".$serch_value."%' ) ";
		$where .=" OR (revendeurs.piece_identite LIKE '%".$serch_value."%') ";
        $where .=" OR (revendeurs.num_agrement LIKE '%".$serch_value."%' ) "; 
        $where .=" OR (revendeurs.qualification LIKE '%".$serch_value."%' ) "; 
        $where .=" OR (ref_ville.ville LIKE '%".$serch_value."%' ) "; 
        $where .=" OR ( CASE revendeurs.etat 
                WHEN '0' THEN 'En Saisie'
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
    	
    	$file_name = 'revendeurs_list';
    	$title     = 'Liste Revendeurs ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Dénomination','Qualification','Ville' ,'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>5, 'Dénomination'=>35,'Qualification'=>25 ,'Ville'=>35 ,'Statut'=>10);
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
	
