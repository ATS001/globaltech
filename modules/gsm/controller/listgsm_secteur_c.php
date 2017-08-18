<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	
	//define index of column
	$columns = array( 
		0 =>'id',	
		1 =>'num_secteur',
		2 =>'hba',                  
   		3 =>'azimuth',                      
   		4 =>'tilt_mecanique',  
   		6 =>'tilt_electrique',
		7 =>'etat',
				
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = $where_etat_line = NULL;
    // define used table.
	$tables.= " gsm_secteur,secteurs";
	//When need add default where should be concated with $joint field wiyhout (WHERE)
	$joint .= " WHERE gsm_secteur.num_secteur = secteurs.id and gsm_secteur.id_technologie = ". Mreq::tp('id');
	$colms .= " gsm_secteur.id AS id, ";
	$colms .= " secteurs.libelle as numsecteur, ";
	$colms .= " gsm_secteur.hba as h, ";
	$colms .= " gsm_secteur.azimuth as az, ";
	$colms .= " gsm_secteur.tilt_mecanique as tm, ";
	$colms .= " gsm_secteur.tilt_electrique as te, ";
	

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('gsm_secteur', 'gsm_secteur');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('gsm_secteur', 'gsm_secteur');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE gsm_secteur.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}else{

		$colms .= " CONCAT(CASE gsm_secteur.etat 
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
            $where .=" (secteurs.libelle LIKE '%".$serch_value."%' ";
			$where .=" OR gsm_secteur.hba LIKE '%".$serch_value."%' ";
			$where .=" OR gsm_secteur.azimuth LIKE '%".$serch_value."%' ";
			$where .=" OR gsm_secteur.tilt_mecanique LIKE '%".$serch_value."%' ";
			$where .=" OR gsm_secteur.tilt_electrique LIKE '%".$serch_value."%' ";
            $where .=" OR gsm_secteur.id LIKE '%".$serch_value."%' ";
            $where .=" OR CASE gsm_secteur.etat 
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
    	
    	$file_name = 'secteurs_list';
    	$title     = 'Liste secteurs ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Numéro secteur', 'HBA','Azimuth','Tilt mécanique','Tilt électrique');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Num secteur'=>15, 'HBA'=>20,'Azimuth'=>15,'Tilt mécanique'=>20,'Tilt électrique'=>20);
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
