
<?php
//array colomn
$array_column = array(
	array(
        'column' => 'users_sys.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => "CONCAT(users_sys.lnom,'  ',users_sys.fnom)",
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '35',
        'header' => 'Utilisateur',
        'align'  => 'L'
    ),
    array(
        'column' => 'services.service',
        'type'   => '',
        'alias'  => 'service',
        'width'  => '30',
        'header' => 'Service',
        'align'  => 'L'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '30',
        'header' => 'Statut',
        'align'  => 'L'
    ),
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('users_sys', 'services');
//Set Jointure
$list_data_table->joint = 'services.id = users_sys.service';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'users_sys';
//Set Task used for statut line
$list_data_table->task = 'user';
//Set File name for export
$list_data_table->file_name = 'Liste_utilisateurs';
//Set Title of report
$list_data_table->title_report = 'Liste Utilisateurs';
//Print JSON DATA

if(!$data = $list_data_table->Query_maker())
{
	exit("0#".$list_data_table->log);
}else{
	echo $data;
}



?>
	

