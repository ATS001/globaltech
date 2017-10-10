<?php
/*$json = '{"a":"1","b":2,"c":3,"d":4}';
$arr = json_decode($json, true);
var_dump($arr);*/
$yes = Msetting::get_set('tva');
var_dump($yes);
//$ste = new MSte_info();
//print($ste->get_ste_info_report_footer(1));
$table = 'ste_info';
$sql = "SHOW FULL COLUMNS FROM $table";
global $db;
$arr_fields = array();
if(!$db->Query($sql))
{
			var_dump($db->Error());
}else{
	$arr_fields = $db->RecordsArray();		
	var_dump($arr_fields);			
}
$line_action = "'fields'  => Mreq::tp('fields') ,";
$line_select = '$colms'. ".= \" $table.fields, \"";
$line_modul = '$values["fields"]       = MySQL::SQLValue($this->_data[\'fields\']);';

/*foreach ($arr_fields as $key => $value) {
	if(!in_array($value[0], array('credat', 'upddat', 'creusr', 'updusr'))){
    	print str_replace('fields', $value[0], $line_action).PHP_EOL;
    }
	//print str_replace('fields', $value[0], $line_action).'<br>';
}
print '<br>================<br>';
foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_modul).'<br>';
}
print '<br>================<br>';

foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_select).'<br>';
}*/
?>

<div class="row">
	<div class="col-xs-12">
		<div class="infobox infobox-red  infobox-small infobox-dark">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-download"></i>
			</div>

			<div class="infobox-data">
				<div class="infobox-content">Downloads</div>
				<div class="infobox-content">1,205</div>
			</div>
		</div>
		<div class="infobox infobox-grey  infobox-small infobox-dark">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-download"></i>
			</div>

			<div class="infobox-data">
				<div class="infobox-content">Downloads</div>
				<div class="infobox-content">1,205</div>
			</div>
		</div>
		<div class="infobox infobox-blue infobox-small infobox-dark">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-download"></i>
			</div>

			<div class="infobox-data">
				<div class="infobox-content">Downloads</div>
				<div class="infobox-content">1,205</div>
			</div>
		</div>
		<div class="infobox infobox-green infobox-small infobox-dark">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-download"></i>
			</div>

			<div class="infobox-data">
				<div class="infobox-content">Downloads</div>
				<div class="infobox-content">1,205</div>
			</div>
		</div>
		<div class="infobox infobox-grey  infobox-small infobox-dark">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-download"></i>
			</div>

			<div class="infobox-data">
				<div class="infobox-content">Downloads</div>
				<div class="infobox-content">1,205</div>
			</div>
		</div>
		<div class="infobox infobox-grey  infobox-small infobox-dark">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-download"></i>
			</div>

			<div class="infobox-data">
				<div class="infobox-content">Downloads</div>
				<div class="infobox-content">1,205</div>
			</div>
		</div>
	</div>
</div>