 <?php 
              
              echo TableTools::count_notif('users_sys', 'user'); 
              echo '</br>'     ;
              echo TableTools::count_notif('vsat_stations', 'vsat'); 
              echo '</br>'     ;
              echo TableTools::count_notif('users_sys', 'user');
              echo '</br>'     ;
              echo TableTools::count_notif('users_sys', 'user');
              echo '</br>'     ;
              echo TableTools::count_notif('users_sys', 'user');
              echo '</br>'     ;
              echo TableTools::count_notif('users_sys', 'user');
              echo '</br>'     ;
              echo TableTools::count_notif('users_sys', 'user');
?>
 <div class="pull-right tableTools-container">
	<div id="content" class="btn-group btn-overlap">
					
		
		<?php 
              //TableTools::btn_add('gsm', 'Liste Sites G.S.M', Null, $exec = NULL, 'reply');      
		 ?>					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Site G.S.M
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php

$sql = "SHOW FULL COLUMNS FROM sys_log";
global $db;
$arr_fields = array();
if(!$db->Query($sql))
{
			var_dump($db->Error());
}else{
	$arr_fields = $db->RecordsSimplArray();		
				
}
$line_action = "'fields'  => Mreq::tp('fields') ,";
$line_modul = '$values["fields"]       = MySQL::SQLValue($this->_data[\'fields\']);';

foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_action).'<br>';
}
print '<br>================<br>';
foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_modul).'<br>';
}

?>

			</div>
		</div>
	</div>	
</div>
