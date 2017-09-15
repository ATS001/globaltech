<?php
  global $db;
  
  $params = $columns = $totalRecords = $data = array();
  $params = $_REQUEST;
  
  //define index of column
  $columns = array( 
    0 =>'date_echeance',
    1 =>'commentaire', 
  );

  $tkn_frm = Mreq::tp('tkn_frm');
  //Format all variables
  $colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = NULL;
  $main_table = 'echeances_contrat'; 
    // define used table.
  $tables .= " $main_table ";
    // define joint and rtable elation
  $joint .= " WHERE $main_table.tkn_frm = '$tkn_frm'  ";
  // set  columns.(the final colm without comma)

  $colms .= " $main_table.id, ";
  $colms .= " $main_table.date_echeance, ";
  
  $colms .= " $main_table.commentaire, ";
  $colms .= " ' ' as statut";

  

  //define notif culomn to concatate with any colms.
  //this is change style of button action to red
  //$notif_colms = TableTools::line_notif_new('users_sys', 'user');
  //$colms .= $notif_colms;
  //difine if user have rule to show line depend of etat 
  //$where_etat_line = TableTools::where_etat_line('users_sys', 'user');
      
  // check search value exist
  if( !empty($params['search']['value']) ) {

    $serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
      //$where_s .= $joint == NULL? " WHERE " : " AND ";


    $where_s .=" AND ( main_table.date_echeance LIKE '%".$serch_value."%' ";    
    $where_s .=" OR $main_table.commentaire LIKE '%".$serch_value."%' )";
    
  }
  //var_dump($where);

  //$where = $where == NULL ? NULL : $where;
  
  /**
   * Check if Query have JOINT then format WHERE puting WHERE 1=1 before where_etat_line
   * Check if Search active then and non JOINT format WHERE puting WHERE 1=1 before where_etat_line
   */
  
  //$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
  //$where_etat_line =  $where_s == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
  
  //$where .= $where_etat_line;
  $where .= $joint;
  $where .= $where_s == NULL ? NULL : $where_s;


  // getting total number records without any search
  $sql = "SELECT $colms  FROM  $tables  ";
  $sqlTot .= $sql;
  $sqlRec .= $sql;
  //concatenate search sql if value exist
  if(isset($where) && $where != NULL) {

    $sqlTot .= $where;
    $sqlRec .= $where;
  }

  //if we use notification we must ordring lines by nofication rule in first
  //Change ('notif', status) with ('notif', column where notif code is concated)
  //on case of order by other parametre this one is disabled (Check Export query)
  
 $order_notif = TableTools::order_bloc($params['order'][0]['column']);

    

  $sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
    $totalRecords = $db->RowCount();
  //
 /*   

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
      
      $file_name = 'user_list';
      $title     = 'Liste utilisateur ';
      if(Mreq::tp('format')=='csv')
      {
        $header    = array('ID', 'Nom & Prénom', 'Service', 'Statut');
        Minit::Export_xls($header, $file_name, $title);
      }elseif(Mreq::tp('format')=='pdf'){
        $header    = array('ID'=>10, 'Nom & Prénom'=>50, 'Service'=>20, 'Statut'=>20);
        Minit::Export_pdf($header, $file_name, $title);
      }
          

    }*/
    
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
  
