<ul class="dropdown-menu dropdown-menu-right">
<?php 
$perm = new Mprms();//New instance for permissionnaire
$perm->id_prm = Mreq::tp('id'); //Set ID of permissionnaire POST AJAX
$perm->get_prm(); //Get All ino of permissionnaire

$action = new TableTools(); //New instance for table tools
$action->line_data = $perm->prm_info;//Set data for this line
//Set Task maine, Table maine, Creator user of line, Task to execute (delete) 
$action->action_line_table('prm', 'permissionnaires', $perm->prm_info['creusr'], 'deleteprm');

?>
</ul>
