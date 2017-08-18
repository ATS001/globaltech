
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	//SELECT `fnom`, `lnom`, `servic`,`active` FROM `users_sys`



	//define index of column
	$columns = array( 
		0 =>'id',
		//1 =>'photo',
		1 =>'r_social', 
		2 =>'categorie',
		3 =>'secteur_activ',
		4 =>'etat',
		5 =>'notif',
		
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " permissionnaires, ref_categ_prm,  ref_secteur_activite ";
    // define joint and rtable elation
	$joint .= " WHERE   ref_categ_prm.id  = permissionnaires.categorie AND ref_secteur_activite.id = permissionnaires.secteur_activ  ";
	// set sherched columns.(the final colm without comma)
	$colms .= " permissionnaires.id as id, ";
	$colms .= " permissionnaires.r_social , ";
	$colms .= " ref_categ_prm.categorie as categorie ,";
	$colms .= " ref_secteur_activite.secteur as secteur_activ ,";


	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('permissionnaires', 'prm');
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif('permissionnaires', 'prm');
	
	//Format this column depend of data draw export or datatable.
	if(Mreq::tp('export')==1)
	{
		$colms .= " CONCAT(CASE permissionnaires.etat 
                WHEN '0' THEN 'Inactif'
                WHEN '1' THEN 'Actif'
                ELSE ' ' END
                ,
                ' ') as statut ";
	}else{

		$colms .= " CONCAT(CASE permissionnaires.etat 
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
		$serch_value = MySQL::SQLValue("%".$serch_value."%");
        //Format where in case joint isset  
	    $where .= $joint == NULL? " WHERE " : " AND ";



		$where .=" ( permissionnaires.nom_p LIKE $serch_value ";   
		$where .=" OR (permissionnaires.id LIKE $serch_value )"; 
		$where .=" OR (ref_secteur_activite.secteur LIKE $serch_value )";
		$where .=" OR (ref_categ_prm.categorie LIKE $serch_value )";
        $where .=" OR (permissionnaires.r_social LIKE $serch_value ) "; 
        $where .=" OR ( CASE permissionnaires.etat 
                WHEN '0' THEN 'En Saisie'
                WHEN '1' THEN 'Valide'
                END LIKE $serch_value ))";
               
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
    	
    	$file_name = 'prm_list';
    	$title     = 'Liste Permissionnaires ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Raison Social', 'Catégorie Permissionnaire', 'Secteur d\'activité' ,'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>5, 'Raison Social'=>35, 'Catégorie_Perm'=>25, 'Secteur d\'activité'=>25 ,'Statut'=>10);
    		Minit::Export_pdf($header, $file_name, $title);
    	}
    	  	

    }

	//$sqlRec
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
	
