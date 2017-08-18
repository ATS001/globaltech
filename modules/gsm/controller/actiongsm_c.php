

<ul class="dropdown-menu dropdown-menu-right">
<?php 
$actions = new Mgsm();
$actions->id_gsm = Mreq::tp('id');
$actions->get_gsm();


$action = new TableTools();
$action->line_data = $actions->gsm_info;
//Set Task maine, Table maine, Creator user of line, Task to execute (delete) 
$action->action_line_table('gsm', 'gsm_stations', $actions->gsm_info['creusr'], 'deletegsm');




?>

</ul>
