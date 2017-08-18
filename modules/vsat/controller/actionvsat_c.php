

<ul class="dropdown-menu dropdown-menu-right">
<?php 
$actions = new Mvsat();
$actions->id_vsat = Mreq::tp('id');
$actions->get_vsat();


$action = new TableTools();
$action->line_data = $actions->vsat_info;
//Set Task maine, Table maine, Creator user of line, Task to execute (delete) 
$action->action_line_table('vsat', 'vsat_stations', $actions->vsat_info['creusr'], 'deletevsat');

$id_marker = MInit::crypt_tp('id',$actions->vsat_info['marker_map']);
$single    = MInit::crypt_tp('singl',1);


?>
<li><a href="#" class="this_map" data="<?php echo $id_marker."&".$single ?>" rel="xxmap"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Afficher la MAP</a></li>
</ul>
