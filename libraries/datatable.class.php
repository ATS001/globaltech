<?php
/**
* DataTable code generator v1.0
*/
class Mdatatable 
{
	private $_data; //data receive from form
    //Declared Variable
    var $tables           = array();//tables of Query
    var $task             = null;//Task called for etat_line and notif status
    var $main_table       = null;//Main table used for notif and status
    var $list_table       = null;
    var $joint            = null;
    var $sqlTot           = null;
    var $sqlRec           = null;
    var $where            = null;
    var $where_s          = null;
    var $where_etat_line  = null;//Where Etat line used when need status and notif
    var $columns          = array();//columns of query array('column' => 'width[#]align')
    var $arr_used_columns = array();//Used final array 
    var $list_col         = null;
    var $need_notif       = true;
    var $order_notif      = null;//Used when call order statut column
    var $params           = array();//Array of $_REQUEST
    var $debug            = false;//Used when want see the full query probleme case
    var $file_name        = null;//Used for Export data 
    var $title_report     = null;//Used on Report exported
    var $error            = true;//Used to check error on methides
    var $log              = null;//return log error 


    public function __construct($properties = array()){
    	$this->params = $_REQUEST;
    }

    

    private function get_list_table()
    {
    	$arr_table = $this->tables;
    	$cont_table = count($arr_table);
    	$i = 0;
    	foreach ($arr_table as $table) {
    		if(++$i === $cont_table)
    		{
    			$v = ' ';
    		}else{
    			$v = ', ';
    		}
    		$this->list_table .= " ".$table."$v";
    	}
        if($this->list_table == null)
        {
            $this->error = false;
            $this->log   = '<br\>Pas de table selectionée';
        }
    }

    private function get_list_column()
    {
    	$arr_columns = $this->columns;
    	
    	$list_col = null;
    	
    	$cont_columns = count($arr_columns);
    	$i = 0;
    	foreach ($arr_columns as $key => $value) {
    		
    		if(++$i === $cont_columns && $this->need_notif == false)
    		{
    			$v = ' ';
    		}else{
    			$v = ', ';
    		}
            if($value['column'] !== 'statut'){
                switch ($value['type']) {
                    case 'int':
                    $list_col .= " REPLACE(FORMAT(".$value['column'].",0), ',', ' ')  as ".$value['alias']."$v";
                    break;
                    case 'date':
                    $list_col .= " DATE_FORMAT(".$value['column'].",'%d-%m-%Y')  as ".$value['alias']."$v";
                    break;
                    default:
                    $list_col .= " ".$value['column']." as ".$value['alias']."$v";
                    break;
                }

            }
    		
    		
    	}
    	if($this->need_notif && $this->task != null)
    	{
    		$notif_colms = TableTools::line_notif_new($this->main_table, $this->task);
    		$list_col .= $notif_colms;
    	}
    	$this->list_col = $list_col;
        if($this->list_col == null)
        {
            $this->error = false;
            $this->log   = '<br\>Pas de columns insérés';
        }
    }

    private function get_where()
    {
    	// check search value exist
    	$params = $this->params;
    	$where_s = null;
    	if( !empty($params['search']['value']) ) {
            $serch_value = str_replace('+',' ',$params['search']['value']);

            $arr_columns = $this->columns;

            $cont_columns = count($arr_columns);
            $i = 0;

            
            
            foreach ($arr_columns as $key => $value) {
                $operator = $i == 0 ? " AND ( " : " OR ";
                if($value['column'] != 'statut')
                {
                    $where_s .=" $operator ".$value['column']." LIKE '%".$serch_value."%' ";
                }
                $i++; 
            }

    		

    		if($this->need_notif && $this->task != null)
    		{
    			$where_s .= TableTools::where_search_etat($this->main_table, $this->task, $serch_value);
    		}
    		$this->where_s = $where_s;
    	}
    	if($this->need_notif == true && $this->task != null)
    	{
    		$this->where_etat_line = TableTools::where_etat_line($this->main_table, $this->task);
    	}

    }

    private function get_order()
    {
    	$params = $this->params;
    	$columns = array_column($this->columns, 'column');
    	if($this->need_notif)
    	{
    		$this->order_notif = TableTools::order_bloc($params['order'][0]['column']);
    	}
    	$this->sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
        
    }

    private function export_data($format)
    {
    	//Export data to CSV File
    	$file_name = $this->file_name;
    	$title     = $this->title_report;
        if($file_name == null or $title == null)
        {
            $this->error = false;
            $this->log   = '<br\>Paramètre export manquants';
        }
    	if($format == 'csv')
    	{
    		$header    = array_column($this->columns, 'alias');
            
    		Minit::Export_xls($header, $file_name, $title);
    	}elseif(Mreq::tp('format')=='pdf'){
           
            $headers = array();
           
            //Return Error 
            $sum_width = array_sum(array_column($this->columns, 'width'));
            
            foreach (array_column($this->columns, 'width') as $value) {
               if(!is_numeric($value)){
                    $this->error = false;
                    $this->log   = '<br\>Les largeurs des columns is_numeric';
                    return false;
                }
            }
            if($sum_width > 100)
            {
                $this->error = false;
                $this->log   = '<br\>La largeur total des columns dépasse 100';
                return false;
            }
            foreach ($this->columns as $key => $value) {
            	$titl  = $value['header'];
            	$width = $value['width'];
            	$align = $value['align'];
            	$headers[$titl] = $width.'[#]'.$align;
            }
            
            
            
    		if(!Minit::Export_pdf($headers, $file_name, $title))
            {
                $this->error = false;
                $this->log   = '<br\>Erreur export PDF';
            }

            if($this->error == false){
                return false;
            }


    	}
    }

    public function Query_maker()
    {
    	$params = $this->params;
    	$where = $where_s = $sqlTot = $sqlRec = NULL;
    	$data = array();
    	$this->get_list_table();
    	$tables = $this->list_table;
    	$this->get_list_column();
    	$columns = array_column($this->columns, 'column');
    	
    	$colms = $this->list_col;
    	$this->get_where();

    	$where .= $this->where_etat_line;
    	if($this->need_notif ==true){
    		$where .= ' AND '.$this->joint;
    	}else{
    		$where .= ' WHERE '.$this->joint;
    	}
    	
    	$where .= $this->where_s == NULL ? NULL : $this->where_s;

        
	// getting total number records without any search
    	$sql = "SELECT $colms  FROM  $tables  ";
    	$sqlTot .= $sql;
    	$sqlRec .= $sql;
	//concatenate search sql if value exist
    	if(isset($where) && $where != NULL) {

    		$sqlTot .= $where;
    		$sqlRec .= $where;
    	}
    	
       
    	if($this->need_notif)
    	{
    		array_push($columns, 'statut');
            array_push($columns, 'notif');
            $this->order_notif = TableTools::order_bloc($params['order'][0]['column']);
    	}
    	$order_notif = $this->order_notif;
    	$sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
        //Istance od DB connexion
        global $db;
    	if (!$db->Query($sqlTot)){

            $this->error = false;
            $this->log   = '<br\>Erreur SQL'.$db->Error()." $sqlTot";

        } 
	    //Get total of records
        $totalRecords = $db->RowCount();
        //Case Export call export methode
        if(Mreq::tp('export') == 1){
        	if(!$this->export_data(Mreq::tp('format')))
            {
                return false;
            }
        }

    	if($this->debug == true){
    		exit($sqlRec);
    	}
    	//Get only req for the LIMIT param
    	//if (!$db->Query($sqlRec)) $db->Kill($db->Error()." SQLREC $sqlRec");
	    if (!$db->Query($sqlRec)){

            $this->error = false;
            $this->log   = '<br\>Erreur SQL'.$db->Error()." $sqlRec";

        }

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
        if($this->error == false)
        {
            return false;
        }

    	return json_encode($json_data);
    }
}