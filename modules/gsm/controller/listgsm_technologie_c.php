<?php 
//SYS MRN ERP
// Modul: technologie_gsm => Controller Liste

global $db;
$params = $columns = $totalRecords = $data = array();

$params = $_REQUEST;



	//define index of column
$columns = array( 
	0 =>'id',
	1 =>'technologie',
	2 =>'marque_bts', 
	3 =>'modele_antenne',
	4 =>'nbr_radio',
	5 =>'notif',
	);

	//Format all variables

$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
$tables .= " gsm_technologie, gsm_stations, technologies_gsm";
    // define joint and table relation
$joint .= " WHERE gsm_technologie.id_site_gsm  = gsm_stations.id and technologies_gsm.id=gsm_technologie.technologie ";
//When need add default where should be concated with $joint field wiyhout (WHERE)
$joint .= " AND gsm_stations.id = ". Mreq::tp('id');
	// set sherched columns.(the final colm without comma)
$colms .= " gsm_technologie.id as id_technologie, ";
$colms .= " technologies_gsm.libelle as technologie, ";
$colms .= " gsm_technologie.marque_bts as marque_bts ,";
$colms .= " gsm_technologie.modele_antenne as modele_antenne, ";
$colms .= " gsm_technologie.nbr_radio as nbr_radio ,";
$colms .= " gsm_technologie.nbr_secteur as nbr_secteur,  ";
$colms .= " gsm_technologie.num_serie as num_serie ";


//$colms .= " $notif_colms";

	//difine if user have rule to show line depend of etat 
$where_etat_line = TableTools::where_etat_line('gsm_technologie', 'gsm_technologie');


	// check search value exist
if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
{

	$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	$where .= $joint == NULL? " WHERE " : " AND ";



	$where .=" (   gsm_technologie.id LIKE '%".$serch_value."%' ";   
	$where .=" OR (technologies_gsm.libelle  LIKE '%".$serch_value."%' )"; 
	$where .=" OR (gsm_technologie.marque_bts LIKE '%".$serch_value."%' ) ";
	$where .=" OR (gsm_technologie.num_serie LIKE '%".$serch_value."%') ";
	$where .=" OR (gsm_technologie.modele_antenne LIKE '%".$serch_value."%' ) )"; 
	

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
	$order_notif = $params['order'][0]['column'] == 0 ?  : NULL;

	$sqlRec .=  " ORDER BY $order_notif ". $params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


	if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
	
	$totalRecords = $db->RowCount();

    //Export data to CSV File
	if( Mreq::tp('export')==1 )
	{

		$file_name = 'gsm_technologie_list';
		$title     = 'Liste GSM ';
		if(Mreq::tp('format')=='csv')
		{
			$header    = array('ID', 'Technologie', 'Marque BTS' ,'Modele antenne','Nombre des radios','Nombre des secteurs', 'Numéro de série');
			Minit::Export_xls($header, $file_name, $title);
		}else{

			$header    = array('ID'=>5, 'Technologie'=>18.75, 'Marque BTS'=>18.75,'Modele antenne'=>18.75,'Nombre des radios'=>10,'Nombre des secteurs'=>10, 'Numéro de série'=>18.75 );
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
	
<?php 
//SYS MRN ERP
// Modul: gsm => Controller Liste