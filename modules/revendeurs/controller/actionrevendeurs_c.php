<ul class="dropdown-menu dropdown-menu-right">

<?php 
$rev = new Mrev();//New instance for revendeur
$rev->id_rev = Mreq::tp('id'); //Set ID of revendeur POST AJAX
$rev->get_rev(); //Get All ino of revendeur

$action = new TableTools(); //New instance for table tools
$action->line_data = $rev->rev_info;//Set data for this line
//Set Task maine, Table maine, Creator user of line, Task to execute (delete) 
$action->action_line_table('revendeurs', 'revendeurs', $rev->rev_info['creusr'], 'deleterevendeur');
?>
</ul>
